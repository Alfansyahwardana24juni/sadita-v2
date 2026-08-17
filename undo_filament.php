<?php
$dir = new RecursiveDirectoryIterator('D:\sadita-v2\app\Filament');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $orig = $content;
        
        $isResource = str_contains($file->getFilename(), 'Resource.php');
        $isForm = str_contains($file->getFilename(), 'Form.php');
        $isInfolist = str_contains($file->getFilename(), 'Infolist.php');

        // Reverse Resource
        if ($isResource) {
            $content = str_replace("use Filament\Forms\Form;\nuse Filament\Infolists\Infolist;", 'use Filament\Schemas\Schema;', $content);
            $content = str_replace('public static function form(Form $form): Form', 'public static function form(Schema $schema): Schema', $content);
            $content = str_replace('public static function infolist(Infolist $infolist): Infolist', 'public static function infolist(Schema $schema): Schema', $content);
            $content = preg_replace('/return ([A-Za-z0-9_]+)Form::configure\(\$form\);/', 'return $1Form::configure($schema);', $content);
            $content = preg_replace('/return ([A-Za-z0-9_]+)Infolist::configure\(\$infolist\);/', 'return $1Infolist::configure($schema);', $content);
        } else if ($isForm) {
            $content = str_replace("use Filament\Forms\Form;", 'use Filament\Schemas\Schema;', $content);
            $content = str_replace('public static function configure(Form $form): Form', 'public static function configure(Schema $schema): Schema', $content);
            $content = preg_replace('/return \$form/', 'return $schema', $content);
        } else if ($isInfolist) {
            $content = str_replace("use Filament\Infolists\Infolist;", 'use Filament\Schemas\Schema;', $content);
            $content = str_replace('public static function configure(Infolist $infolist): Infolist', 'public static function configure(Schema $schema): Schema', $content);
            $content = preg_replace('/return \$infolist/', 'return $schema', $content);
        }

        // Reverse other Filament\Schemas
        $content = str_replace('use Filament\Forms\Set;', 'use Filament\Schemas\Components\Utilities\Set;', $content);
        
        // In Infolists
        if ($isInfolist) {
            $content = str_replace('use Filament\Infolists\Components\KeyValueEntry;', 'use Filament\Schemas\Components\KeyValue;', $content);
            $content = str_replace('KeyValueEntry::make', 'KeyValue::make', $content);
            
            $content = str_replace('use Filament\Infolists\Components\TextEntry;', 'use Filament\Schemas\Components\TextEntry;', $content);
        }

        if ($orig !== $content) {
            file_put_contents($file->getPathname(), $content);
            echo "Reverted: " . $file->getPathname() . "\n";
        }
    }
}
