@if (!request()->routeIs('saditacare'))
    <div x-data="fabChat()" 
         x-init="initChat()"
         class="fixed bottom-24 right-5 z-[70] flex flex-col items-end gap-3"
    >
        <!-- Greeting Bubble -->
        <div 
            x-show="showGreeting && !open" 
            x-transition:enter="transition-all ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition-all ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-90"
            class="relative mr-1 max-w-[200px] rounded-2xl rounded-br-none bg-white p-3 shadow-[0_8px_30px_rgba(0,0,0,0.12)] border border-slate-100"
            style="display: none;"
        >
            <div class="flex items-center justify-between gap-2">
                <p class="text-[13px] font-black text-primary">Halo! 👋</p>
                <button @click.stop="showGreeting = false" class="text-muted hover:text-ink"><span class="material-symbols-outlined text-[14px]">close</span></button>
            </div>
            <p class="mt-1 text-[11px] font-medium leading-4 text-muted">Klik ikon robot ini jika Anda butuh konsultasi cepat.</p>
            <!-- Pointer triangle -->
            <div class="absolute -bottom-2 right-4 h-4 w-4 rotate-45 border-b border-r border-slate-100 bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.03)]"></div>
        </div>

        <!-- FAB Button -->
        <button 
            @click="open = true; showGreeting = false" 
            class="group flex h-14 w-14 items-center justify-center rounded-full bg-primary text-white shadow-[0_4px_20px_rgba(128,0,0,0.3)] transition-transform hover:scale-105 active:scale-95"
            style="animation: pulse-soft 2.5s infinite;"
        >
            <span class="material-symbols-outlined text-[28px]">smart_toy</span>
        </button>

        <!-- Backdrop -->
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[80] bg-ink/40 backdrop-blur-sm"
            @click="open = false"
            style="display: none;"
        ></div>

        <!-- Pop-up Chat Bottom Sheet -->
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="fixed bottom-0 left-0 right-0 z-[90] mx-auto flex w-full max-w-[480px] flex-col rounded-t-3xl bg-slate-50 shadow-xl"
            style="height: 85vh; display: none;"
        >
            <!-- Header -->
            <div class="flex items-center justify-between rounded-t-3xl border-b border-line bg-white px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-[22px]">smart_toy</span>
                        <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-moss"></span>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-ink">SaditaCare AI</h3>
                        <p class="text-[11px] font-semibold text-moss">Online</p>
                    </div>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('saditacare') }}" class="flex h-8 w-8 items-center justify-center rounded-full text-muted hover:bg-surface hover:text-primary" title="Buka Layar Penuh">
                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                    </a>
                    <button @click="open = false" class="flex h-8 w-8 items-center justify-center rounded-full text-muted hover:bg-surface hover:text-ink">
                        <span class="material-symbols-outlined text-[24px]">keyboard_arrow_down</span>
                    </button>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 overflow-y-auto p-4 hide-scrollbar" id="fabChatLog">
                <!-- Welcome Message -->
                <div class="mb-4 flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                        <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                    </span>
                    <div class="max-w-[85%] rounded-2xl rounded-tl-none border border-line bg-white p-3 shadow-sm">
                        <p class="text-xs leading-5 text-ink">Halo! Saya asisten AI SADITA. Ada yang bisa saya bantu terkait produk atau kesehatan ternak Anda?</p>
                    </div>
                </div>

                <!-- Messages -->
                <template x-for="(msg, index) in messages" :key="index">
                    <div class="mb-4">
                        <!-- User Message -->
                        <div x-show="msg.role === 'user'" class="flex justify-end">
                            <div class="max-w-[85%] rounded-2xl rounded-tr-none bg-primary p-3 text-white shadow-sm">
                                <p class="text-xs leading-5" x-text="msg.text"></p>
                            </div>
                        </div>
                        
                        <!-- AI Message -->
                        <div x-show="msg.role === 'assistant'" class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                                <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                            </span>
                            <div class="max-w-[85%] rounded-2xl rounded-tl-none border border-line bg-white p-3 shadow-sm">
                                <p class="text-xs leading-5 text-ink" x-html="msg.text"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Typing indicator -->
                <div x-show="isLoading" class="mb-4 flex gap-3" style="display: none;">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                        <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                    </span>
                    <div class="flex items-center gap-1 rounded-2xl rounded-tl-none border border-line bg-white px-4 py-3 shadow-sm">
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-muted" style="animation-delay: 0s"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-muted" style="animation-delay: 0.2s"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-muted" style="animation-delay: 0.4s"></span>
                    </div>
                </div>
            </div>

            <!-- Quick Suggestions (hides when chatting) -->
            <div x-show="messages.length === 0" class="px-4 pb-2">
                <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
                    <button @click="setInput('Rekomendasi vitamin ayam')" class="shrink-0 rounded-full border border-line bg-white px-3 py-1.5 text-[11px] font-bold text-primary shadow-sm active:scale-95">Rekomendasi vitamin ayam</button>
                    <button @click="setInput('Sapi kurang nafsu makan')" class="shrink-0 rounded-full border border-line bg-white px-3 py-1.5 text-[11px] font-bold text-primary shadow-sm active:scale-95">Sapi kurang nafsu makan</button>
                    <button @click="setInput('Info produk antibiotik')" class="shrink-0 rounded-full border border-line bg-white px-3 py-1.5 text-[11px] font-bold text-primary shadow-sm active:scale-95">Info produk antibiotik</button>
                </div>
            </div>

            <!-- Input Form -->
            <div class="border-t border-line bg-white p-3 pb-safe">
                <div x-show="aiExhausted" class="mb-2 rounded-xl bg-amber-500/10 border border-amber-500/30 p-3 text-center" style="display: none;">
                    <span class="material-symbols-outlined text-amber-500 text-[20px]">warning</span>
                    <p class="mt-1 text-xs font-bold text-amber-500">Layanan AI sedang tidak tersedia</p>
                    <p class="mt-1 text-[11px] text-muted">Batas pemakaian harian tercapai. Silakan hubungi tim SADITA via WhatsApp.</p>
                </div>
                <form @submit.prevent="sendMessage" class="flex items-end gap-2">
                    <textarea 
                        x-model="inputText" 
                        rows="1"
                        :disabled="aiExhausted"
                        @keydown.enter.prevent="if(!isLoading && !aiExhausted) sendMessage()"
                        class="max-h-[100px] w-full resize-none rounded-xl border border-line bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary hide-scrollbar disabled:opacity-50 disabled:cursor-not-allowed"
                        :placeholder="aiExhausted ? 'AI tidak tersedia saat ini...' : 'Tanya SaditaCare...'"></textarea>
                    <button 
                        type="submit" 
                        :disabled="isLoading || inputText.trim() === '' || aiExhausted"
                        class="mb-0.5 flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-xl bg-primary text-white transition-opacity disabled:opacity-50"
                    >
                        <span class="material-symbols-outlined text-[20px]" x-text="isLoading ? 'hourglass_empty' : 'send'">send</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes pulse-soft {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 4px 14px 0 rgba(74, 187, 152, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 4px 20px 4px rgba(74, 187, 152, 0.5);
            }
        }
        .pb-safe { padding-bottom: max(12px, env(safe-area-inset-bottom)); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush

    @push('scripts')
    <script>
        function fabChat() {
            return {
                open: false,
                showGreeting: false,
                messages: [],
                inputText: '',
                isLoading: false,
                aiExhausted: false,
                
                initChat() {
                    setTimeout(() => {
                        if (!localStorage.getItem('saditacare_greeted')) {
                            this.showGreeting = true;
                            localStorage.setItem('saditacare_greeted', 'true');
                            setTimeout(() => this.showGreeting = false, 6000);
                        }
                    }, 1500);

                    // Sync history with main SaditaCare page
                    const stored = localStorage.getItem('sadita_chat_history');
                    if (stored) {
                        try {
                            const parsed = JSON.parse(stored);
                            if (parsed && Array.isArray(parsed.messages)) {
                                this.messages = parsed.messages.slice(-15).map(m => {
                                    // Parse HTML for assistant just like in sendMessage
                                    let html = m.text;
                                    if(m.role === 'assistant' && !html.includes('<br>')) {
                                        html = html.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                                        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                                        html = html.replace(/\n/g, '<br>');
                                        html = html.replace(/\[PRODUCT:([^\]|]+)(?:\|([^\]|]+)\|([^\]]+))?\]/g, 
                                            (match, slug, imageUrl, productName) => {
                                                return `<a href="/toko/produk/${slug}" class="mt-2 block w-max rounded-lg bg-primary/10 px-3 py-1.5 text-[11px] font-bold text-primary">Lihat Produk: ${productName || slug}</a>`;
                                            }
                                        );
                                    }
                                    return {
                                        role: m.role,
                                        text: m.role === 'user' ? m.text : html
                                    };
                                });
                            }
                        } catch(e) {}
                    }
                    
                    this.$watch('messages', () => this.scrollToBottom());
                    this.$watch('isLoading', () => this.scrollToBottom());
                    this.$watch('open', (val) => {
                        if(val) setTimeout(() => this.scrollToBottom(), 100);
                    });
                },

                setInput(text) {
                    this.inputText = text;
                    setTimeout(() => this.sendMessage(), 100);
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const log = document.getElementById('fabChatLog');
                        if (log) log.scrollTop = log.scrollHeight;
                    }, 50);
                },

                async sendMessage() {
                    const text = this.inputText.trim();
                    if (!text || this.isLoading) return;

                    // Push unescaped for user (Alpine x-text escapes it)
                    this.messages.push({ role: 'user', text: text });
                    this.inputText = '';
                    this.isLoading = true;

                    try {
                        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfMeta) throw new Error('CSRF token not found');
                        const csrfToken = csrfMeta.content;
                        
                        const historyForApi = this.messages.slice(0, -1).map(m => ({
                            role: m.role,
                            text: m.role === 'assistant' ? m.text.replace(/<br>/g, '\n').replace(/<strong>(.*?)<\/strong>/g, '**$1**') : m.text // Rough unparse
                        }));
                        
                        const res = await fetch('/ai/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: text, history: historyForApi })
                        });

                        if (res.status === 503) {
                            const errData = await res.json().catch(() => null);
                            if (errData && errData.exhausted) {
                                this.messages.push({ role: 'assistant', text: errData.reply || 'Layanan AI sedang tidak tersedia.' });
                                this.isLoading = false;
                                this.inputText = '';
                                this.aiExhausted = true;
                                return;
                            }
                        }
                        if (!res.ok) throw new Error('Network response was not ok');
                        const data = await res.json();
                        
                        let replyHtml = data.reply || 'Maaf, terjadi kesalahan.';
                        // Format HTML for display
                        replyHtml = replyHtml.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                        replyHtml = replyHtml.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        replyHtml = replyHtml.replace(/\n/g, '<br>');
                        replyHtml = replyHtml.replace(/\[PRODUCT:([^\]|]+)(?:\|([^\]|]+)\|([^\]]+))?\]/g, 
                            (match, slug, imageUrl, productName) => {
                                return `<a href="/toko/produk/${slug}" class="mt-2 block w-max rounded-lg bg-primary/10 px-3 py-1.5 text-[11px] font-bold text-primary">Lihat Produk: ${productName || slug}</a>`;
                            }
                        );
                        
                        this.messages.push({ role: 'assistant', text: replyHtml });
                        
                        // Sync with main chat history
                        localStorage.setItem('sadita_chat_history', JSON.stringify({
                            updated_at: Date.now(),
                            messages: this.messages.slice(-20)
                        }));
                    } catch (error) {
                        this.messages.push({ role: 'assistant', text: 'Maaf, gagal terhubung ke server. Silakan coba lagi.' });
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
    @endpush
@endif
