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

        // Replace use Filament\Schemas\Schema;
        if ($isResource) {
            $content = str_replace('use Filament\Schemas\Schema;', "use Filament\Forms\Form;\nuse Filament\Infolists\Infolist;", $content);
            $content = str_replace('public static function form(Schema $schema): Schema', 'public static function form(Form $form): Form', $content);
            $content = str_replace('public static function infolist(Schema $schema): Schema', 'public static function infolist(Infolist $infolist): Infolist', $content);
            $content = preg_replace('/return ([A-Za-z0-9_]+)Form::configure\(\$schema\);/', 'return $1Form::configure($form);', $content);
            $content = preg_replace('/return ([A-Za-z0-9_]+)Infolist::configure\(\$schema\);/', 'return $1Infolist::configure($infolist);', $content);
        } else if ($isForm) {
            $content = str_replace('use Filament\Schemas\Schema;', "use Filament\Forms\Form;", $content);
            $content = str_replace('public static function configure(Schema $schema): Schema', 'public static function configure(Form $form): Form', $content);
            $content = preg_replace('/return \$schema/', 'return $form', $content);
        } else if ($isInfolist) {
            $content = str_replace('use Filament\Schemas\Schema;', "use Filament\Infolists\Infolist;", $content);
            $content = str_replace('public static function configure(Schema $schema): Schema', 'public static function configure(Infolist $infolist): Infolist', $content);
            $content = preg_replace('/return \$schema/', 'return $infolist', $content);
        }

        // Fix other Filament\Schemas
        $content = str_replace('use Filament\Schemas\Components\Utilities\Set;', 'use Filament\Forms\Set;', $content);
        
        // In Infolists
        if ($isInfolist) {
            $content = str_replace('use Filament\Schemas\Components\KeyValue;', 'use Filament\Infolists\Components\KeyValueEntry;', $content);
            $content = str_replace('KeyValue::make', 'KeyValueEntry::make', $content);
            
            $content = str_replace('use Filament\Schemas\Components\TextEntry;', 'use Filament\Infolists\Components\TextEntry;', $content);
        }

        if ($orig !== $content) {
            file_put_contents($file->getPathname(), $content);
            echo "Fixed: " . $file->getPathname() . "\n";
        }
    }
}
