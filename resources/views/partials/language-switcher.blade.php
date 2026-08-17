{{-- Language Switcher Partial --}}
{{-- Menyembunyikan UI Google Translate bawaan dan membuat tombol custom dengan bendera negara --}}

<style>
/* ─── Language Switcher ─── */
#lang-fab {
    position: fixed;
    left: 50%;
    transform: translateX(-50%);
    bottom: calc(72px + 16px); /* di atas navbar */
    z-index: 55;
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 100px;
    padding: 6px 12px 6px 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.10);
    cursor: pointer;
    transition: box-shadow 0.2s, transform 0.2s;
    font-family: Inter, sans-serif;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    max-width: calc(100vw - 32px);
}
#lang-fab:active { transform: translateX(-50%) scale(0.97); }
#lang-fab:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.14); }

#lang-fab .lang-flag {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 1.5px solid rgba(0,0,0,0.08);
}
#lang-fab .lang-label {
    font-size: 12px;
    font-weight: 700;
    color: #374151;
    white-space: nowrap;
}
#lang-fab .lang-arrow {
    font-size: 16px;
    color: #9CA3AF;
    flex-shrink: 0;
    transition: transform 0.25s;
}
#lang-fab.open .lang-arrow { transform: rotate(180deg); }

/* ─── Modal backdrop ─── */
#lang-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 200;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    animation: fadeInBd 0.2s ease;
}
@keyframes fadeInBd { from { opacity:0; } to { opacity:1; } }
#lang-modal-backdrop.active { display: block; }

/* ─── Modal sheet ─── */
#lang-modal {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%) translateY(100%);
    z-index: 210;
    width: 100%;
    max-width: 488px;
    background: #fff;
    border-radius: 24px 24px 0 0;
    padding: 0 0 env(safe-area-inset-bottom, 0);
    box-shadow: 0 -8px 40px rgba(0,0,0,0.18);
    transition: transform 0.35s cubic-bezier(0.32,0.72,0,1);
    font-family: Inter, sans-serif;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
}
#lang-modal.open { transform: translateX(-50%) translateY(0); }

#lang-modal-handle {
    width: 40px; height: 4px;
    background: #E5E7EB;
    border-radius: 2px;
    margin: 12px auto 0;
    flex-shrink: 0;
}
#lang-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px 12px;
    flex-shrink: 0;
    border-bottom: 1px solid #F3F4F6;
}
#lang-modal-header h2 {
    font-size: 16px;
    font-weight: 800;
    color: #111827;
    margin: 0;
}
#lang-modal-close {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #F3F4F6;
    border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
}
#lang-modal-close:hover { background: #E5E7EB; }
#lang-modal-close svg { width:16px; height:16px; color:#6B7280; }

#lang-search-wrap {
    padding: 12px 16px;
    flex-shrink: 0;
    position: relative;
}
#lang-search {
    width: 100%;
    height: 40px;
    border-radius: 12px;
    border: 1.5px solid #E5E7EB;
    background: #F9FAFB;
    padding: 0 12px 0 36px;
    font-size: 13px;
    font-family: Inter, sans-serif;
    color: #111827;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.15s;
}
#lang-search:focus { border-color: #800000; }
#lang-search-icon {
    position: absolute;
    left: 28px;
    top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
    pointer-events: none;
    font-size: 18px;
}

#lang-list {
    overflow-y: auto;
    flex: 1;
    padding: 4px 8px 16px;
    -webkit-overflow-scrolling: touch;
}
#lang-list::-webkit-scrollbar { display: none; }

.lang-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 14px;
    cursor: pointer;
    transition: background 0.12s;
    border: 1.5px solid transparent;
}
.lang-item:hover { background: #F9FAFB; }
.lang-item.selected {
    background: #FFF0F0;
    border-color: rgba(128,0,0,0.15);
}
.lang-item img {
    width: 32px; height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 1.5px solid rgba(0,0,0,0.07);
}
.lang-item-info { flex: 1; min-width: 0; }
.lang-item-name {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lang-item-native {
    font-size: 11px;
    color: #9CA3AF;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lang-item-check {
    width: 20px; height: 20px;
    border-radius: 50%;
    background: #800000;
    display: none;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.lang-item.selected .lang-item-check { display: flex; }
.lang-item-check svg { width:12px; height:12px; color:#fff; }

.lang-section-title {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #9CA3AF;
    padding: 10px 12px 6px;
}

#lang-no-results {
    text-align: center;
    padding: 32px 16px;
    color: #9CA3AF;
    font-size: 14px;
    font-weight: 600;
    display: none;
}
/* Dropdown language selector */
#lang-select {
    appearance: none;
    background: #fff url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOCIgaGVpZ2h0PSI0IiB2aWV3Qm94PSIwIDAgOCA0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik00LjYwNTIgMS4xOTA5bC01Ljg1NjggNmMwLjE3OTI1IDAuMTc5MjQgMC40NzM2NCAwLjE3OTI0IDAuNjU1OTUgMCAwLjEzNzI0LTAuMTc5MjYgMC4yNDM3Mi0wLjQ5NDEzIDAuMjQzNzJIMTguNUw1Ljg5NDUzIDQuNzcwNjJMOCA0LjczNzA0WiIgZmlsbD0iI0AwMDAwMCIvPjwvc3ZnPg==') no-repeat right 10px center;
    background-size: 12px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 14px;
    font-family: Inter, sans-serif;
    color: #111827;
    width: 180px;
    max-width: 100%;
    cursor: pointer;
}
</style>

<!-- Language Switcher FAB -->
<div id="lang-fab" onclick="openModal()">
    <img id="lang-fab-flag" class="lang-flag" src="https://flagcdn.com/w40/id.png" alt="ID">
    <span id="lang-fab-label" class="lang-label">Indonesia</span>
    <span class="material-symbols-outlined lang-arrow">expand_more</span>
</div>

<!-- Modal Backdrop -->
<div id="lang-modal-backdrop" onclick="closeModal()"></div>

<!-- Modal -->
<div id="lang-modal">
    <div id="lang-modal-handle"></div>
    <div id="lang-modal-header">
        <h2>Pilih Bahasa</h2>
        <button id="lang-modal-close" onclick="closeModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6L6 18M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div id="lang-search-wrap">
        <span class="material-symbols-outlined" id="lang-search-icon">search</span>
        <input type="text" id="lang-search" placeholder="Cari bahasa...">
    </div>
    <div id="lang-list"></div>
    <div id="lang-no-results">Bahasa tidak ditemukan</div>
</div>

<script>
(function() { 
    if (!document.getElementById('lang-fab')) return;
    // ── Language data (kode google: kode flag, nama, nama asli)
    const LANGUAGES = [
        // Prioritas teratas
        { google: 'id', flag: 'id', name: 'Indonesia',    native: 'Bahasa Indonesia', priority: true },
        { google: 'en', flag: 'gb', name: 'English',      native: 'English',          priority: true },
        { google: 'zh-CN', flag: 'cn', name: 'Chinese (Simplified)', native: '中文 (简体)', priority: true },
        { google: 'ar', flag: 'sa', name: 'Arabic',       native: 'العربية',          priority: true },
        { google: 'ms', flag: 'my', name: 'Malay',        native: 'Bahasa Melayu',    priority: true },
        // Semua negara lainnya
        { google: 'af', flag: 'za', name: 'Afrikaans',     native: 'Afrikaans' },
        { google: 'sq', flag: 'al', name: 'Albanian',      native: 'Shqip' },
        { google: 'am', flag: 'et', name: 'Amharic',       native: 'አማርኛ' },
        { google: 'hy', flag: 'am', name: 'Armenian',      native: 'Հայերեն' },
        { google: 'az', flag: 'az', name: 'Azerbaijani',   native: 'Azərbaycanca' },
        { google: 'eu', flag: 'es', name: 'Basque',        native: 'Euskara' },
        { google: 'be', flag: 'by', name: 'Belarusian',    native: 'Беларуская' },
        { google: 'bn', flag: 'bd', name: 'Bengali',       native: 'বাংলা' },
        { google: 'bs', flag: 'ba', name: 'Bosnian',       native: 'Bosanski' },
        { google: 'bg', flag: 'bg', name: 'Bulgarian',     native: 'Български' },
        { google: 'ca', flag: 'es', name: 'Catalan',       native: 'Català' },
        { google: 'ceb', flag: 'ph', name: 'Cebuano',      native: 'Cebuano' },
        { google: 'zh-TW', flag: 'tw', name: 'Chinese (Traditional)', native: '繁體中文' },
        { google: 'co', flag: 'fr', name: 'Corsican',      native: 'Corsu' },
        { google: 'hr', flag: 'hr', name: 'Croatian',      native: 'Hrvatski' },
        { google: 'cs', flag: 'cz', name: 'Czech',         native: 'Čeština' },
        { google: 'da', flag: 'dk', name: 'Danish',        native: 'Dansk' },
        { google: 'nl', flag: 'nl', name: 'Dutch',         native: 'Nederlands' },
        { google: 'eo', flag: 'eu', name: 'Esperanto',     native: 'Esperanto' },
        { google: 'et', flag: 'ee', name: 'Estonian',      native: 'Eesti' },
        { google: 'fi', flag: 'fi', name: 'Finnish',       native: 'Suomi' },
        { google: 'fr', flag: 'fr', name: 'French',        native: 'Français' },
        { google: 'fy', flag: 'nl', name: 'Frisian',       native: 'Frysk' },
        { google: 'gl', flag: 'es', name: 'Galician',      native: 'Galego' },
        { google: 'ka', flag: 'ge', name: 'Georgian',      native: 'ქართული' },
        { google: 'de', flag: 'de', name: 'German',        native: 'Deutsch' },
        { google: 'el', flag: 'gr', name: 'Greek',         native: 'Ελληνικά' },
        { google: 'gu', flag: 'in', name: 'Gujarati',      native: 'ગુજરાતી' },
        { google: 'ht', flag: 'ht', name: 'Haitian Creole',native: 'Kreyòl ayisyen' },
        { google: 'ha', flag: 'ng', name: 'Hausa',         native: 'Hausa' },
        { google: 'haw', flag: 'us', name: 'Hawaiian',     native: 'ʻŌlelo Hawaiʻi' },
        { google: 'iw', flag: 'il', name: 'Hebrew',        native: 'עברית' },
        { google: 'hi', flag: 'in', name: 'Hindi',         native: 'हिन्दी' },
        { google: 'hmn', flag: 'cn', name: 'Hmong',        native: 'Hmong' },
        { google: 'hu', flag: 'hu', name: 'Hungarian',     native: 'Magyar' },
        { google: 'is', flag: 'is', name: 'Icelandic',     native: 'Íslenska' },
        { google: 'ig', flag: 'ng', name: 'Igbo',          native: 'Igbo' },
        { google: 'ga', flag: 'ie', name: 'Irish',         native: 'Gaeilge' },
        { google: 'it', flag: 'it', name: 'Italian',       native: 'Italiano' },
        { google: 'ja', flag: 'jp', name: 'Japanese',      native: '日本語' },
        { google: 'jw', flag: 'id', name: 'Javanese',      native: 'Basa Jawa' },
        { google: 'kn', flag: 'in', name: 'Kannada',       native: 'ಕನ್ನಡ' },
        { google: 'kk', flag: 'kz', name: 'Kazakh',        native: 'Қазақша' },
        { google: 'km', flag: 'kh', name: 'Khmer',         native: 'ភាសាខ្មែរ' },
        { google: 'ko', flag: 'kr', name: 'Korean',        native: '한국어' },
        { google: 'ku', flag: 'iq', name: 'Kurdish',       native: 'Kurdî' },
        { google: 'ky', flag: 'kg', name: 'Kyrgyz',        native: 'Кыргызча' },
        { google: 'lo', flag: 'la', name: 'Lao',           native: 'ລາວ' },
        { google: 'la', flag: 'va', name: 'Latin',         native: 'Latina' },
        { google: 'lv', flag: 'lv', name: 'Latvian',       native: 'Latviešu' },
        { google: 'lt', flag: 'lt', name: 'Lithuanian',    native: 'Lietuvių' },
        { google: 'lb', flag: 'lu', name: 'Luxembourgish', native: 'Lëtzebuergesch' },
        { google: 'mk', flag: 'mk', name: 'Macedonian',    native: 'Македонски' },
        { google: 'mg', flag: 'mg', name: 'Malagasy',      native: 'Malagasy' },
        { google: 'ml', flag: 'in', name: 'Malayalam',     native: 'മലയാളം' },
        { google: 'mt', flag: 'mt', name: 'Maltese',       native: 'Malti' },
        { google: 'mi', flag: 'nz', name: 'Maori',         native: 'Māori' },
        { google: 'mr', flag: 'in', name: 'Marathi',       native: 'मराठी' },
        { google: 'mn', flag: 'mn', name: 'Mongolian',     native: 'Монгол' },
        { google: 'my', flag: 'mm', name: 'Myanmar (Burmese)', native: 'မြန်မာဘာသာ' },
        { google: 'ne', flag: 'np', name: 'Nepali',        native: 'नेपाली' },
        { google: 'no', flag: 'no', name: 'Norwegian',     native: 'Norsk' },
        { google: 'ny', flag: 'mw', name: 'Nyanja (Chichewa)', native: 'Chichewa' },
        { google: 'ps', flag: 'af', name: 'Pashto',        native: 'پښتو' },
        { google: 'fa', flag: 'ir', name: 'Persian',       native: 'فارسی' },
        { google: 'pl', flag: 'pl', name: 'Polish',        native: 'Polski' },
        { google: 'pt', flag: 'pt', name: 'Portuguese',    native: 'Português' },
        { google: 'pa', flag: 'in', name: 'Punjabi',       native: 'ਪੰਜਾਬੀ' },
        { google: 'ro', flag: 'ro', name: 'Romanian',      native: 'Română' },
        { google: 'ru', flag: 'ru', name: 'Russian',       native: 'Русский' },
        { google: 'sm', flag: 'ws', name: 'Samoan',        native: 'Samoa' },
        { google: 'gd', flag: 'gb', name: 'Scots Gaelic',  native: 'Gàidhlig' },
        { google: 'sr', flag: 'rs', name: 'Serbian',       native: 'Српски' },
        { google: 'st', flag: 'ls', name: 'Sesotho',       native: 'Sesotho' },
        { google: 'sn', flag: 'zw', name: 'Shona',         native: 'Shona' },
        { google: 'sd', flag: 'pk', name: 'Sindhi',        native: 'سنڌي' },
        { google: 'si', flag: 'lk', name: 'Sinhala',       native: 'සිංහල' },
        { google: 'sk', flag: 'sk', name: 'Slovak',        native: 'Slovenčina' },
        { google: 'sl', flag: 'si', name: 'Slovenian',     native: 'Slovenščina' },
        { google: 'so', flag: 'so', name: 'Somali',        native: 'Soomaali' },
        { google: 'es', flag: 'es', name: 'Spanish',       native: 'Español' },
        { google: 'su', flag: 'id', name: 'Sundanese',     native: 'Basa Sunda' },
        { google: 'sw', flag: 'tz', name: 'Swahili',       native: 'Kiswahili' },
        { google: 'sv', flag: 'se', name: 'Swedish',       native: 'Svenska' },
        { google: 'tl', flag: 'ph', name: 'Tagalog (Filipino)', native: 'Filipino' },
        { google: 'tg', flag: 'tj', name: 'Tajik',         native: 'Тоҷикӣ' },
        { google: 'ta', flag: 'in', name: 'Tamil',         native: 'தமிழ்' },
        { google: 'te', flag: 'in', name: 'Telugu',        native: 'తెలుగు' },
        { google: 'th', flag: 'th', name: 'Thai',          native: 'ภาษาไทย' },
        { google: 'tr', flag: 'tr', name: 'Turkish',       native: 'Türkçe' },
        { google: 'uk', flag: 'ua', name: 'Ukrainian',     native: 'Українська' },
        { google: 'ur', flag: 'pk', name: 'Urdu',          native: 'اردو' },
        { google: 'uz', flag: 'uz', name: 'Uzbek',         native: 'Oʻzbekcha' },
        { google: 'vi', flag: 'vn', name: 'Vietnamese',    native: 'Tiếng Việt' },
        { google: 'cy', flag: 'gb', name: 'Welsh',         native: 'Cymraeg' },
        { google: 'xh', flag: 'za', name: 'Xhosa',         native: 'isiXhosa' },
        { google: 'yi', flag: 'il', name: 'Yiddish',       native: 'ייִדיש' },
        { google: 'yo', flag: 'ng', name: 'Yoruba',        native: 'Yorùbá' },
        { google: 'zu', flag: 'za', name: 'Zulu',          native: 'isiZulu' },
    ];

    // Deteksi bahasa aktif dari cookie Google Translate
    function getActiveLang() {
        const match = document.cookie.match(/googtrans=\/id\/([^;]+)/);
        return match ? match[1] : 'id';
    }

    function setActiveLang(googleCode) {
        // Hapus cookie lama
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname + ';';
        if (googleCode === 'id') {
            // Reset ke bahasa asli
            document.cookie = 'googtrans=/id/id; path=/;';
            document.cookie = 'googtrans=/id/id; path=/; domain=' + window.location.hostname + ';';
        } else {
            document.cookie = 'googtrans=/id/' + googleCode + '; path=/;';
            document.cookie = 'googtrans=/id/' + googleCode + '; path=/; domain=' + window.location.hostname + ';';
        }
        window.location.reload();
    }

    function updateFab(lang) {
        const found = LANGUAGES.find(l => l.google === lang) || LANGUAGES[0];
        const fabFlag = document.getElementById('lang-fab-flag');
        const fabLabel = document.getElementById('lang-fab-label');
        if (fabFlag) fabFlag.src = 'https://flagcdn.com/w40/' + found.flag + '.png';
        if (fabFlag) fabFlag.alt = found.flag.toUpperCase();
        if (fabLabel) fabLabel.textContent = found.name;
    }

    function renderList(filter) {
        const list = document.getElementById('lang-list');
        const noResults = document.getElementById('lang-no-results');
        if (!list) return;

        const activeLang = getActiveLang();
        const q = (filter || '').toLowerCase().trim();

        const filtered = LANGUAGES.filter(l =>
            !q || l.name.toLowerCase().includes(q) || l.native.toLowerCase().includes(q)
        );

        // Bersihkan kecuali no-results
        Array.from(list.children).forEach(c => {
            if (c.id !== 'lang-no-results') c.remove();
        });

        if (filtered.length === 0) {
            noResults.style.display = 'block';
            return;
        }
        noResults.style.display = 'none';

        const priority = filtered.filter(l => l.priority);
        const others   = filtered.filter(l => !l.priority);

        function makeItem(lang) {
            const div = document.createElement('div');
            div.className = 'lang-item' + (lang.google === activeLang ? ' selected' : '');
            div.innerHTML = `
                <img src="https://flagcdn.com/w40/${lang.flag}.png" alt="${lang.flag.toUpperCase()}" loading="lazy" onerror="this.src='https://flagcdn.com/w40/un.png'">
                <div class="lang-item-info">
                    <div class="lang-item-name">${lang.name}</div>
                    <div class="lang-item-native">${lang.native}</div>
                </div>
                <div class="lang-item-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>`;
            div.addEventListener('click', () => {
                closeModal();
                if (lang.google !== activeLang) {
                    setActiveLang(lang.google);
                }
            });
            return div;
        }

        if (!q && priority.length > 0) {
            const title1 = document.createElement('div');
            title1.className = 'lang-section-title';
            title1.textContent = 'Utama';
            list.appendChild(title1);
            priority.forEach(l => list.appendChild(makeItem(l)));
        }

        if (others.length > 0) {
            if (!q && priority.length > 0) {
                const title2 = document.createElement('div');
                title2.className = 'lang-section-title';
                title2.textContent = 'Semua Bahasa';
                list.appendChild(title2);
            }
            others.forEach(l => list.appendChild(makeItem(l)));
        }

        if (q) {
            // Kalau ada search, tampilkan semua hasil tanpa section
            Array.from(list.children).forEach(c => {
                if (c.id !== 'lang-no-results') c.remove();
            });
            noResults.style.display = 'none';
            filtered.forEach(l => list.appendChild(makeItem(l)));
        }
    }

    function openModal() {
        const backdrop = document.getElementById('lang-modal-backdrop');
        const modal    = document.getElementById('lang-modal');
        const fab      = document.getElementById('lang-fab');
        const search   = document.getElementById('lang-search');
        if (!modal || !backdrop) return;
        backdrop.classList.add('active');
        modal.classList.add('open');
        if (fab) fab.classList.add('open');
        renderList('');
        setTimeout(() => search && search.focus(), 350);
    }

    function closeModal() {
        const backdrop = document.getElementById('lang-modal-backdrop');
        const modal    = document.getElementById('lang-modal');
        const fab      = document.getElementById('lang-fab');
        const search   = document.getElementById('lang-search');
        if (!modal || !backdrop) return;
        modal.classList.remove('open');
        backdrop.classList.remove('active');
        if (fab) fab.classList.remove('open');
        if (search) search.value = '';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const activeLang = getActiveLang();
        updateFab(activeLang);
        
        const searchInput = document.getElementById('lang-search');
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                renderList(e.target.value);
            });
        }
    });
})();
</script>
