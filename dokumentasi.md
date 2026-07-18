# Dokumentasi Proyek SADITA

Dokumentasi ini dibuat secara otomatis dan mencakup struktur Database, Model, Controller, View, dan Route dari sistem SADITA.

## 1. Skema Database

### Tabel: `artikels`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `artikelstips`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `carousels`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `wp_commentmeta`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_comments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_links`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_options`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_postmeta`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_posts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_term_relationships`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_term_taxonomy`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_termmeta`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_terms`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_usermeta`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `wp_users`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `case_stages`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `cases`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `contacts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `goals`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `articles`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | Yes |
| `title` | json | No |
| `slug` | varchar | No |
| `excerpt` | json | Yes |
| `content` | json | Yes |
| `featured_image` | varchar | Yes |
| `author` | varchar | No |
| `status` | enum | No |
| `published_at` | timestamp | Yes |
| `meta_title` | varchar | Yes |
| `meta_description` | text | Yes |
| `views_count` | int | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `contacts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `orders`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `order_number` | varchar | No |
| `success_token` | varchar | Yes |
| `warehouse_id` | bigint | Yes |
| `user_id` | bigint | Yes |
| `customer_name` | varchar | No |
| `customer_phone` | varchar | No |
| `customer_address` | text | Yes |
| `customer_postal_code` | varchar | Yes |
| `customer_city` | varchar | Yes |
| `province_id` | char | Yes |
| `regency_id` | char | Yes |
| `district_id` | char | Yes |
| `village_id` | char | Yes |
| `subtotal` | int | No |
| `shipping_cost` | int | No |
| `discount_amount` | int | No |
| `voucher_code` | varchar | Yes |
| `cashback_amount` | int | No |
| `shipping_method` | varchar | Yes |
| `shipping_courier` | varchar | Yes |
| `shipping_service` | varchar | Yes |
| `shipping_etd` | varchar | Yes |
| `total` | int | No |
| `status` | enum | No |
| `payment_method` | varchar | Yes |
| `payment_status` | varchar | No |
| `notes` | text | Yes |
| `admin_notes` | text | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `routes`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `services`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `ship_schedules`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `ships`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `testimonials`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `customers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `document_templates`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `invoices`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `company_profiles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `customers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `generated_letters`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `invoices`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `letter_templates`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `guru`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `siswa`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `article_topic`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `article_views`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `articles`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | Yes |
| `title` | json | No |
| `slug` | varchar | No |
| `excerpt` | json | Yes |
| `content` | json | Yes |
| `featured_image` | varchar | Yes |
| `author` | varchar | No |
| `status` | enum | No |
| `published_at` | timestamp | Yes |
| `meta_title` | varchar | Yes |
| `meta_description` | text | Yes |
| `views_count` | int | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `comment_notification_subscriptions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `comments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `reactions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `series`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `snippet_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `snippets`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `topicables`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `topics`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `cities`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `countries`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `departments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `employees`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `exports`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `failed_import_rows`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `imports`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `states`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `customers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `shipments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_profiles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `customers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `generated_letters`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `invoices`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `letter_templates`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `notifikasis`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `hasil_broadcasts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `login_activities`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `skor_empat`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `skor_satu_dua`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `skor_tiga`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `farms`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `meta_ades`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `non_meta_ades`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `ads`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `closing_data`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `iklan_ads`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `jenis_produk`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `organiks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `sumber_organiks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `closing_iklan_ads`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `closing_produks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `iklan_ads`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `iklan_ads_produk`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `nomor_h_p_s`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `detail_penjualans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pelanggans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `penjualans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `siswa`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `barang`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kategori`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `login`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `member`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `nota`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `penjualan`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `toko`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `activity_logs`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `clients`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `document_versions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `documents`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `account_subtypes`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `accounts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `addresses`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `adjustmentables`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `adjustments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `bank_accounts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `bills`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `budget_allocations`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `budget_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `budgets`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `clients`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `companies`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_defaults`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_invitations`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_profiles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_user`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `connected_accounts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `connected_bank_accounts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `contacts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `currencies`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `currency_lists`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `departments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `document_defaults`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `document_line_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `estimates`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `exports`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `failed_import_rows`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `imports`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `institutions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `invoices`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `journal_entries`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `localizations`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `offerings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `recurring_invoices`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `transactions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `vendors`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `recommendations`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `transactions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `tb_admin`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `tb_category`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `tb_image`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `banks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `data_banks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `hargas`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `laundry_settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications_settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `page_settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `password_resets`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `transaksis`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `detailpenjualans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pelanggans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `penjualans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `password_resets`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `carts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `orders`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `order_number` | varchar | No |
| `success_token` | varchar | Yes |
| `warehouse_id` | bigint | Yes |
| `user_id` | bigint | Yes |
| `customer_name` | varchar | No |
| `customer_phone` | varchar | No |
| `customer_address` | text | Yes |
| `customer_postal_code` | varchar | Yes |
| `customer_city` | varchar | Yes |
| `province_id` | char | Yes |
| `regency_id` | char | Yes |
| `district_id` | char | Yes |
| `village_id` | char | Yes |
| `subtotal` | int | No |
| `shipping_cost` | int | No |
| `discount_amount` | int | No |
| `voucher_code` | varchar | Yes |
| `cashback_amount` | int | No |
| `shipping_method` | varchar | Yes |
| `shipping_courier` | varchar | Yes |
| `shipping_service` | varchar | Yes |
| `shipping_etd` | varchar | Yes |
| `total` | int | No |
| `status` | enum | No |
| `payment_method` | varchar | Yes |
| `payment_status` | varchar | No |
| `notes` | text | Yes |
| `admin_notes` | text | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `password_resets`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `rents`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `suppliers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `transaction_details`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `transactions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `vehicles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `comments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `password_resets`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `posts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `companies`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_addresses`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_kbli`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `company_management`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `customers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `documents`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `order_notes`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `orders`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `order_number` | varchar | No |
| `success_token` | varchar | Yes |
| `warehouse_id` | bigint | Yes |
| `user_id` | bigint | Yes |
| `customer_name` | varchar | No |
| `customer_phone` | varchar | No |
| `customer_address` | text | Yes |
| `customer_postal_code` | varchar | Yes |
| `customer_city` | varchar | Yes |
| `province_id` | char | Yes |
| `regency_id` | char | Yes |
| `district_id` | char | Yes |
| `village_id` | char | Yes |
| `subtotal` | int | No |
| `shipping_cost` | int | No |
| `discount_amount` | int | No |
| `voucher_code` | varchar | Yes |
| `cashback_amount` | int | No |
| `shipping_method` | varchar | Yes |
| `shipping_courier` | varchar | Yes |
| `shipping_service` | varchar | Yes |
| `shipping_etd` | varchar | Yes |
| `total` | int | No |
| `status` | enum | No |
| `payment_method` | varchar | Yes |
| `payment_status` | varchar | No |
| `notes` | text | Yes |
| `admin_notes` | text | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `payments`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `service_packages`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `services`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `company_profiles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `customers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `invoice_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `invoices`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `logistics_documents`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `quotation_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `quotations`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `services`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `cabang`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `item_pesanans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kategoris`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pengaturans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pesanans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produk`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `stoks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `article_news`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `authors`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `banner_advertisements`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `article_news`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `authors`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `banner_advertisements`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `article_news`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `authors`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `banner_advertisements`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `article_news`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `authors`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `banner_advertisements`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `tahanan_sulsel`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `admin_kantor`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `articles`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | Yes |
| `title` | json | No |
| `slug` | varchar | No |
| `excerpt` | json | Yes |
| `content` | json | Yes |
| `featured_image` | varchar | Yes |
| `author` | varchar | No |
| `status` | enum | No |
| `published_at` | timestamp | Yes |
| `meta_title` | varchar | Yes |
| `meta_description` | text | Yes |
| `views_count` | int | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `category_products`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `detail_categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `dokter_hewan`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `language_lines`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `category_menus`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `checkout_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `checkouts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `menu_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `sales`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `ai_agent_settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `role` | varchar | Yes |
| `language` | varchar | No |
| `style` | varchar | Yes |
| `tone` | varchar | Yes |
| `addressing` | varchar | Yes |
| `instructions` | text | Yes |
| `response_format` | text | Yes |
| `scope_rules` | text | Yes |
| `contact_label` | varchar | Yes |
| `contact_value` | varchar | Yes |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `ai_knowledge_bases`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `title` | varchar | No |
| `category` | varchar | Yes |
| `content` | longtext | No |
| `priority` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `article_categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `slug` | varchar | No |
| `description` | text | Yes |
| `is_active` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `articles`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | Yes |
| `title` | json | No |
| `slug` | varchar | No |
| `excerpt` | json | Yes |
| `content` | json | Yes |
| `featured_image` | varchar | Yes |
| `author` | varchar | No |
| `status` | enum | No |
| `published_at` | timestamp | Yes |
| `meta_title` | varchar | Yes |
| `meta_description` | text | Yes |
| `views_count` | int | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `image` | varchar | Yes |
| `description` | json | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `consultation_logs`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `session_id` | varchar | No |
| `animal_type` | varchar | Yes |
| `messages` | json | No |
| `recommended_products` | json | Yes |
| `ip_address` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `customer_service_categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `customer_services`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `customer_service_category_id` | bigint | No |
| `name` | varchar | No |
| `photo` | varchar | Yes |
| `title` | varchar | No |
| `experience` | varchar | No |
| `city` | varchar | No |
| `whatsapp_number` | varchar | No |
| `working_hours` | varchar | No |
| `status` | enum | No |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `display_settings`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `home_hero_badge` | varchar | Yes |
| `home_hero_title` | varchar | Yes |
| `home_hero_description` | text | Yes |
| `home_primary_cta_label` | varchar | Yes |
| `home_primary_cta_url` | varchar | Yes |
| `home_secondary_cta_label` | varchar | Yes |
| `home_secondary_cta_url` | varchar | Yes |
| `home_warehouse_cta_label` | varchar | Yes |
| `home_warehouse_cta_url` | varchar | Yes |
| `home_about_title` | varchar | Yes |
| `home_about_description` | text | Yes |
| `home_stat_experience_value` | varchar | Yes |
| `home_stat_experience_label` | varchar | Yes |
| `home_stat_product_value` | varchar | Yes |
| `home_stat_product_label` | varchar | Yes |
| `home_stat_partner_value` | varchar | Yes |
| `home_stat_partner_label` | varchar | Yes |
| `home_stat_volume_value` | varchar | Yes |
| `home_stat_volume_label` | varchar | Yes |
| `store_home_title` | varchar | Yes |
| `store_home_description` | text | Yes |
| `store_home_info_title` | varchar | Yes |
| `store_home_info_description` | text | Yes |
| `store_katalog_title` | varchar | Yes |
| `store_katalog_subtitle` | varchar | Yes |
| `about_company_title` | varchar | Yes |
| `about_company_description` | text | Yes |
| `about_vision` | text | Yes |
| `about_mission_points` | text | Yes |
| `about_certification_points` | text | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `districts`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | char | No |
| `regency_id` | char | No |
| `name` | varchar | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `hero_banners`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `title` | varchar | Yes |
| `subtitle` | varchar | Yes |
| `image_url` | varchar | No |
| `link_url` | varchar | Yes |
| `button_text` | varchar | Yes |
| `sort_order` | int | No |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `order_items`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `order_id` | bigint | No |
| `product_id` | bigint | Yes |
| `product_name` | varchar | No |
| `product_sku` | varchar | Yes |
| `quantity` | int | No |
| `price` | int | No |
| `subtotal` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `orders`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `order_number` | varchar | No |
| `success_token` | varchar | Yes |
| `warehouse_id` | bigint | Yes |
| `user_id` | bigint | Yes |
| `customer_name` | varchar | No |
| `customer_phone` | varchar | No |
| `customer_address` | text | Yes |
| `customer_postal_code` | varchar | Yes |
| `customer_city` | varchar | Yes |
| `province_id` | char | Yes |
| `regency_id` | char | Yes |
| `district_id` | char | Yes |
| `village_id` | char | Yes |
| `subtotal` | int | No |
| `shipping_cost` | int | No |
| `discount_amount` | int | No |
| `voucher_code` | varchar | Yes |
| `cashback_amount` | int | No |
| `shipping_method` | varchar | Yes |
| `shipping_courier` | varchar | Yes |
| `shipping_service` | varchar | Yes |
| `shipping_etd` | varchar | Yes |
| `total` | int | No |
| `status` | enum | No |
| `payment_method` | varchar | Yes |
| `payment_status` | varchar | No |
| `notes` | text | Yes |
| `admin_notes` | text | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `page_visits`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `url` | varchar | No |
| `session_id` | varchar | Yes |
| `duration_seconds` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `product_stocks`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `product_id` | bigint | No |
| `warehouse_id` | bigint | No |
| `stock` | int | No |
| `reserved_stock` | int | No |
| `low_stock_threshold` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `provinces`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | char | No |
| `name` | varchar | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `regencies`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | char | No |
| `province_id` | char | No |
| `name` | varchar | No |
| `rajaongkir_city_id` | int | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `reviews`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `product_id` | bigint | No |
| `customer_name` | varchar | No |
| `location` | varchar | Yes |
| `rating` | tinyint | No |
| `comment` | text | No |
| `is_verified` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `shipping_zones`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `zone_name` | varchar | No |
| `cities` | json | No |
| `shipping_methods` | json | No |
| `free_shipping_threshold` | decimal | Yes |
| `flat_rate` | decimal | Yes |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `villages`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | char | No |
| `district_id` | char | No |
| `name` | varchar | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `warehouses`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `slug` | varchar | No |
| `code` | varchar | No |
| `address` | text | No |
| `city` | varchar | No |
| `province` | varchar | No |
| `postal_code` | varchar | Yes |
| `rajaongkir_city_id` | int | Yes |
| `phone` | varchar | Yes |
| `whatsapp` | varchar | Yes |
| `service_area` | text | Yes |
| `delivery_estimate` | varchar | Yes |
| `image` | text | Yes |
| `is_active` | tinyint | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `admin_kantor`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `articles`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | Yes |
| `title` | json | No |
| `slug` | varchar | No |
| `excerpt` | json | Yes |
| `content` | json | Yes |
| `featured_image` | varchar | Yes |
| `author` | varchar | No |
| `status` | enum | No |
| `published_at` | timestamp | Yes |
| `meta_title` | varchar | Yes |
| `meta_description` | text | Yes |
| `views_count` | int | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `category_products`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `detail_categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `dokter_hewan`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `testimonis`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `video_profiles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `cabang`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `item_pesanans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kategoris`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pengaturans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pesanans`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produk`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `stok_histories`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `stoks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `model_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `model_has_roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `order_product`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `orders`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `order_number` | varchar | No |
| `success_token` | varchar | Yes |
| `warehouse_id` | bigint | Yes |
| `user_id` | bigint | Yes |
| `customer_name` | varchar | No |
| `customer_phone` | varchar | No |
| `customer_address` | text | Yes |
| `customer_postal_code` | varchar | Yes |
| `customer_city` | varchar | Yes |
| `province_id` | char | Yes |
| `regency_id` | char | Yes |
| `district_id` | char | Yes |
| `village_id` | char | Yes |
| `subtotal` | int | No |
| `shipping_cost` | int | No |
| `discount_amount` | int | No |
| `voucher_code` | varchar | Yes |
| `cashback_amount` | int | No |
| `shipping_method` | varchar | Yes |
| `shipping_courier` | varchar | Yes |
| `shipping_service` | varchar | Yes |
| `shipping_etd` | varchar | Yes |
| `total` | int | No |
| `status` | enum | No |
| `payment_method` | varchar | Yes |
| `payment_status` | varchar | No |
| `notes` | text | Yes |
| `admin_notes` | text | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `product_categories`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `product_suppliers`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `products`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `category_id` | bigint | No |
| `name` | json | No |
| `slug` | varchar | No |
| `description` | json | Yes |
| `short_description` | json | Yes |
| `composition` | json | Yes |
| `indication` | json | Yes |
| `usage_instruction` | json | Yes |
| `dosage` | json | Yes |
| `withdrawal_time` | varchar | Yes |
| `registration_number` | varchar | Yes |
| `pack` | varchar | Yes |
| `animal_type` | varchar | Yes |
| `symptom_tags` | text | Yes |
| `price` | int | No |
| `weight` | int | No |
| `length` | int | No |
| `width` | int | No |
| `height` | int | No |
| `compare_at_price` | int | Yes |
| `image` | text | Yes |
| `rating` | decimal | No |
| `reviews_count` | int | No |
| `sold_count` | int | No |
| `status` | varchar | No |
| `is_featured` | tinyint | No |
| `sort_order` | int | No |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `role_has_permissions`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `roles`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `cabangs`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kategoris`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `stoks`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `admins`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `carousels`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_cities`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_districts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_provinces`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_villages`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kblis`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pendirian_cvs`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pendirian_pts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `tes`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `admins`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `carousels`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kblis`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pendirian_c_v_s`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `admins`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `carousels`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_cities`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_districts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_provinces`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `indonesia_villages`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `kblis`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `notifications`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pendirian_cvs`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pendirian_pts`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `tes`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `users`
| Kolom | Tipe Data | Nullable |
|---|---|---|
| `id` | bigint | No |
| `name` | varchar | No |
| `email` | varchar | No |
| `email_verified_at` | timestamp | Yes |
| `password` | varchar | No |
| `remember_token` | varchar | Yes |
| `created_at` | timestamp | Yes |
| `updated_at` | timestamp | Yes |

### Tabel: `admin`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `ongkir`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pelanggan`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pembelian`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `pembelian_produk`
| Kolom | Tipe Data | Nullable |
|---|---|---|

### Tabel: `produk`
| Kolom | Tipe Data | Nullable |
|---|---|---|

## 2. Models (`app/Models`)

- `AiAgentSetting.php`
- `AiKnowledgeBase.php`
- `Article.php`
- `ArticleCategory.php`
- `Category.php`
- `ConsultationLog.php`
- `CustomerService.php`
- `CustomerServiceCategory.php`
- `DisplaySetting.php`
- `District.php`
- `HeroBanner.php`
- `Order.php`
- `OrderItem.php`
- `PageVisit.php`
- `Product.php`
- `ProductStock.php`
- `Province.php`
- `Regency.php`
- `Review.php`
- `ShippingZone.php`
- `User.php`
- `Village.php`
- `Warehouse.php`

## 3. Controllers (`app/Http/Controllers`)

- `AiController.php`
- `Api/ShippingController.php`
- `Api/VoucherController.php`
- `CartController.php`
- `CheckoutController.php`
- `Controller.php`
- `HomeController.php`
- `OrderTrackingController.php`
- `PageController.php`
- `ProductController.php`
- `StoreController.php`

## 4. Views (`resources/views`)

Kumpulan file blade template:

- `components/bottom-nav.blade.php`
- `components/cookie-consent.blade.php`
- `components/header.blade.php`
- `components/layouts/app.blade.php`
- `components/layouts/toko.blade.php`
- `components/product-card.blade.php`
- `components/saditacare-fab.blade.php`
- `components/toko-bottom-nav.blade.php`
- `components/⚡customer-service-chat.blade.php`
- `errors/404.blade.php`
- `home.blade.php`
- `layouts/app.blade.php`
- `layouts/partials/header.blade.php`
- `layouts/partials/navbar.blade.php`
- `livewire/customer-service-chat.blade.php`
- `pages/artikel-detail.blade.php`
- `pages/artikel.blade.php`
- `pages/chat.blade.php`
- `pages/home.blade.php`
- `pages/produk-detail.blade.php`
- `pages/produk-kategori.blade.php`
- `pages/produk.blade.php`
- `pages/saditacare.blade.php`
- `pages/tentang.blade.php`
- `partials/cart-script.blade.php`
- `toko/cart.blade.php`
- `toko/checkout-success.blade.php`
- `toko/checkout.blade.php`
- `toko/detail-produk.blade.php`
- `toko/home.blade.php`
- `toko/katalog.blade.php`
- `toko/orders.blade.php`
- `toko/track-order.blade.php`
- `welcome.blade.php`

## 5. Routes

```text

 GET|HEAD / .. home › HomeController
 GET|HEAD admin .. filament.admin.pages.dashboard › Filament\Pages › Dashboard
 GET|HEAD admin/ai-agent-settings .. filament.admin.resources.ai-agent-settings.index › App\Filament\Resources\AiAgentSettings\Pages\ListAiAgentSettings
 GET|HEAD admin/ai-agent-settings/create .. filament.admin.resources.ai-agent-settings.create › App\Filament\Resources\AiAgentSettings\Pages\CreateAiAgentSetting
 GET|HEAD admin/ai-agent-settings/{record}/edit .. filament.admin.resources.ai-agent-settings.edit › App\Filament\Resources\AiAgentSettings\Pages\EditAiAgentSetting
 GET|HEAD admin/ai-knowledge-bases .. filament.admin.resources.ai-knowledge-bases.index › App\Filament\Resources\AiKnowledgeBases\Pages\ListAiKnowledgeBases
 GET|HEAD admin/ai-knowledge-bases/create .. filament.admin.resources.ai-knowledge-bases.create › App\Filament\Resources\AiKnowledgeBases\Pages\CreateAiKnowledgeBase
 GET|HEAD admin/ai-knowledge-bases/{record}/edit .. filament.admin.resources.ai-knowledge-bases.edit › App\Filament\Resources\AiKnowledgeBases\Pages\EditAiKnowledgeBase
 GET|HEAD admin/article-categories .. filament.admin.resources.article-categories.index › App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories
 GET|HEAD admin/article-categories/create .. filament.admin.resources.article-categories.create › App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory
 GET|HEAD admin/article-categories/{record} .. filament.admin.resources.article-categories.view › App\Filament\Resources\ArticleCategories\Pages\ViewArticleCategory
 GET|HEAD admin/article-categories/{record}/edit .. filament.admin.resources.article-categories.edit › App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory
 GET|HEAD admin/articles .. filament.admin.resources.articles.index › App\Filament\Resources\Articles\Pages\ListArticles
 GET|HEAD admin/articles/create .. filament.admin.resources.articles.create › App\Filament\Resources\Articles\Pages\CreateArticle
 GET|HEAD admin/articles/{record} .. filament.admin.resources.articles.view › App\Filament\Resources\Articles\Pages\ViewArticle
 GET|HEAD admin/articles/{record}/edit .. filament.admin.resources.articles.edit › App\Filament\Resources\Articles\Pages\EditArticle
 GET|HEAD admin/categories .. filament.admin.resources.categories.index › App\Filament\Resources\Categories\Pages\ListCategories
 GET|HEAD admin/categories/create .. filament.admin.resources.categories.create › App\Filament\Resources\Categories\Pages\CreateCategory
 GET|HEAD admin/categories/{record} .. filament.admin.resources.categories.view › App\Filament\Resources\Categories\Pages\ViewCategory
 GET|HEAD admin/categories/{record}/edit .. filament.admin.resources.categories.edit › App\Filament\Resources\Categories\Pages\EditCategory
 GET|HEAD admin/consultation-logs .. filament.admin.resources.consultation-logs.index › App\Filament\Resources\ConsultationLogs\Pages\ListConsultationLogs
 GET|HEAD admin/consultation-logs/create .. filament.admin.resources.consultation-logs.create › App\Filament\Resources\ConsultationLogs\Pages\CreateConsultationLog
 GET|HEAD admin/consultation-logs/{record} .. filament.admin.resources.consultation-logs.view › App\Filament\Resources\ConsultationLogs\Pages\ViewConsultationLog
 GET|HEAD admin/consultation-logs/{record}/edit .. filament.admin.resources.consultation-logs.edit › App\Filament\Resources\ConsultationLogs\Pages\EditConsultationLog
 GET|HEAD admin/display-settings .. filament.admin.resources.display-settings.index › App\Filament\Resources\DisplaySettings\Pages\ListDisplaySettings
 GET|HEAD admin/display-settings/{record}/edit .. filament.admin.resources.display-settings.edit › App\Filament\Resources\DisplaySettings\Pages\EditDisplaySetting
 GET|HEAD admin/hero-banners .. filament.admin.resources.hero-banners.index › App\Filament\Resources\HeroBanners\Pages\ListHeroBanners
 GET|HEAD admin/hero-banners/create .. filament.admin.resources.hero-banners.create › App\Filament\Resources\HeroBanners\Pages\CreateHeroBanner
 GET|HEAD admin/hero-banners/{record}/edit .. filament.admin.resources.hero-banners.edit › App\Filament\Resources\HeroBanners\Pages\EditHeroBanner
 GET|HEAD admin/login .. filament.admin.auth.login › Filament\Auth › Login
 POST admin/logout .. filament.admin.auth.logout › Filament\Auth › LogoutController
 GET|HEAD admin/orders .. filament.admin.resources.orders.index › App\Filament\Resources\Orders\Pages\ListOrders
 GET|HEAD admin/orders/create .. filament.admin.resources.orders.create › App\Filament\Resources\Orders\Pages\CreateOrder
 GET|HEAD admin/orders/{record} .. filament.admin.resources.orders.view › App\Filament\Resources\Orders\Pages\ViewOrder
 GET|HEAD admin/orders/{record}/edit .. filament.admin.resources.orders.edit › App\Filament\Resources\Orders\Pages\EditOrder
 GET|HEAD admin/product-stocks .. filament.admin.resources.product-stocks.index › App\Filament\Resources\ProductStocks\Pages\ListProductStocks
 GET|HEAD admin/product-stocks/create .. filament.admin.resources.product-stocks.create › App\Filament\Resources\ProductStocks\Pages\CreateProductStock
 GET|HEAD admin/product-stocks/{record} .. filament.admin.resources.product-stocks.view › App\Filament\Resources\ProductStocks\Pages\ViewProductStock
 GET|HEAD admin/product-stocks/{record}/edit .. filament.admin.resources.product-stocks.edit › App\Filament\Resources\ProductStocks\Pages\EditProductStock
 GET|HEAD admin/products .. filament.admin.resources.products.index › App\Filament\Resources\Products\Pages\ListProducts
 GET|HEAD admin/products/create .. filament.admin.resources.products.create › App\Filament\Resources\Products\Pages\CreateProduct
 GET|HEAD admin/products/{record} .. filament.admin.resources.products.view › App\Filament\Resources\Products\Pages\ViewProduct
 GET|HEAD admin/products/{record}/edit .. filament.admin.resources.products.edit › App\Filament\Resources\Products\Pages\EditProduct
 GET|HEAD admin/warehouses .. filament.admin.resources.warehouses.index › App\Filament\Resources\Warehouses\Pages\ListWarehouses
 GET|HEAD admin/warehouses/create .. filament.admin.resources.warehouses.create › App\Filament\Resources\Warehouses\Pages\CreateWarehouse
 GET|HEAD admin/warehouses/{record} .. filament.admin.resources.warehouses.view › App\Filament\Resources\Warehouses\Pages\ViewWarehouse
 GET|HEAD admin/warehouses/{record}/edit .. filament.admin.resources.warehouses.edit › App\Filament\Resources\Warehouses\Pages\EditWarehouse
 POST ai/chat .. ai.chat › AiController@chat
 GET|HEAD api/shipping/cities .. Api\ShippingController@getCities
 GET|HEAD api/shipping/districts .. Api\ShippingController@getDistricts
 POST api/shipping/methods .. Api\ShippingController@getShippingMethods
 GET|HEAD api/shipping/provinces .. Api\ShippingController@getProvinces
 GET|HEAD api/shipping/subdistricts .. Api\ShippingController@getSubdistricts
 GET|HEAD api/shipping/test .. routes/api.php:22
 GET|HEAD api/shipping/villages .. Api\ShippingController@getVillages
 POST api/vouchers/check .. Api\VoucherController@check
 GET|HEAD artikel .. artikel › PageController@artikel
 GET|HEAD artikel/{article:slug} .. artikel.show › PageController@artikelShow
 GET|HEAD cart .. cart.index › CartController@index
 POST cart/add-by-slug/{product:slug} .. cart.add.slug › CartController@add
 POST cart/add/{product} .. cart.add › CartController@add
 GET|HEAD cart/count .. cart.count › CartController@count
 DELETE cart/remove/{productId} .. cart.remove › CartController@remove
 PATCH cart/update/{productId} .. cart.update › CartController@update
 GET|HEAD chat .. chat › PageController@chat
 GET|HEAD checkout .. checkout › CheckoutController@index
 POST checkout .. checkout.store › CheckoutController@store
 GET|HEAD checkout/success/{orderNumber}/{token} .. checkout.success › CheckoutController@success
 GET|HEAD filament/exports/{export}/download .. filament.exports.download › Filament\Actions › DownloadExport
 GET|HEAD filament/imports/{import}/failed-rows/download .. filament.imports.failed-rows.download › Filament\Actions › DownloadImportFailureCsv
 GET|HEAD lang/{locale} .. locale.switch › routes/web.php:52
 GET|HEAD livewire-0c96f51d/css/{component}.css .. vendor/livewire/livewire/src/Features/SupportCssModules/SupportCssModules.php:15
 GET|HEAD livewire-0c96f51d/css/{component}.global.css .. vendor/livewire/livewire/src/Features/SupportCssModules/SupportCssModules.php:48
 GET|HEAD livewire-0c96f51d/js/{component}.js .. vendor/livewire/livewire/src/Features/SupportJsModules/SupportJsModules.php:16
 GET|HEAD livewire-0c96f51d/livewire.csp.min.js.map .. Livewire\Mechanisms › FrontendAssets@cspMaps
 GET|HEAD livewire-0c96f51d/livewire.js .. Livewire\Mechanisms › FrontendAssets@returnJavaScriptAsFile
 GET|HEAD livewire-0c96f51d/livewire.min.js.map .. Livewire\Mechanisms › FrontendAssets@maps
 GET|HEAD livewire-0c96f51d/preview-file/{filename} .. livewire.preview-file › Livewire\Features › FilePreviewController@handle
 POST livewire-0c96f51d/update .. default-livewire.update › Livewire\Mechanisms › HandleRequests@handleUpdate
 POST livewire-0c96f51d/upload-file .. livewire.upload-file › Livewire\Features › FileUploadController@handle
 GET|HEAD produk .. produk › PageController@produk
 GET|HEAD produk/{category:slug} .. produk.category › PageController@produkKategori
 GET|HEAD produk/{category:slug}/{product:slug} .. produk.detail › PageController@produkDetail
 GET|HEAD saditacare .. saditacare › PageController@saditacare
 GET|HEAD storage/{path} .. storage.local › vendor/laravel/framework/src/Illuminate/Filesystem/FilesystemServiceProvider.php:111
 PUT storage/{path} .. storage.local.upload › vendor/laravel/framework/src/Illuminate/Filesystem/FilesystemServiceProvider.php:119
 GET|HEAD tentang .. tentang › PageController@tentang
 GET|HEAD toko .. toko.home › StoreController@index
 GET|HEAD toko/katalog .. toko.katalog › ProductController@index
 GET|HEAD toko/lacak-pesanan .. toko.track-order › OrderTrackingController@index
 POST toko/lacak-pesanan .. OrderTrackingController@index
 GET|HEAD toko/order .. toko.orders › CheckoutController@orders
 GET|HEAD toko/produk/{product:slug} .. toko.produk.show › ProductController@show
 POST toko/select-warehouse/{warehouse:slug} .. toko.select-warehouse › StoreController@select
 GET|HEAD up .. vendor/laravel/framework/src/Illuminate/Foundation/Configuration/ApplicationBuilder.php:224

 Showing [95] routes


```

## 6. Referensi Dokumentasi Tambahan

### File: `CEK_ONGKIR_DAN_METODE_PENGIRIMAN.md`
```markdown
# Cek Ongkir dan Metode Pengiriman

Dokumen ini menjadi acuan agar fitur cek ongkir dan metode pengiriman tetap konsisten.

## Ringkasan

Checkout menggunakan API.co.id untuk tarif ekspedisi nasional.

- Provider ongkir: API.co.id
- Endpoint tarif: `GET https://use.api.co.id/expedition/shipping-cost`
- Header auth: `x-api-co-id`
- Basis perhitungan: kode kelurahan/desa 10 digit
- Berat dikirim ke API.co.id dalam kilogram, dibulatkan ke atas
- Biteship sudah tidak dipakai

## File Utama

- `app/Services/ApiCoIdService.php`
- `app/Http/Controllers/Api/ShippingController.php`
- `resources/views/toko/checkout.blade.php`
- `config/api_co_id.php`
- `.env`

## Konfigurasi .env

Isi API key dan kode asal gudang di `.env`:

```env
API_CO_ID_API_KEY=isi_api_key_dari_dashboard_api_co_id
API_CO_ID_BASE_URL=https://use.api.co.id

API_CO_ID_ORIGIN_VILLAGE_CODE=7371141001
API_CO_ID_ORIGIN_VILLAGE_CODE_BOGOR_TIMUR=3271021002
API_CO_ID_ORIGIN_VILLAGE_CODE_MAKASSAR=7371141001

API_CO_ID_ITEM_WEIGHT_GRAM...
*(konten terpotong)*

```

### File: `CHECKOUT-V3-MASTERPLAN.md`
```markdown

# SADITA V3 - Checkout Master Plan

Version: 3.0
Status: Planning
Author: Alfa & ChatGPT
Last Update: 11 Juli 2026

---

# Tujuan

Membangun halaman Checkout yang cepat, sederhana, modern, dan scalable.

Checkout harus memiliki pengalaman seperti:

- Tokopedia
- Shopee
- TikTok Shop

tetapi tetap mempertahankan kebutuhan bisnis SADITA.

---

# Filosofi

Checkout bukan tempat customer berpikir.

Checkout adalah tempat customer menyelesaikan transaksi secepat mungkin.

Setiap elemen yang tidak membantu transaksi harus dihilangkan.

---

# Prinsip UI

Prioritas:

1. Cepat
2. Jelas
3. Sedikit Klik
4. Mobile First
5. Mudah dipahami orang awam

Target waktu checkout:

< 60 detik

---

# Struktur Halaman

Urutan section WAJIB seperti berikut.

────────────────────

1.
Alamat Pengiriman

────────────────────

2.
Metode Pengiriman

──────�...
*(konten terpotong)*

```

### File: `finishing.md`
```markdown
# ROLE

Anda adalah Senior Full Stack Developer, UI/UX Designer, dan Software Architect dengan pengalaman membangun aplikasi Laravel + React + Tailwind + MySQL.

Tugas Anda adalah bertindak sebagai Lead Developer yang akan menyelesaikan proyek ini hingga siap production.

---

# OBJECTIVE

Website ini harus siap di-deploy ke cPanel malam ini.

Jangan memberikan teori panjang.

Fokus pada eksekusi.

Selalu kerjakan berdasarkan prioritas tertinggi terlebih dahulu.

---

# RULE

Jangan mengubah fitur yang sudah berjalan kecuali memang perlu diperbaiki.

Selalu gunakan struktur project yang sudah ada.

Jangan membuat fitur baru yang tidak diperlukan.

Jika menemukan bug, langsung perbaiki.

Jika menemukan UI kurang baik, langsung redesign.

Pastikan seluruh perubahan tetap sinkron dengan database.

Gunakan data dummy seperlunya agar seluruh halaman terlihat realistis.

---

# PRIORITAS PEKERJAAN

Kerjakan secara berurutan.

## Tahap 1 — Audit

...
*(konten terpotong)*

```

### File: `panduan_deploy_cpanel.md`
```markdown
# Panduan Deploy Laravel (SADITA) ke cPanel via GitHub

Secara keseluruhan, sistem SADITA saat ini sudah sangat matang untuk diluncurkan (MVP - *Minimum Viable Product*). Fitur-fitur fundamental mulai dari katalog, keranjang, kalkulasi ongkir volumetrik, integrasi multi-gudang, hingga proteksi keamanan stok (transaksi database) semuanya **sudah rampung dan beroperasi sempurna**.

Berikut adalah panduan **Step-by-Step** mengorbitkan proyek SADITA ke cPanel menggunakan GitHub, **termasuk setting Auto-Deploy agar saat Anda push ke GitHub, server langsung terupdate otomatis!**

---

## Tahap 1: Persiapan di cPanel
1. **Pastikan Versi PHP:** Buka menu **Select PHP Version** di cPanel, pastikan Anda menggunakan minimal **PHP 8.2** atau **8.3** (sesuai kebutuhan Laravel 11).
2. **Aktifkan Ekstensi PHP:** Pastikan ekstensi `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `mbstring`, `pdo`, `tokenizer`, `xml`, dan `zip` dalam keadaan tercentang.
3. **Buat Database:** Buka menu **MySQL Databases**....
*(konten terpotong)*

```

### File: `perbaikan-tentang.md`
```markdown
Perbaiki section "Manfaat Kami" pada halaman Tentang.

Jangan menggunakan tampilan lama berupa background merah dengan bullet list.

Ubah menjadi section modern yang konsisten dengan UI SADITA.

## Judul

Mengapa Memilih SADITA

Tambahkan deskripsi singkat:

"SADITA berkomitmen menghadirkan produk kesehatan hewan berkualitas melalui standar produksi yang ketat, tenaga profesional, dan sistem manajemen mutu yang terpercaya."

## Layout

Gunakan card modern.

Grid:

- Mobile: 1 kolom
- Tablet: 2 kolom
- Desktop: 3 kolom

## Card

Setiap card memiliki:

- Icon (Lucide Heroicon)
- Judul
- Deskripsi singkat
- Border tipis
- Rounded 20px
- Shadow ringan
- Padding konsisten
- Hover animation ringan

## Isi Card

1. SDM Berpengalaman
Didukung tenaga profesional yang ahli di bidang kesehatan hewan dan peternakan.

2. Standar Produksi Nasional
Produk diproduksi mengikuti standar CPOHB dan SOP perusahaan.

3. Produksi Berkualitas
Seluruh proses produk...
*(konten terpotong)*

```


## 7. Alur Website (Website Flow)

Berikut adalah ringkasan alur penggunaan sistem SADITA secara keseluruhan:

### A. Sisi Pengguna (Frontend / Public)
1. **Eksplorasi Profil & Produk:**
   - User mengunjungi **Beranda** untuk melihat informasi singkat perusahaan.
   - User dapat membuka **Tentang Kami** untuk melihat profil lengkap, fasilitas, sertifikat, dan legalitas.
   - User mengeksplorasi katalog di halaman **Produk** publik.
2. **Konsultasi Kesehatan Hewan:**
   - User yang memiliki pertanyaan medis hewan dapat masuk ke **SaditaCare**.
   - User berinteraksi dengan AI cerdas untuk mendapatkan rekomendasi.
   - *Error Handling:* Jika sistem AI sedang sibuk/habis kuota, sistem otomatis menampilkan peringatan dan mengarahkan user untuk menghubungi via WhatsApp.
3. **Pembelanjaan (Toko SADITA):**
   - User masuk ke **Toko** dan memasukkan produk ke dalam **Keranjang**.
   - Di keranjang, user dapat menambah/mengurangi jumlah produk atau menghapusnya (dengan konfirmasi visual *SweetAlert*).
4. **Checkout & Pengiriman:**
   - User menekan tombol checkout dan diarahkan ke halaman **Checkout**.
   - User mengisi alamat lengkap (Provinsi -> Kota -> Kecamatan -> Kelurahan) yang bersifat berjenjang (*cascading*).
   - Sistem memanggil API RajaOngkir untuk menghitung opsi ongkos kirim.
   - User memilih metode pembayaran (Transfer Bank, QRIS, atau COD). Setiap metode menampilkan instruksi khusus secara dinamis.
   - User melakukan **Buat Pesanan** (dengan konfirmasi dialog).
5. **Pasca Pembelian:**
   - User diarahkan ke halaman **Sukses**, berisi panduan pembayaran dan tombol "Konfirmasi ke Admin (WhatsApp)".
   - User dapat melihat status pemrosesan paket mereka di halaman **Lacak Pesanan** dengan memasukkan ID Pesanan dan Nomor HP.
   - Riwayat belanja yang tersimpan di *browser* dapat diakses lewat menu **Order Saya**.

### B. Sisi Admin (Backend / Filament)
1. **Manajemen Utama (Dashboard):**
   - Admin login melalui `/admin`.
   - Melihat metrik utama seperti total pesanan, produk aktif, dan log chat konsultasi.
2. **Pemrosesan Pesanan:**
   - Saat user menyelesaikan *checkout*, order baru akan muncul di menu **Orders**.
   - Admin mengecek mutasi bank/pembayaran.
   - Admin memperbarui status pesanan: `Pending` -> `Processing` -> `Shipped` (menginput resi) -> `Delivered`.
   - Perubahan status ini akan tercermin seketika saat user memeriksa halaman **Lacak Pesanan**.
3. **Manajemen Konten & Produk:**
   - Admin menambah atau memperbarui data Produk (Stok, Harga, Kategori, Foto).
   - Perubahan akan langsung tampil *real-time* di halaman Toko publik.
   - Admin dapat melihat *Log Konsultasi AI* untuk memahami apa saja kendala penyakit hewan yang sering ditanyakan oleh pelanggan.