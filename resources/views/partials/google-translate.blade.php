{{-- Widget Google Website Translator (tersembunyi) + helper pemilih bahasa. --}}
<div id="google_translate_element" aria-hidden="true" class="notranslate"></div>

<script>
(function () {
    var COOKIE = 'googtrans';
    var SOURCE = @json(config('translate.source', 'id'));

    function cookieDomains() {
        var host = window.location.hostname;
        var domains = [null];
        if (!/^\d{1,3}(\.\d{1,3}){3}$/.test(host) && host.indexOf('.') !== -1) {
            domains.push('.' + host);
            var parts = host.split('.');
            if (parts.length > 2) domains.push('.' + parts.slice(-2).join('.'));
        }
        return domains;
    }

    function readCookie(name) {
        var parts = ('; ' + document.cookie).split('; ' + name + '=');
        return parts.length < 2 ? null : parts.pop().split(';').shift();
    }

    window.currentTranslateLang = function () {
        var value = readCookie(COOKIE);
        if (!value) return SOURCE;
        value = decodeURIComponent(value).split('/').filter(Boolean).pop();
        return value || SOURCE;
    };

    function combo() {
        return document.querySelector('select.goog-te-combo');
    }

    // Ikon Material Symbols dirender dari teks-ligatur (mis. "home", "search").
    // Google Translate akan menerjemahkan teks itu & merusak ikon, jadi tandai
    // agar dilewati (class .notranslate + atribut translate="no").
    function shieldIcons(root) {
        var scope = root && root.querySelectorAll ? root : document;
        scope.querySelectorAll('.material-symbols-outlined:not(.notranslate), .material-icons:not(.notranslate)')
            .forEach(function (el) {
                el.classList.add('notranslate');
                el.setAttribute('translate', 'no');
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { shieldIcons(); });
    } else {
        shieldIcons();
    }
    document.addEventListener('livewire:navigated', function () { shieldIcons(); });
    new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
            var added = mutations[i].addedNodes;
            for (var j = 0; j < added.length; j++) {
                var node = added[j];
                if (node.nodeType !== 1) continue;
                if (node.classList && (node.classList.contains('material-symbols-outlined') || node.classList.contains('material-icons'))) {
                    node.classList.add('notranslate');
                    node.setAttribute('translate', 'no');
                }
                shieldIcons(node);
            }
        }
    }).observe(document.body || document.documentElement, { childList: true, subtree: true });

    window.applyLanguage = function (lang) {
        if (!lang || lang === SOURCE) {
            cookieDomains().forEach(function (d) {
                document.cookie = COOKIE + '=; path=/;' + (d ? ' domain=' + d + ';' : '') +
                    ' expires=Thu, 01 Jan 1970 00:00:00 GMT';
            });
            window.location.reload();
            return;
        }

        var value = '/' + SOURCE + '/' + lang;
        cookieDomains().forEach(function (d) {
            document.cookie = COOKIE + '=' + value + '; path=/;' + (d ? ' domain=' + d + ';' : '') +
                ' max-age=31536000; SameSite=Lax';
        });

        var el = combo();
        if (el) {
            el.value = lang;
            if (el.value === lang) {
                el.dispatchEvent(new Event('change', { bubbles: true }));
                window.dispatchEvent(new Event('google-translate:change'));
                return;
            }
        }
        window.location.reload();
    };

    window.googleTranslateElementInit = function () {
        if (!(window.google && window.google.translate && window.google.translate.TranslateElement)) return;
        new window.google.translate.TranslateElement({
            pageLanguage: SOURCE,
            layout: window.google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false,
        }, 'google_translate_element');
        window.dispatchEvent(new Event('google-translate:ready'));
    };

    function loadScript() {
        if (document.getElementById('google-translate-script')) return;
        shieldIcons();
        var s = document.createElement('script');
        s.id = 'google-translate-script';
        s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        s.async = true;
        document.head.appendChild(s);
    }

    var events = ['scroll', 'mousemove', 'touchstart', 'keydown'];
    var loaded = false;
    function trigger() {
        if (loaded) return;
        loaded = true;
        events.forEach(function (e) { window.removeEventListener(e, trigger); });
        loadScript();
    }

    if (window.currentTranslateLang() !== SOURCE) {
        trigger();
    } else {
        events.forEach(function (e) { window.addEventListener(e, trigger, { passive: true }); });
        setTimeout(trigger, 2000);
    }
})();
</script>
