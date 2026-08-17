<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaditaCare - Konsultasi Ternak Berbasis AI</title>
    <meta name="description" content="SaditaCare: Asisten Pintar Kesehatan Ternak dari SADITA.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: Inter, sans-serif; }
        .material-symbols-outlined { font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .typing-dot { animation: typingBounce 1.2s infinite ease-in-out; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-6px); }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-ink">
<div id="global-toast" class="fixed left-1/2 top-4 z-[120] hidden w-[calc(100%-24px)] max-w-[488px] -translate-x-1/2 rounded-xl bg-ink px-4 py-3 text-sm font-semibold text-white opacity-0 transition-opacity duration-300 shadow-xl"></div>
<div class="main-container-responsive bg-background pb-36">

    <header class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur">
        <a href="{{ route('chat') }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Kembali">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="text-center">
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">Asisten Pintar</p>
            <h1 class="text-base font-black text-primary">SaditaCare</h1>
        </div>
        <button id="clearHistory" class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-100" aria-label="Hapus Riwayat" title="Hapus Riwayat Chat">
            <span class="material-symbols-outlined">delete</span>
        </button>
    </header>

    <main class="px-5 pt-5">
        {{-- Intro card --}}
        <section class="rounded-2xl border border-line bg-white p-4 shadow-sm">
            <div class="flex gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary text-white">
                    <span class="material-symbols-outlined">stethoscope</span>
                </span>
                <div>
                    <h2 class="text-base font-black text-primary">SaditaCare</h2>
                    <p class="mt-1 text-sm leading-6 text-muted">Bantu kami memahami kondisi ternak Anda. Jelaskan gejala yang dialami, dan SaditaCare akan memberikan analisis awal serta rekomendasi produk yang relevan.</p>
                </div>
            </div>
            <div class="mt-3 rounded-xl bg-amber/10 p-3 text-xs leading-5 text-amber">
                <span class="font-bold">Disclaimer:</span> SaditaCare memberikan analisis awal berdasarkan informasi yang diberikan pengguna dan tidak menggantikan diagnosis dokter hewan. Jika kondisi ternak memburuk atau darurat, segera hubungi dokter hewan.
            </div>
        </section>



        {{-- Quick prompts --}}
        <section class="mt-4">
            <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.16em] text-muted">Contoh pertanyaan cepat</p>
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                @foreach(['Ayam saya sering ngorok, apa penyebabnya?', 'Ayam tidak mau makan, produk apa yang cocok?', 'Bagaimana mengatasi CRD pada ayam?', 'Vitamin apa yang cocok setelah vaksin?', 'Ayam saya mencret, langkah awal apa yang harus dilakukan?'] as $prompt)
                    <button class="prompt-chip shrink-0 rounded-full bg-white px-4 py-2 text-xs font-bold text-primary shadow-sm border border-line"
                        data-prompt="{{ $prompt }}">{{ Str::limit($prompt, 30) }}</button>
                @endforeach
            </div>
        </section>

        {{-- Chat log --}}
        <section id="chatLog" class="mt-4 space-y-4">
            <article class="flex gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                    <span class="material-symbols-outlined text-[19px]">stethoscope</span>
                </span>
                <div class="rounded-2xl rounded-tl-none border border-line bg-white p-4 shadow-sm">
                    <p class="text-sm leading-6">Halo! Saya SaditaCare. Ceritakan kondisi ternak Anda — jenis, umur, jumlah, gejala, dan sudah berapa lama. Saya akan bantu arahan awal dan rekomendasi produk.</p>
                </div>
            </article>
        </section>
    </main>

    {{-- Input form --}}
    <form id="aiForm" class="fixed bottom-0 left-1/2 z-50 flex -translate-x-1/2 items-end gap-2 border-t border-line bg-white/95 p-4 backdrop-blur fixed-container-responsive">
        <textarea id="aiInput" rows="1"
            class="w-full resize-none rounded-2xl border border-line bg-surface py-3 px-4 text-base leading-6 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 hide-scrollbar"
            placeholder="Tulis gejala atau tanya produk..."></textarea>
        <button class="shrink-0 mb-[1px] flex h-[48px] w-[48px] items-center justify-center rounded-full bg-primary text-white transition-opacity disabled:opacity-50"
            id="sendBtn" type="submit" aria-label="Kirim">
            <span class="material-symbols-outlined">send</span>
        </button>
    </form>

</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const chatLog = document.getElementById('chatLog');
const aiForm = document.getElementById('aiForm');
const aiInput = document.getElementById('aiInput');
const sendBtn = document.getElementById('sendBtn');
const CHAT_STORAGE_KEY = 'sadita_chat_history';
const CHAT_STORAGE_TTL_MS = 1000 * 60 * 60 * 24; // 24 jam
const CHAT_MAX_MESSAGES = 20;
const CHAT_MAX_TEXT_LENGTH = 8000;

let serverHistory = @json($chatHistory ?? []);
let chatHistory = [];
let lastFailedMessage = '';
let rateLimitCooldownUntil = 0;
let rateLimitTimer = null;

function saditaNotify(message, type = 'info') {
    const toast = document.getElementById('global-toast');
    if (!toast || !message) return;
    toast.textContent = message;
    toast.className = 'fixed left-1/2 top-4 z-[120] w-[calc(100%-24px)] max-w-[488px] -translate-x-1/2 rounded-xl px-4 py-3 text-sm font-semibold text-white opacity-0 transition-opacity duration-300 shadow-xl';
    toast.classList.add(type === 'error' ? 'bg-red-600' : (type === 'success' ? 'bg-moss' : 'bg-ink'));
    toast.classList.remove('hidden');
    requestAnimationFrame(() => toast.classList.add('opacity-100'));
    setTimeout(() => {
        toast.classList.remove('opacity-100');
        setTimeout(() => toast.classList.add('hidden'), 300);
    }, 2200);
}

// Load chat history on page load
window.addEventListener('DOMContentLoaded', () => {
    if (serverHistory && serverHistory.length > 0) {
        chatHistory = serverHistory.map(msg => ({ role: msg.role, text: msg.text }));
        chatHistory.forEach(msg => {
            appendMessage(msg.role, escapeHtml(msg.text), false);
        });
        persistHistory(chatHistory);
    } else {
        chatHistory = loadPersistedHistory();
        if (chatHistory.length > 0) {
            chatHistory.forEach(msg => {
                appendMessage(msg.role, escapeHtml(msg.text), false);
            });
        }
    }
});

// Quick prompt chips
document.querySelectorAll('.prompt-chip').forEach(btn => {
    btn.addEventListener('click', () => {
        aiInput.value = btn.dataset.prompt;
        aiInput.focus();
    });
});

function appendMessage(role, html, saveToHistory = true) {
    const isUser = role === 'user';
    const article = document.createElement('article');
    article.className = isUser ? 'flex justify-end' : 'flex gap-3';
    article.innerHTML = isUser
        ? `<div class="max-w-[86%] rounded-2xl rounded-tr-none bg-primary p-4 text-white shadow-sm"><p class="text-sm leading-6">${html}</p></div>`
        : `<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary text-white"><span class="material-symbols-outlined text-[19px]">stethoscope</span></span>
           <div class="max-w-[86%] rounded-2xl rounded-tl-none border border-line bg-white p-4 shadow-sm"><p class="text-sm leading-6">${html}</p></div>`;
    chatLog.appendChild(article);
    article.scrollIntoView({ behavior: 'smooth', block: 'end' });
    
    // Save to localStorage if needed
    if (saveToHistory && role !== 'assistant' && html.includes('Jenis ternak dipilih')) {
        // Don't save animal selection messages
        return article;
    }
    
    return article;
}

function saveHistory() {
    persistHistory(chatHistory);
}

function sanitizeMessageEntry(entry) {
    const role = entry?.role === 'assistant' ? 'assistant' : 'user';
    const text = String(entry?.text ?? '').trim().slice(0, CHAT_MAX_TEXT_LENGTH);
    if (!text) return null;
    return { role, text };
}

function loadPersistedHistory() {
    const raw = localStorage.getItem(CHAT_STORAGE_KEY);
    if (!raw) return [];

    try {
        const parsed = JSON.parse(raw);
        const now = Date.now();

        // Backward compatibility: format lama berupa array langsung.
        if (Array.isArray(parsed)) {
            const normalized = parsed.map(sanitizeMessageEntry).filter(Boolean).slice(-CHAT_MAX_MESSAGES);
            persistHistory(normalized);
            return normalized;
        }

        if (!parsed || !Array.isArray(parsed.messages)) {
            localStorage.removeItem(CHAT_STORAGE_KEY);
            return [];
        }

        const updatedAt = Number(parsed.updated_at) || 0;
        if (!updatedAt || now - updatedAt > CHAT_STORAGE_TTL_MS) {
            localStorage.removeItem(CHAT_STORAGE_KEY);
            return [];
        }

        return parsed.messages.map(sanitizeMessageEntry).filter(Boolean).slice(-CHAT_MAX_MESSAGES);
    } catch (_) {
        localStorage.removeItem(CHAT_STORAGE_KEY);
        return [];
    }
}

function persistHistory(history) {
    const messages = history
        .map(sanitizeMessageEntry)
        .filter(Boolean)
        .slice(-CHAT_MAX_MESSAGES);

    if (messages.length === 0) {
        localStorage.removeItem(CHAT_STORAGE_KEY);
        return;
    }

    localStorage.setItem(CHAT_STORAGE_KEY, JSON.stringify({
        updated_at: Date.now(),
        messages,
    }));
}

function appendTyping() {
    const article = document.createElement('article');
    article.id = 'typingIndicator';
    article.className = 'flex gap-3';
    article.innerHTML = `
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
            <span class="material-symbols-outlined text-[19px]">stethoscope</span>
        </span>
        <div class="rounded-2xl rounded-tl-none border border-line bg-white px-4 py-3 shadow-sm flex items-center gap-1">
            <span class="typing-dot h-2 w-2 rounded-full bg-muted inline-block"></span>
            <span class="typing-dot h-2 w-2 rounded-full bg-muted inline-block"></span>
            <span class="typing-dot h-2 w-2 rounded-full bg-muted inline-block"></span>
        </div>`;
    chatLog.appendChild(article);
    article.scrollIntoView({ behavior: 'smooth', block: 'end' });
}

function removeTyping() {
    document.getElementById('typingIndicator')?.remove();
}

function setSendButtonState(disabled, label = null) {
    sendBtn.disabled = disabled;
    const icon = sendBtn.querySelector('.material-symbols-outlined');
    if (icon) {
        icon.textContent = label ?? 'send';
    }
}

function startRateLimitCooldown(seconds) {
    const safeSeconds = Math.max(1, Number(seconds) || 60);
    rateLimitCooldownUntil = Date.now() + (safeSeconds * 1000);

    if (rateLimitTimer) {
        clearInterval(rateLimitTimer);
    }

    const tick = () => {
        const remaining = Math.max(0, Math.ceil((rateLimitCooldownUntil - Date.now()) / 1000));
        if (remaining <= 0) {
            clearInterval(rateLimitTimer);
            rateLimitTimer = null;
            setSendButtonState(false, 'send');
            return;
        }

        setSendButtonState(true, String(remaining));
    };

    tick();
    rateLimitTimer = setInterval(tick, 1000);
}

function isInRateLimitCooldown() {
    return Date.now() < rateLimitCooldownUntil;
}

// Escape HTML and parse product links
function escapeHtml(text) {
    // First escape HTML
    let escaped = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    
    // Convert markdown bold to HTML
    escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    
    // Convert newlines to br
    escaped = escaped.replace(/\n/g, '<br>');
    
    // Parse product markers and add buttons
    escaped = escaped.replace(/\[PRODUCT:([^\]|]+)(?:\|([^\]|]+)\|([^\]]+))?\]/g, function(match, slug, imageUrl, productName) {
        const href = '/toko/produk/' + slug;
        let imageHtml = '';
        if (imageUrl) {
            imageHtml = `
            <div class="mt-2 mb-3 max-w-[200px] overflow-hidden rounded-xl border border-line bg-white shadow-sm">
                <img src="${imageUrl}" alt="${productName || slug}" class="h-32 w-full object-cover" loading="lazy">
                ${productName ? `<div class="bg-surface px-3 py-2 text-xs font-bold text-ink truncate">${productName}</div>` : ''}
            </div>`;
        }
        
        return `
<br>
${imageHtml}
<div class="mt-2 flex flex-wrap gap-2">
    <button
        type="button"
        class="ai-buy-button inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-bold text-white hover:bg-primary/90 transition-colors"
        data-product-slug="${slug}"
    >
        <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
        Tambah ke Keranjang
    </button>
    <a
        href="${href}"
        class="inline-flex items-center gap-1 rounded-lg border border-line bg-white px-3 py-2 text-xs font-semibold text-primary hover:bg-surface transition-colors"
    >
        <span class="material-symbols-outlined text-[18px]">visibility</span>
        Lihat Detail
    </a>
</div>`;
    });
    
    return escaped;
}

async function sendMessage(message) {
    if (!message.trim()) return;
    if (isInRateLimitCooldown()) {
        const remaining = Math.max(1, Math.ceil((rateLimitCooldownUntil - Date.now()) / 1000));
        saditaNotify(`Tunggu ${remaining} detik sebelum kirim lagi.`, 'error');
        return;
    }
    if (!navigator.onLine) {
        saditaNotify('Anda sedang offline. Periksa koneksi internet.', 'error');
        appendMessage('assistant', 'Anda sedang offline. Periksa koneksi internet lalu coba lagi.');
        return;
    }

    appendMessage('user', escapeHtml(message));
    chatHistory.push({ role: 'user', text: message });
    saveHistory();

    aiInput.value = '';
    aiInput.style.height = 'auto';
    setSendButtonState(true, 'hourglass_top');
    appendTyping();

    try {
        const res = await fetch('/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                message,
                history: chatHistory.slice(0, -1), // history before current message
            })
        });

        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            if (res.status === 429) {
                const retryAfter = Number(data.retry_after) || 60;
                startRateLimitCooldown(retryAfter);
            }
            throw new Error(data.message || 'Layanan AI sedang sibuk. Coba beberapa saat lagi.');
        }
        removeTyping();

        const reply = data.reply || 'Maaf, terjadi kesalahan. Silakan coba lagi.';
        appendMessage('assistant', escapeHtml(reply));
        wireAiProductButtons();
        chatHistory.push({ role: 'assistant', text: reply });
        saveHistory();
    } catch (err) {
        removeTyping();
        lastFailedMessage = message;
        const errorMessage = err?.message || 'Maaf, tidak dapat terhubung. Periksa koneksi internet Anda.';
        saditaNotify(errorMessage, 'error');
        appendMessage('assistant', `${errorMessage}<br><button type="button" class="ai-retry-button mt-3 inline-flex h-9 items-center rounded-lg border border-primary px-3 text-xs font-bold text-primary">Coba Lagi</button>`);
    } finally {
        if (!isInRateLimitCooldown()) {
            setSendButtonState(false, 'send');
        }
    }
}

function wireAiProductButtons() {
    document.querySelectorAll('.ai-buy-button[data-product-slug]').forEach(button => {
        if (button.dataset.bound === '1') return;
        button.dataset.bound = '1';
        button.addEventListener('click', () => {
            const slug = button.dataset.productSlug;
            if (!slug || typeof window.saditaAddToCart !== 'function') return;
            const product = { id: slug };
            window.saditaAddToCart(product, 1);
        });
    });
}

aiForm.addEventListener('submit', e => {
    e.preventDefault();
    sendMessage(aiInput.value);
});

chatLog.addEventListener('click', (event) => {
    const retryButton = event.target.closest('.ai-retry-button');
    if (!retryButton || !lastFailedMessage) return;
    sendMessage(lastFailedMessage);
});

// Auto-resize textarea
aiInput.addEventListener('input', () => {
    aiInput.style.height = 'auto';
    aiInput.style.height = Math.min(aiInput.scrollHeight, 120) + 'px';
});

// Enter to send (Shift+Enter for newline)
aiInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage(aiInput.value);
    }
});

// Clear history button
document.getElementById('clearHistory').addEventListener('click', () => {
    if (confirm('Hapus semua riwayat percakapan?')) {
        chatHistory = [];
        localStorage.removeItem(CHAT_STORAGE_KEY);
        
        // Clear chat log but keep welcome message
        chatLog.innerHTML = `
            <article class="flex gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                    <span class="material-symbols-outlined text-[19px]">stethoscope</span>
                </span>
                <div class="rounded-2xl rounded-tl-none border border-line bg-white p-4 shadow-sm">
                    <p class="text-sm leading-6">Halo! Saya SaditaCare. Ceritakan kondisi ternak Anda — jenis, umur, jumlah, gejala, dan sudah berapa lama. Saya akan bantu arahan awal dan rekomendasi produk.</p>
                </div>
            </article>
        `;
    }
});
</script>
@include('partials.cart-script')
</body>
</html>
