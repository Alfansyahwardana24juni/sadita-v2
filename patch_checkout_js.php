<?php
$file = 'resources/views/toko/checkout.blade.php';
$content = file_get_contents($file);

$saveFormJS = <<<JS
            function saveFormData() {
                const data = {
                    customer_name: document.querySelector('[name="customer_name"]').value,
                    customer_phone: document.querySelector('[name="customer_phone"]').value,
                    customer_province: provinceSelect.value,
                    customer_regency_id: citySelect.value,
                    district_id: districtSelect.value,
                    village_id: villageSelect.value,
                    customer_address: document.querySelector('[name="customer_address"]').value,
                    postal_code: document.querySelector('[name="postal_code"]').value,
                    notes: document.querySelector('[name="notes"]').value,
                };
                localStorage.setItem('sadita_checkout_info', JSON.stringify(data));
            }

            async function restoreFormData() {
                const saved = localStorage.getItem('sadita_checkout_info');
                if (!saved) return;
                try {
                    const data = JSON.parse(saved);
                    if (data.customer_name) document.querySelector('[name="customer_name"]').value = data.customer_name;
                    if (data.customer_phone) document.querySelector('[name="customer_phone"]').value = data.customer_phone;
                    if (data.customer_address) document.querySelector('[name="customer_address"]').value = data.customer_address;
                    if (data.postal_code) document.querySelector('[name="postal_code"]').value = data.postal_code;
                    if (data.notes) document.querySelector('[name="notes"]').value = data.notes;

                    if (data.customer_province) {
                        provinceSelect.value = data.customer_province;
                        await loadCities(data.customer_province);
                        if (data.customer_regency_id) {
                            citySelect.value = data.customer_regency_id;
                            document.getElementById('customer-city-input').value = citySelect.options[citySelect.selectedIndex]?.text || '';
                            await loadDistricts(data.customer_regency_id);
                            if (data.district_id) {
                                districtSelect.value = data.district_id;
                                await loadVillages(data.district_id);
                                if (data.village_id) {
                                    villageSelect.value = data.village_id;
                                    // Panggil event change desa untuk load ongkir
                                    villageSelect.dispatchEvent(new Event('change'));
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.error('Gagal mengembalikan data form:', e);
                }
            }
JS;

$content = str_replace(
    'loadProvinces();',
    'loadProvinces().then(restoreFormData);',
    $content
);

$content = str_replace(
    'if (!result.isConfirmed) return;
                }',
    'if (!result.isConfirmed) return;
                }
                saveFormData();',
    $content
);

$content = str_replace(
    '// ── Boot ──────────────────────────────────────────────────────────',
    $saveFormJS . '

            // ── Boot ──────────────────────────────────────────────────────────',
    $content
);

file_put_contents($file, $content);
?>
