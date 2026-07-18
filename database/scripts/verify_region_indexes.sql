-- Verification Script for Region Foreign Key Indexes
-- This script verifies all foreign key columns have proper indexes
-- and tests the performance of cascading dropdown queries

-- ============================================================================
-- 1. VERIFY SINGLE COLUMN FOREIGN KEY INDEXES
-- ============================================================================

-- Provinces table (no FKs, but id is primary key)
SELECT 'PROVINCES' as 'Table', 'id' as 'Column', 'PRIMARY KEY' as 'Index Type', 'N/A' as 'Status' 
UNION ALL

-- Regencies: province_id FK
SELECT 'REGENCIES', 'province_id', 'FK Index', 
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'regencies' 
    AND COLUMN_NAME = 'province_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING')
UNION ALL

-- Districts: regency_id FK
SELECT 'DISTRICTS', 'regency_id', 'FK Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'districts' 
    AND COLUMN_NAME = 'regency_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING')
UNION ALL

-- Villages: district_id FK
SELECT 'VILLAGES', 'district_id', 'FK Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'villages' 
    AND COLUMN_NAME = 'district_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING')
UNION ALL

-- Orders: province_id FK
SELECT 'ORDERS', 'province_id', 'FK Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND COLUMN_NAME = 'province_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING')
UNION ALL

-- Orders: regency_id FK
SELECT 'ORDERS', 'regency_id', 'FK Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND COLUMN_NAME = 'regency_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING')
UNION ALL

-- Orders: district_id FK
SELECT 'ORDERS', 'district_id', 'FK Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND COLUMN_NAME = 'district_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING')
UNION ALL

-- Orders: village_id FK
SELECT 'ORDERS', 'village_id', 'FK Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND COLUMN_NAME = 'village_id'
    AND SEQ_IN_INDEX = 1
  ), '✓ Indexed', '✗ MISSING');

-- ============================================================================
-- 2. VERIFY COMPOSITE INDEXES FOR CASCADING QUERIES
-- ============================================================================

SELECT '=== COMPOSITE INDEXES (for cascading dropdowns) ===' as '';

-- Regencies composite index
SELECT 'REGENCIES' as 'Table', 'idx_regencies_province_name' as 'Index',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'regencies' 
    AND INDEX_NAME = 'idx_regencies_province_name'
  ), '✓ Exists', '⚠ Not yet applied (pending migration)')
UNION ALL

-- Districts composite index
SELECT 'DISTRICTS', 'idx_districts_regency_name',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'districts' 
    AND INDEX_NAME = 'idx_districts_regency_name'
  ), '✓ Exists', '⚠ Not yet applied (pending migration)')
UNION ALL

-- Villages composite index
SELECT 'VILLAGES', 'idx_villages_district_name',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'villages' 
    AND INDEX_NAME = 'idx_villages_district_name'
  ), '✓ Exists', '⚠ Not yet applied (pending migration)')
UNION ALL

-- Orders composite indexes
SELECT 'ORDERS', 'idx_orders_province_payment',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND INDEX_NAME = 'idx_orders_province_payment'
  ), '✓ Exists', '⚠ Not yet applied (pending migration)')
UNION ALL

SELECT 'ORDERS', 'idx_orders_region_cascade',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND INDEX_NAME = 'idx_orders_region_cascade'
  ), '✓ Exists', '⚠ Not yet applied (pending migration)')
UNION ALL

SELECT 'ORDERS', 'idx_orders_region_hierarchy',
  IF(EXISTS(
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'orders' 
    AND INDEX_NAME = 'idx_orders_region_hierarchy'
  ), '✓ Exists', '⚠ Not yet applied (pending migration)');

-- ============================================================================
-- 3. ANALYZE CURRENT INDEXES
-- ============================================================================

SELECT '=== ALL INDEXES ON REGION TABLES ===' as '';

SELECT TABLE_NAME, INDEX_NAME, COLUMN_NAME, SEQ_IN_INDEX as 'Position'
FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('provinces', 'regencies', 'districts', 'villages', 'orders')
AND COLUMN_NAME IN ('id', 'name', 'province_id', 'regency_id', 'district_id', 'village_id')
ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX;

-- ============================================================================
-- 4. QUERY EXECUTION PLANS (EXPLAIN examples after migration)
-- ============================================================================

SELECT '=== EXECUTION PLAN EXAMPLES ===' as '';
SELECT 'After migration, run these EXPLAIN queries to verify optimization:' as '';

-- Example 1: Cascading regencies query
-- EXPLAIN SELECT * FROM regencies WHERE province_id = '11' ORDER BY name;
-- Expected: Using index (idx_regencies_province_name), Using filesort: No

-- Example 2: Cascading districts query  
-- EXPLAIN SELECT * FROM districts WHERE regency_id = '1101' ORDER BY name;
-- Expected: Using index (idx_districts_regency_name), Using filesort: No

-- Example 3: Cascading villages query
-- EXPLAIN SELECT * FROM villages WHERE district_id = '110101' ORDER BY name;
-- Expected: Using index (idx_villages_district_name), Using filesort: No

-- Example 4: Orders with cascading region filter
-- EXPLAIN SELECT * FROM orders WHERE province_id = '11' AND regency_id = '1101' AND district_id = '110101';
-- Expected: Using index (idx_orders_region_cascade)

-- ============================================================================
-- 5. INDEX STATISTICS
-- ============================================================================

SELECT '=== INDEX SIZE AND STATISTICS ===' as '';

SELECT 
  TABLE_NAME,
  INDEX_NAME,
  ROUND(STAT_VALUE * @@innodb_page_size / 1024 / 1024, 2) as 'Index Size (MB)',
  STAT_NAME
FROM INFORMATION_SCHEMA.INNODB_SYS_TABLESTATS stats
JOIN INFORMATION_SCHEMA.INNODB_INDEXES idx ON stats.TABLE_ID = idx.TABLE_ID
WHERE stats.TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('provinces', 'regencies', 'districts', 'villages')
AND STAT_NAME = 'size';

-- ============================================================================
-- Summary
-- ============================================================================

SELECT CONCAT(
  'Index Verification Complete. ',
  'Status: All foreign key columns should be indexed. ',
  'Composite indexes optimize cascading dropdown queries.'
) as 'Summary';
