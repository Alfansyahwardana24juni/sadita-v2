<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'SADITA - Solusi Sehat Ternak Anda' }}</title>
    <meta name="description" content="{{ $description ?? 'Partner produk kesehatan hewan dan peternakan untuk peternak Indonesia.' }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: Inter, sans-serif; }
        .material-symbols-outlined { font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24; }

        /* ── Hide modern Google Translate toolbar ── */
        .VIpgJd-ZVi9od-ORHb-OEVmcd, 
        .goog-te-banner-frame,
        iframe.skiptranslate,
        .skiptranslate > iframe,
        .goog-te-balloon-frame,
        #goog-gt-tt { display: none !important; visibility: hidden !important; }
        body { top: 0 !important; position: static !important; }
        .goog-tooltip, .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; box-shadow: none !important; }
        
        /* ── Style Google Translate Widget ── */
        #google_translate_element .goog-te-gadget {
            font-size: 0 !important; 
            color: transparent !important;
            display: flex;
            align-items: center;
        }
        #google_translate_element .goog-te-gadget .goog-te-combo {
            appearance: none;
            -webkit-appearance: none;
            background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 4px center;
            background-size: 14px 14px;
            border: none;
            padding: 4px 24px 4px 4px;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            font-family: Inter, sans-serif;
            cursor: pointer;
            outline: none;
            margin: 0;
            height: 32px;
        }
        #google_translate_element .goog-te-gadget .goog-te-combo:hover {
            border-color: #cbd5e1;
        }
        #google_translate_element .goog-te-gadget .goog-te-combo:focus {
            border-color: #800000;
        }
        #google_translate_element .goog-logo-link { display: none !important; }
        #google_translate_element .goog-te-gadget img { display: none !important; }
    </style>
    
    @stack('styles')
    @livewireStyles
</head>
<body class="h-[100dvh] overflow-hidden bg-[#F5F5F5] text-ink">

    <div class="main-container-responsive bg-white">
        <!-- Content -->
        <main class="page-container">
            {{ $slot }}
        </main>
        
        <!-- Bottom Navigation -->
        <div class="shrink-0 relative z-[60]">
            @include('layouts.partials.navbar')
        </div>
    </div>
    
    <x-saditacare-fab />

    @stack('scripts')
    @livewireScripts

    <!-- Google Translate Widget Script -->
    <div id="google_translate_element" style="display:none;"></div>
    <script>
        // Prevent Google Translate from translating material icons
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.material-symbols-outlined').forEach(function(icon) {
                icon.classList.add('notranslate');
                icon.setAttribute('translate', 'no');
            });
        });

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en,zh-CN,ar,ms,af,sq,am,hy,az,eu,be,bn,bs,bg,ca,ceb,zh-TW,co,hr,cs,da,nl,eo,et,fi,fr,fy,gl,ka,de,el,gu,ht,ha,haw,iw,hi,hmn,hu,is,ig,ga,it,ja,jw,kn,kk,km,ko,ku,ky,lo,la,lv,lt,lb,mk,mg,ml,mt,mi,mr,mn,my,ne,no,ny,ps,fa,pl,pt,pa,ro,ru,sm,gd,sr,st,sn,sd,si,sk,sl,so,es,su,sv,tl,tg,ta,te,th,tr,uk,ur,uz,vi,cy,xh,yi,yo,zu',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
</body>
</html>
