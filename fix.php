<?php
$files = glob('app/Filament/Resources/*/Schemas/*Infolist.php');
foreach ($files as $f) {
    $c = file_get_contents($f);
    $c = str_replace('Filament\Schemas\Components\TextEntry', 'Filament\Schemas\Components\Text', $c);
    $c = str_replace('TextEntry::make', 'Text::make', $c);
    file_put_contents($f, $c);
    echo "Fixed $f\n";
}
