<?php
$file = 'resources/views/toko/checkout.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    '@csrf',
    '@csrf
                <input type="hidden" name="selected_items_str" value="{{ $selectedItemsStr ?? \'\' }}">',
    $content
);

file_put_contents($file, $content);
?>
