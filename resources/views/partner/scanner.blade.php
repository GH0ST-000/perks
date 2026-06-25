<x-partner-layout :partner="$partner">
    <div class="flex justify-center items-start md:pt-8 px-2">
        <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl md:rounded-[32px] overflow-hidden shadow-sm border border-slate-50 dark:border-slate-800"
             x-data="partnerScanner()" x-cloak>

            {{-- Step 1: ID / phone entry --}}
            <div x-show="step === 'id_entry'" x-transition.opacity>
                <div class="bg-blue-600 dark:bg-blue-700 p-6 md:p-8 flex flex-col items-center justify-center text-white text-center">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 rounded-xl md:rounded-2xl flex items-center justify-center mb-3 md:mb-4 backdrop-blur-sm border border-white/10">
                        <i data-lucide="scan-line" class="w-7 h-7 md:w-8 md:h-8 animate-pulse"></i>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold mb-1">სკანირება</h2>
                    <p class="text-white/80 text-xs md:text-sm">შეიყვანეთ ID ან ტელეფონი</p>
                </div>

                <div class="p-5 md:p-8 space-y-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none pl-4 md:pl-5 pr-2 transition-colors duration-300"
                             :class="error ? 'text-red-400' : (valid ? 'text-green-500' : 'text-slate-400 dark:text-slate-500')">
                            <i data-lucide="user-plus" class="w-[18px] h-[18px] shrink-0"></i>
                        </div>
                        <input type="text"
                               x-model="query"
                               @input="validate()"
                               placeholder="P-8821 / 599XXXXXX"
                               class="w-full pl-12 md:pl-14 pr-10 md:pr-12 py-3.5 md:py-4 bg-slate-50 dark:bg-slate-800 border-2 rounded-xl md:rounded-2xl text-slate-900 dark:text-slate-100 font-medium outline-none text-sm md:text-base transition-all duration-300"
                               :class="error ? 'border-red-400' : (valid ? 'border-green-500' : 'border-slate-100 dark:border-slate-700 focus:border-blue-600')">
                        <div x-show="error" class="absolute inset-y-0 right-0 flex items-center pr-4 md:pr-5 text-red-500">
                            <i data-lucide="circle-alert" class="w-[18px] h-[18px] shrink-0"></i>
                        </div>
                        <div x-show="valid && !error" class="absolute inset-y-0 right-0 flex items-center pr-4 md:pr-5 text-green-500">
                            <i data-lucide="circle-check" class="w-[18px] h-[18px] shrink-0"></i>
                        </div>
                    </div>

                    <p x-show="error" x-text="error" class="text-[10px] font-bold text-red-500 px-1"></p>

                    <button type="button"
                            @click="search()"
                            :disabled="!valid"
                            class="partner-btn-primary w-full font-bold py-3.5 md:py-4 rounded-xl md:rounded-2xl transition-all shadow-lg flex items-center justify-center gap-2 active:scale-[0.98] min-h-[52px]"
                            :class="!valid ? 'partner-btn-primary--disabled' : (loading ? 'opacity-90 cursor-wait' : '')">
                        <span x-show="loading" class="w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin shrink-0" aria-hidden="true"></span>
                        <span x-text="loading ? 'მუშავდება...' : 'ძებნა'"></span>
                    </button>
                </div>
            </div>

            {{-- Step 2: SMS verification --}}
            <div x-show="step === 'sms_verification'" x-transition.opacity>
                <div class="bg-blue-600 dark:bg-blue-700 p-6 md:p-8 flex flex-col items-center justify-center text-white text-center relative">
                    <button type="button" @click="goBack()" class="absolute top-4 left-4 p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
                    </button>
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 rounded-xl md:rounded-2xl flex items-center justify-center mb-3 md:mb-4 backdrop-blur-sm border border-white/10">
                        <i data-lucide="message-square" class="w-7 h-7 md:w-8 md:h-8"></i>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold mb-1">ვერიფიკაცია</h2>
                    <p class="text-white/80 text-xs truncate max-w-full px-4" x-show="customerName">
                        <span class="font-black" x-text="customerName"></span>
                        <span x-show="offerName" class="block text-white/70 text-[10px] mt-0.5" x-text="offerName"></span>
                    </p>
                    <p class="text-white/70 text-[10px] mt-1 px-4">SMS კოდი გაიგზავნა მომხმარებლის ტელეფონზე</p>
                </div>

                <div class="p-5 md:p-8 space-y-5">
                    <div class="flex justify-center gap-2 md:gap-3">
                        @foreach([0, 1, 2, 3] as $i)
                            <input type="text"
                                   maxlength="1"
                                   inputmode="numeric"
                                   x-ref="code{{ $i }}"
                                   class="partner-code-input w-11 h-14 md:w-14 md:h-16 text-center text-xl md:text-2xl font-black rounded-xl md:rounded-2xl border-2 transition-all outline-none
                                          bg-slate-50 text-slate-900 border-slate-200
                                          dark:bg-slate-800 dark:text-white dark:border-slate-700"
                                   :class="codeError
                                       ? 'border-red-400 !bg-red-50 !text-slate-900 dark:!bg-red-950/40 dark:!text-white'
                                       : (code[{{ $i }}] ? 'border-blue-600 !bg-blue-50 !text-slate-900 dark:!bg-blue-950/50 dark:!border-blue-500 dark:!text-white' : '')"
                                   x-model="code[{{ $i }}]"
                                   @input="onCodeInput($event, {{ $i }})"
                                   @keydown="onCodeKeydown($event, {{ $i }})">
                        @endforeach
                    </div>

                    <p x-show="codeError" x-text="codeError" class="text-[10px] font-bold text-red-500 text-center"></p>

                    <button type="button"
                            @click="confirm()"
                            class="partner-btn-primary w-full font-bold py-3.5 md:py-4 rounded-xl md:rounded-2xl transition-all shadow-lg flex items-center justify-center gap-2 min-h-[52px]"
                            :class="loading ? 'opacity-90 cursor-wait' : ''">
                        <span x-show="loading" class="w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin shrink-0" aria-hidden="true"></span>
                        <span x-text="loading ? 'მუშავდება...' : 'დადასტურება'"></span>
                    </button>

                    <div class="text-center">
                        <button type="button"
                                @click="resend()"
                                :disabled="resendSeconds > 0"
                                class="text-[9px] font-bold uppercase tracking-widest transition-colors"
                                :class="resendSeconds > 0 ? 'text-slate-300' : 'text-blue-600 dark:text-blue-400'"
                                x-text="resendSeconds > 0 ? `ხელახლა (${resendSeconds}წმ)` : 'ხელახლა გაგზავნა'">
                        </button>
                    </div>
                </div>
            </div>

            {{-- Step 3: Success --}}
            <div x-show="step === 'success'" x-transition.opacity class="p-8 md:p-12 flex flex-col items-center justify-center text-center partner-animate-pop">
                <div class="relative mb-5 md:mb-6">
                    <div class="w-20 h-20 md:w-24 md:h-24 bg-green-50 dark:bg-green-900/20 text-green-500 dark:text-green-400 rounded-full flex items-center justify-center shadow-inner ring-4 md:ring-8 ring-green-100 dark:ring-green-950/30">
                        <i data-lucide="circle-check" class="w-10 h-10 md:w-14 md:h-14" stroke-width="2.5"></i>
                    </div>
                    <!-- <div class="absolute -top-1 -right-1 w-8 h-8 md:w-10 md:h-10 bg-yellow-400 text-white rounded-full flex items-center justify-center shadow-lg border-2 md:border-4 border-white dark:border-slate-900"> -->
                        <!-- <i data-lucide="sparkles" class="w-4 h-4 md:w-5 md:h-5"></i> -->
                    <!-- </div> -->
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 dark:text-slate-100 mb-1">წარმატებული!</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mb-2 max-w-[260px]" x-text="successMessage">დადასტურდა</p>
                <p x-show="pCoinsAwarded > 0" class="text-amber-600 dark:text-amber-400 text-xs font-bold mb-6 md:mb-8" x-text="'+' + pCoinsAwarded + ' P-coins მომხმარებელს'"></p>
                <button type="button"
                        @click="reset()"
                        class="partner-btn-finish w-full md:w-auto px-10 md:px-12 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold transition-all shadow-lg">
                    დასრულება
                </button>
            </div>
        </div>
    </div>

    @push('head')
        <style>[x-cloak] { display: none !important; }</style>
    @endpush

    @push('scripts')
        @vite(['resources/js/app.js'])
        <script>
            function partnerScanner() {
                return {
                    step: 'id_entry',
                    query: '',
                    valid: false,
                    error: null,
                    loading: false,
                    token: null,
                    customerName: '',
                    offerName: '',
                    successMessage: 'დადასტურდა',
                    pCoinsAwarded: 0,
                    code: ['', '', '', ''],
                    codeError: null,
                    resendSeconds: 30,
                    resendTimer: null,
                    searchUrl: @js(route('partner.scanner.search')),
                    verifyUrl: @js(route('partner.scanner.verify')),

                    csrf() {
                        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                    },

                    validate() {
                        const value = this.query.trim();
                        if (!value) {
                            this.error = null;
                            this.valid = false;
                            return;
                        }
                        const idOk = /^P-\d+$/i.test(value);
                        const phoneOk = /^\d{9,15}$/.test(value);
                        if (idOk || phoneOk) {
                            this.error = null;
                            this.valid = true;
                            return;
                        }
                        if (value.toLowerCase().startsWith('p') && !value.includes('-')) {
                            this.error = 'ID ფორმატი: P-XXXX';
                        } else {
                            this.error = 'არასწორი ფორმატი';
                        }
                        this.valid = false;
                    },

                    async search() {
                        if (!this.valid || this.loading) return;
                        this.loading = true;
                        this.error = null;
                        try {
                            const res = await fetch(this.searchUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': this.csrf(),
                                },
                                body: JSON.stringify({ query: this.query.trim() }),
                            });
                            const data = await res.json();
                            if (!res.ok) {
                                this.error = data.message || 'ძებნა ვერ მოხერხდა';
                                return;
                            }
                            this.token = data.token;
                            this.customerName = data.user_name;
                            this.offerName = data.offer_name;
                            this.step = 'sms_verification';
                            this.code = ['', '', '', ''];
                            this.startResendTimer();
                            this.$nextTick(() => {
                                lucide.createIcons();
                                this.$refs.code0?.focus?.();
                            });
                        } catch (e) {
                            this.error = 'ქსელის შეცდომა. სცადეთ ხელახლა.';
                        } finally {
                            this.loading = false;
                        }
                    },

                    goBack() {
                        this.step = 'id_entry';
                        this.token = null;
                        this.customerName = '';
                        this.offerName = '';
                        this.stopResendTimer();
                        this.code = ['', '', '', ''];
                        this.codeError = null;
                        this.$nextTick(() => lucide.createIcons());
                    },

                    onCodeInput(event, index) {
                        const val = event.target.value.replace(/\D/g, '').slice(-1);
                        this.code[index] = val;
                        event.target.value = val;
                        this.codeError = null;
                        if (val && index < 3) {
                            const next = this.$refs['code' + (index + 1)];
                            next?.focus?.();
                        }
                    },

                    onCodeKeydown(event, index) {
                        if (event.key === 'Backspace' && !this.code[index] && index > 0) {
                            const prev = this.$refs['code' + (index - 1)];
                            prev?.focus?.();
                        }
                    },

                    async confirm() {
                        if (this.loading || !this.token) return;
                        if (this.code.join('').length < 4) {
                            this.codeError = 'შეიყვანეთ კოდი';
                            return;
                        }
                        this.loading = true;
                        this.codeError = null;
                        try {
                            const res = await fetch(this.verifyUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': this.csrf(),
                                },
                                body: JSON.stringify({
                                    token: this.token,
                                    code: this.code.join(''),
                                }),
                            });
                            const data = await res.json();
                            if (!res.ok) {
                                this.codeError = data.message || 'ვერიფიკაცია ვერ მოხერხდა';
                                return;
                            }
                            this.successMessage = `${data.user_name} — ${data.offer_name}`;
                            this.pCoinsAwarded = data.p_coins_awarded || 0;
                            this.step = 'success';
                            this.stopResendTimer();
                            this.$nextTick(() => lucide.createIcons());
                        } catch (e) {
                            this.codeError = 'ქსელის შეცდომა. სცადეთ ხელახლა.';
                        } finally {
                            this.loading = false;
                        }
                    },

                    async resend() {
                        if (this.resendSeconds > 0 || this.loading) return;
                        await this.search();
                    },

                    startResendTimer() {
                        this.stopResendTimer();
                        this.resendSeconds = 30;
                        this.resendTimer = setInterval(() => {
                            if (this.resendSeconds > 0) {
                                this.resendSeconds--;
                            } else {
                                this.stopResendTimer();
                            }
                        }, 1000);
                    },

                    stopResendTimer() {
                        if (this.resendTimer) {
                            clearInterval(this.resendTimer);
                            this.resendTimer = null;
                        }
                    },

                    reset() {
                        this.step = 'id_entry';
                        this.query = '';
                        this.valid = false;
                        this.error = null;
                        this.token = null;
                        this.customerName = '';
                        this.offerName = '';
                        this.successMessage = 'დადასტურდა';
                        this.pCoinsAwarded = 0;
                        this.code = ['', '', '', ''];
                        this.codeError = null;
                        this.stopResendTimer();
                        this.$nextTick(() => lucide.createIcons());
                    },
                };
            }

            document.addEventListener('alpine:initialized', () => lucide.createIcons());
        </script>
    @endpush
</x-partner-layout>
