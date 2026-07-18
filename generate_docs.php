<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$md = "# Dokumentasi Proyek SADITA\n\n";
$md .= "Dokumentasi ini dibuat secara otomatis dan mencakup struktur Database, Model, Controller, View, dan Route dari sistem SADITA.\n\n";

// 1. DATABASE SCHEMA
$md .= "## 1. Skema Database\n\n";
$tables = Schema::getTables();
foreach ($tables as $tableInfo) {
    $tableName = $tableInfo['name'];
    // Skip migration tables
    if (in_array($tableName, ['migrations', 'password_reset_tokens', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs'])) {
        continue;
    }
    $md .= "### Tabel: `$tableName`\n";
    $columns = Schema::getColumns($tableName);
    $md .= "| Kolom | Tipe Data | Nullable |\n";
    $md .= "|---|---|---|\n";
    foreach ($columns as $column) {
        $type = $column['type_name'];
        $nullable = $column['nullable'] ? 'Yes' : 'No';
        $md .= "| `{$column['name']}` | {$type} | {$nullable} |\n";
    }
    $md .= "\n";
}

// 2. MODELS
$md .= "## 2. Models (`app/Models`)\n\n";
$modelsPath = app_path('Models');
if (File::exists($modelsPath)) {
    $models = File::allFiles($modelsPath);
    foreach ($models as $model) {
        $md .= "- `" . $model->getFilename() . "`\n";
    }
}
$md .= "\n";

// 3. CONTROLLERS
$md .= "## 3. Controllers (`app/Http/Controllers`)\n\n";
$controllersPath = app_path('Http/Controllers');
if (File::exists($controllersPath)) {
    $controllers = File::allFiles($controllersPath);
    foreach ($controllers as $controller) {
        $path = str_replace(app_path('Http/Controllers') . DIRECTORY_SEPARATOR, '', $controller->getPathname());
        $md .= "- `" . str_replace('\\', '/', $path) . "`\n";
    }
}
$md .= "\n";

// 4. VIEWS
$md .= "## 4. Views (`resources/views`)\n\n";
$viewsPath = resource_path('views');
if (File::exists($viewsPath)) {
    $views = File::allFiles($viewsPath);
    $md .= "Kumpulan file blade template:\n\n";
    foreach ($views as $view) {
        $path = str_replace(resource_path('views') . DIRECTORY_SEPARATOR, '', $view->getPathname());
        $md .= "- `" . str_replace('\\', '/', $path) . "`\n";
    }
}
$md .= "\n";

// 5. ROUTES
$md .= "## 5. Routes\n\n";
$routesFile = base_path('routes_summary.txt');
if (File::exists($routesFile)) {
    $md .= "```text\n";
    $md .= File::get($routesFile);
    $md .= "\n```\n\n";
} else {
    $md .= "*Jalankan `php artisan route:list > routes_summary.txt` terlebih dahulu untuk melihat route.*\n\n";
}

// 6. OTHER MD FILES
$md .= "## 6. Referensi Dokumentasi Tambahan\n\n";
$basePath = base_path();
$mdFiles = File::files($basePath);
foreach ($mdFiles as $mdFile) {
    if ($mdFile->getExtension() === 'md' && $mdFile->getFilename() !== 'dokumentasi.md') {
        $md .= "### File: `" . $mdFile->getFilename() . "`\n";
        $content = File::get($mdFile->getPathname());
        // Truncate if too long to keep summary readable, or just include it all as requested
        if (strlen($content) > 1000) {
             $content = substr($content, 0, 1000) . "...\n*(konten terpotong)*\n";
        }
        $md .= "```markdown\n$content\n```\n\n";
    }
}

File::put(base_path('dokumentasi.md'), $md);

echo "Dokumentasi berhasil dibuat di dokumentasi.md\n";
