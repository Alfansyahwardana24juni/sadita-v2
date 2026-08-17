<?php

// Mapping of languages with their native name, english name, and flag code (ISO 3166-1 alpha-2 for flagcdn)
$languages = [
    "id" => ["name" => "Bahasa Indonesia", "en" => "Indonesian", "flag" => "id"],
    "en" => ["name" => "English", "en" => "English", "flag" => "gb"],
    "ar" => ["name" => "العربية", "en" => "Arabic", "flag" => "sa"],
    "zh-CN" => ["name" => "简体中文", "en" => "Chinese (Simplified)", "flag" => "cn"],
    "ja" => ["name" => "日本語", "en" => "Japanese", "flag" => "jp"],
    "ko" => ["name" => "한국어", "en" => "Korean", "flag" => "kr"],
    "hi" => ["name" => "हिन्दी", "en" => "Hindi", "flag" => "in"],
    "th" => ["name" => "ไทย", "en" => "Thai", "flag" => "th"],
    "vi" => ["name" => "Tiếng Việt", "en" => "Vietnamese", "flag" => "vn"],
    "tl" => ["name" => "Filipino", "en" => "Filipino", "flag" => "ph"],
    "ms" => ["name" => "Bahasa Melayu", "en" => "Malay", "flag" => "my"],
    "fr" => ["name" => "Français", "en" => "French", "flag" => "fr"],
    "de" => ["name" => "Deutsch", "en" => "German", "flag" => "de"],
    "es" => ["name" => "Español", "en" => "Spanish", "flag" => "es"],
    "ru" => ["name" => "Русский", "en" => "Russian", "flag" => "ru"],
    "pt" => ["name" => "Português", "en" => "Portuguese", "flag" => "pt"],
    "it" => ["name" => "Italiano", "en" => "Italian", "flag" => "it"],
    "nl" => ["name" => "Nederlands", "en" => "Dutch", "flag" => "nl"],
    "tr" => ["name" => "Türkçe", "en" => "Turkish", "flag" => "tr"]
];

$current_lang = "id";
if (isset($_COOKIE["googtrans"])) {
    $parts = explode("/", $_COOKIE["googtrans"]);
    $current_lang = end($parts);
}

// Fallback if somehow current_lang is not in array
if (!array_key_exists($current_lang, $languages)) {
    $current_lang = "id";
}
?>

<div class="relative" id="lang-switcher-container">
    <!-- Trigger Button -->
    <button 
        type="button" 
        id="lang-switcher-button"
        class="flex items-center gap-2 rounded-full border border-line bg-white px-3 py-1.5 shadow-sm transition-all hover:border-primary/30"
        aria-expanded="false"
        aria-haspopup="true"
    >
        <img src="https://flagcdn.com/w20/{{ $languages[$current_lang]['flag'] }}.png" width="20" alt="{{ $current_lang }}" class="rounded-[2px] shadow-sm">
        <span class="text-sm font-bold text-slate-700 uppercase">{{ strtoupper($current_lang) }}</span>
        <span class="material-symbols-outlined text-[18px] text-slate-500">expand_more</span>
    </button>

    <!-- Dropdown Menu -->
    <div 
        id="lang-switcher-menu"
        class="absolute right-0 mt-2 w-72 origin-top-right rounded-2xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 transition-all opacity-0 invisible z-[100] flex flex-col max-h-[400px] overflow-hidden"
    >
        <!-- Search Bar -->
        <div class="p-3 border-b border-line bg-slate-50/50">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">search</span>
                <input 
                    type="text" 
                    id="lang-search-input"
                    class="w-full rounded-xl border border-line bg-white py-2.5 pl-10 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-2 focus:ring-primary/20"
                    placeholder="Cari bahasa..."
                >
            </div>
        </div>

        <!-- Language List -->
        <div class="overflow-y-auto p-2 pb-3" id="lang-list-container">
            <div class="px-3 pb-2 pt-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Populer</div>
            
            @foreach($languages as $code => $data)
                <button 
                    type="button"
                    class="lang-item flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left transition-all hover:bg-slate-50 active:scale-[0.98] {{ $current_lang === $code ? 'bg-slate-50' : '' }}"
                    data-code="{{ $code }}"
                    data-search="{{ strtolower($data['name'] . ' ' . $data['en']) }}"
                    onclick="changeLanguage('{{ $code }}')"
                >
                    <div class="flex items-center gap-3">
                        <img src="https://flagcdn.com/w20/{{ $data['flag'] }}.png" width="20" alt="{{ $code }}" class="rounded-[2px] shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold {{ $current_lang === $code ? 'text-primary' : 'text-slate-700' }}">{{ $data['name'] }}</span>
                            <span class="text-[11px] font-medium text-slate-400">{{ $data['en'] }}</span>
                        </div>
                    </div>
                    @if($current_lang === $code)
                        <span class="material-symbols-outlined text-[20px] text-primary">check</span>
                    @endif
                </button>
            @endforeach
            
            <!-- No results message -->
            <div id="lang-no-results" class="hidden py-4 text-center text-sm text-slate-500">
                Bahasa tidak ditemukan
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('lang-switcher-button');
        const menu = document.getElementById('lang-switcher-menu');
        const searchInput = document.getElementById('lang-search-input');
        const langItems = document.querySelectorAll('.lang-item');
        const noResults = document.getElementById('lang-no-results');
        
        // Toggle dropdown
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!menu.contains(e.target) && !button.contains(e.target)) {
                closeDropdown();
            }
        });

        function openDropdown() {
            menu.classList.remove('opacity-0', 'invisible');
            menu.classList.add('opacity-100', 'visible');
            button.setAttribute('aria-expanded', 'true');
            setTimeout(() => searchInput.focus(), 50);
        }

        function closeDropdown() {
            menu.classList.remove('opacity-100', 'visible');
            menu.classList.add('opacity-0', 'invisible');
            button.setAttribute('aria-expanded', 'false');
            // Reset search
            searchInput.value = '';
            filterLanguages('');
        }

        // Search functionality
        searchInput.addEventListener('input', function(e) {
            filterLanguages(e.target.value.toLowerCase());
        });

        function filterLanguages(term) {
            let hasVisible = false;
            langItems.forEach(item => {
                const searchStr = item.getAttribute('data-search');
                if (searchStr.includes(term)) {
                    item.style.display = 'flex';
                    hasVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (hasVisible) {
                noResults.classList.add('hidden');
            } else {
                noResults.classList.remove('hidden');
            }
        }
    });

    function changeLanguage(langCode) {
        // Clear all possible variations of the googtrans cookie first
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=" + location.hostname + "; path=/;";
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=." + location.hostname + "; path=/;";
        
        if (langCode !== 'id') {
            var cookieValue = "/id/" + langCode;
            document.cookie = "googtrans=" + cookieValue + "; path=/";
            document.cookie = "googtrans=" + cookieValue + "; domain=" + location.hostname + "; path=/";
        }
        
        window.location.reload();
    }
</script>
