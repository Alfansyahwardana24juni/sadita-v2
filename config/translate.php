<?php

/*
|--------------------------------------------------------------------------
| Google Translate language switcher
|--------------------------------------------------------------------------
|
| "source" adalah bahasa asli halaman (tidak diterjemahkan). Kontrol bahasa
| memakai widget Google Website Translator (cookie "googtrans") tanpa banner.
| "popular" tampil di bagian atas dropdown; "languages" adalah daftar lengkap.
|
*/

return [

    'source' => 'id',

    'popular' => ['id', 'en', 'ar', 'zh-CN', 'ja', 'ko', 'ms', 'nl', 'de', 'fr', 'es', 'hi'],

    'languages' => [
        ['code' => 'id', 'name' => 'Bahasa Indonesia', 'english' => 'Indonesian', 'flag' => '🇮🇩'],
        ['code' => 'en', 'name' => 'English', 'english' => 'English', 'flag' => '🇬🇧'],
        ['code' => 'ar', 'name' => 'العربية', 'english' => 'Arabic', 'flag' => '🇸🇦'],
        ['code' => 'zh-CN', 'name' => '简体中文', 'english' => 'Chinese (Simplified)', 'flag' => '🇨🇳'],
        ['code' => 'zh-TW', 'name' => '繁體中文', 'english' => 'Chinese (Traditional)', 'flag' => '🇹🇼'],
        ['code' => 'ja', 'name' => '日本語', 'english' => 'Japanese', 'flag' => '🇯🇵'],
        ['code' => 'ko', 'name' => '한국어', 'english' => 'Korean', 'flag' => '🇰🇷'],
        ['code' => 'ms', 'name' => 'Bahasa Melayu', 'english' => 'Malay', 'flag' => '🇲🇾'],
        ['code' => 'nl', 'name' => 'Nederlands', 'english' => 'Dutch', 'flag' => '🇳🇱'],
        ['code' => 'de', 'name' => 'Deutsch', 'english' => 'German', 'flag' => '🇩🇪'],
        ['code' => 'fr', 'name' => 'Français', 'english' => 'French', 'flag' => '🇫🇷'],
        ['code' => 'es', 'name' => 'Español', 'english' => 'Spanish', 'flag' => '🇪🇸'],
        ['code' => 'hi', 'name' => 'हिन्दी', 'english' => 'Hindi', 'flag' => '🇮🇳'],
        ['code' => 'pt', 'name' => 'Português', 'english' => 'Portuguese', 'flag' => '🇵🇹'],
        ['code' => 'it', 'name' => 'Italiano', 'english' => 'Italian', 'flag' => '🇮🇹'],
        ['code' => 'ru', 'name' => 'Русский', 'english' => 'Russian', 'flag' => '🇷🇺'],
        ['code' => 'th', 'name' => 'ไทย', 'english' => 'Thai', 'flag' => '🇹🇭'],
        ['code' => 'vi', 'name' => 'Tiếng Việt', 'english' => 'Vietnamese', 'flag' => '🇻🇳'],
        ['code' => 'tr', 'name' => 'Türkçe', 'english' => 'Turkish', 'flag' => '🇹🇷'],
        ['code' => 'fil', 'name' => 'Filipino', 'english' => 'Filipino', 'flag' => '🇵🇭'],
        ['code' => 'bn', 'name' => 'বাংলা', 'english' => 'Bengali', 'flag' => '🇧🇩'],
        ['code' => 'ta', 'name' => 'தமிழ்', 'english' => 'Tamil', 'flag' => '🇮🇳'],
        ['code' => 'te', 'name' => 'తెలుగు', 'english' => 'Telugu', 'flag' => '🇮🇳'],
        ['code' => 'ur', 'name' => 'اردو', 'english' => 'Urdu', 'flag' => '🇵🇰'],
        ['code' => 'fa', 'name' => 'فارسی', 'english' => 'Persian', 'flag' => '🇮🇷'],
        ['code' => 'pl', 'name' => 'Polski', 'english' => 'Polish', 'flag' => '🇵🇱'],
        ['code' => 'uk', 'name' => 'Українська', 'english' => 'Ukrainian', 'flag' => '🇺🇦'],
        ['code' => 'ro', 'name' => 'Română', 'english' => 'Romanian', 'flag' => '🇷🇴'],
        ['code' => 'el', 'name' => 'Ελληνικά', 'english' => 'Greek', 'flag' => '🇬🇷'],
        ['code' => 'cs', 'name' => 'Čeština', 'english' => 'Czech', 'flag' => '🇨🇿'],
        ['code' => 'sv', 'name' => 'Svenska', 'english' => 'Swedish', 'flag' => '🇸🇪'],
        ['code' => 'da', 'name' => 'Dansk', 'english' => 'Danish', 'flag' => '🇩🇰'],
        ['code' => 'fi', 'name' => 'Suomi', 'english' => 'Finnish', 'flag' => '🇫🇮'],
        ['code' => 'no', 'name' => 'Norsk', 'english' => 'Norwegian', 'flag' => '🇳🇴'],
        ['code' => 'hu', 'name' => 'Magyar', 'english' => 'Hungarian', 'flag' => '🇭🇺'],
        ['code' => 'he', 'name' => 'עברית', 'english' => 'Hebrew', 'flag' => '🇮🇱'],
        ['code' => 'sw', 'name' => 'Kiswahili', 'english' => 'Swahili', 'flag' => '🇰🇪'],
        ['code' => 'jw', 'name' => 'Basa Jawa', 'english' => 'Javanese', 'flag' => '🇮🇩'],
        ['code' => 'su', 'name' => 'Basa Sunda', 'english' => 'Sundanese', 'flag' => '🇮🇩'],
        ['code' => 'my', 'name' => 'မြန်မာ', 'english' => 'Myanmar (Burmese)', 'flag' => '🇲🇲'],
        ['code' => 'km', 'name' => 'ខ្មែរ', 'english' => 'Khmer', 'flag' => '🇰🇭'],
        ['code' => 'lo', 'name' => 'ລາວ', 'english' => 'Lao', 'flag' => '🇱🇦'],
        ['code' => 'si', 'name' => 'සිංහල', 'english' => 'Sinhala', 'flag' => '🇱🇰'],
        ['code' => 'ne', 'name' => 'नेपाली', 'english' => 'Nepali', 'flag' => '🇳🇵'],
        ['code' => 'pa', 'name' => 'ਪੰਜਾਬੀ', 'english' => 'Punjabi', 'flag' => '🇮🇳'],
        ['code' => 'gu', 'name' => 'ગુજરાતી', 'english' => 'Gujarati', 'flag' => '🇮🇳'],
        ['code' => 'kn', 'name' => 'ಕನ್ನಡ', 'english' => 'Kannada', 'flag' => '🇮🇳'],
        ['code' => 'ml', 'name' => 'മലയാളം', 'english' => 'Malayalam', 'flag' => '🇮🇳'],
        ['code' => 'mr', 'name' => 'मराठी', 'english' => 'Marathi', 'flag' => '🇮🇳'],
        ['code' => 'sk', 'name' => 'Slovenčina', 'english' => 'Slovak', 'flag' => '🇸🇰'],
        ['code' => 'sl', 'name' => 'Slovenščina', 'english' => 'Slovenian', 'flag' => '🇸🇮'],
        ['code' => 'hr', 'name' => 'Hrvatski', 'english' => 'Croatian', 'flag' => '🇭🇷'],
        ['code' => 'sr', 'name' => 'Српски', 'english' => 'Serbian', 'flag' => '🇷🇸'],
        ['code' => 'bg', 'name' => 'Български', 'english' => 'Bulgarian', 'flag' => '🇧🇬'],
        ['code' => 'lt', 'name' => 'Lietuvių', 'english' => 'Lithuanian', 'flag' => '🇱🇹'],
        ['code' => 'lv', 'name' => 'Latviešu', 'english' => 'Latvian', 'flag' => '🇱🇻'],
        ['code' => 'et', 'name' => 'Eesti', 'english' => 'Estonian', 'flag' => '🇪🇪'],
        ['code' => 'is', 'name' => 'Íslenska', 'english' => 'Icelandic', 'flag' => '🇮🇸'],
        ['code' => 'ga', 'name' => 'Gaeilge', 'english' => 'Irish', 'flag' => '🇮🇪'],
        ['code' => 'ca', 'name' => 'Català', 'english' => 'Catalan', 'flag' => '🇪🇸'],
        ['code' => 'af', 'name' => 'Afrikaans', 'english' => 'Afrikaans', 'flag' => '🇿🇦'],
        ['code' => 'sq', 'name' => 'Shqip', 'english' => 'Albanian', 'flag' => '🇦🇱'],
        ['code' => 'az', 'name' => 'Azərbaycan', 'english' => 'Azerbaijani', 'flag' => '🇦🇿'],
        ['code' => 'kk', 'name' => 'Қазақ', 'english' => 'Kazakh', 'flag' => '🇰🇿'],
        ['code' => 'uz', 'name' => 'Oʻzbek', 'english' => 'Uzbek', 'flag' => '🇺🇿'],
        ['code' => 'hy', 'name' => 'Հայերեն', 'english' => 'Armenian', 'flag' => '🇦🇲'],
        ['code' => 'ka', 'name' => 'ქართული', 'english' => 'Georgian', 'flag' => '🇬🇪'],
        ['code' => 'mn', 'name' => 'Монгол', 'english' => 'Mongolian', 'flag' => '🇲🇳'],
        ['code' => 'am', 'name' => 'አማርኛ', 'english' => 'Amharic', 'flag' => '🇪🇹'],
        ['code' => 'ha', 'name' => 'Hausa', 'english' => 'Hausa', 'flag' => '🇳🇬'],
        ['code' => 'yo', 'name' => 'Yorùbá', 'english' => 'Yoruba', 'flag' => '🇳🇬'],
        ['code' => 'ig', 'name' => 'Igbo', 'english' => 'Igbo', 'flag' => '🇳🇬'],
        ['code' => 'zu', 'name' => 'isiZulu', 'english' => 'Zulu', 'flag' => '🇿🇦'],
    ],
];
