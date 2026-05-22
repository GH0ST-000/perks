@php
    $isEdit = $editing ?? false;
@endphp

<x-partner-layout :partner="$partner">
    <div class="max-w-2xl mx-auto animate-fade-in relative" x-data="partnerOfferForm()">

        @if($errors->any())
            <div class="mb-4 md:mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <a href="{{ route('partner.offers') }}"
           @click="requestLeave($event, '{{ route('partner.offers') }}')"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 mb-4 md:mb-6 transition-colors group">
            <div class="p-2 bg-white dark:bg-slate-900 rounded-lg md:rounded-xl shadow-sm group-hover:scale-105 transition-transform">
                <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
            </div>
            <span class="font-bold text-xs md:text-sm">უკან</span>
        </a>

        <div class="bg-white dark:bg-slate-900 p-5 md:p-8 rounded-2xl md:rounded-[32px] shadow-sm border border-slate-50 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-6 md:mb-8">
                <div class="p-2 md:p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl md:rounded-2xl">
                    <i data-lucide="{{ $isEdit ? 'pencil' : 'plus' }}" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">
                        {{ $isEdit ? 'შეთავაზების რედაქტირება' : 'ახალი შეთავაზება' }}
                    </h2>
                    <p class="text-[10px] md:text-xs text-slate-400 font-semibold">
                        {{ $isEdit ? 'შეცვალეთ შეთავაზების დეტალები' : 'შეავსეთ დეტალები ვერიფიკაციისთვის' }}
                    </p>
                </div>
            </div>

            <form x-ref="offerForm"
                  action="{{ $formAction }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-4 md:space-y-6"
                  @submit="allowLeave()">
                @csrf
                @if($isEdit)
                    @method('PATCH')
                @endif

                @include('partner.partials.offer-form-fields')

                <button type="submit" class="partner-btn-primary w-full font-bold py-3.5 md:py-4 rounded-xl md:rounded-2xl transition-all shadow-lg flex items-center justify-center gap-2 min-h-[52px]">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    <span>{{ $isEdit ? 'ცვლილებების შენახვა' : 'გაგზავნა ადმინისტრატორთან' }}</span>
                </button>
            </form>
        </div>

        {{-- Custom unsaved changes modal --}}
        <div x-show="showLeaveModal"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 partner-leave-overlay"
             @click.self="cancelLeave()"
             @keydown.escape.window="cancelLeave()">
            <div x-show="showLeaveModal"
                 x-transition:enter="transition ease-out duration-200 delay-75"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="partner-leave-panel w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl md:rounded-[28px] p-6 md:p-8 border border-slate-100 dark:border-slate-800 text-center"
                 @click.stop>
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-500">
                    <i data-lucide="alert-triangle" class="w-7 h-7"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">შეუნახავი ცვლილებები</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                    თქვენ მიერ შეყვანილი მონაცემები დაიკარგება. გსურთ გასვლა გვერდიდან?
                </p>
                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button type="button"
                            @click="cancelLeave()"
                            class="flex-1 py-3 rounded-xl font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors min-h-[48px]">
                        გაგრძელება
                    </button>
                    <button type="button"
                            @click="confirmLeave()"
                            class="flex-1 py-3 rounded-xl font-bold text-sm partner-btn-finish min-h-[48px]">
                        <span x-text="leaveAction === 'reload' ? 'განახლება' : 'გასვლა'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('head')
        <style>[x-cloak] { display: none !important; }</style>
    @endpush

    @push('scripts')
        <script>
            function partnerOfferForm() {
                return {
                    editingId: @json($isEdit ? ($offerId ?? null) : null),
                    dirty: false,
                    showLeaveModal: false,
                    pendingLeaveUrl: null,
                    leaveAction: 'navigate',
                    imageName: '',
                    imagePreview: null,
                    existingImage: @json($existingImageUrl ?? null),
                    removeImage: false,
                    form: @json($initialForm),
                    _navClickHandler: null,
                    _keyHandler: null,

                    get hasImagePreview() {
                        return !!(this.imagePreview || this.existingImage);
                    },

                    init() {
                        this.$refs.offerForm?.addEventListener('input', () => this.markDirty());
                        this.$refs.offerForm?.addEventListener('change', () => this.markDirty());

                        this._navClickHandler = (event) => this.onDocumentClick(event);
                        this._keyHandler = (event) => this.onDocumentKeydown(event);

                        document.getElementById('partner-app')?.addEventListener('click', this._navClickHandler, true);
                        window.addEventListener('keydown', this._keyHandler, true);

                        this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                    },

                    destroy() {
                        document.getElementById('partner-app')?.removeEventListener('click', this._navClickHandler, true);
                        window.removeEventListener('keydown', this._keyHandler, true);
                    },

                    markDirty() {
                        this.dirty = true;
                    },

                    allowLeave() {
                        this.dirty = false;
                    },

                    openLeaveModal(action, url = null) {
                        this.leaveAction = action;
                        this.pendingLeaveUrl = url;
                        this.showLeaveModal = true;
                        this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                    },

                    cancelLeave() {
                        this.showLeaveModal = false;
                        this.pendingLeaveUrl = null;
                    },

                    confirmLeave() {
                        this.allowLeave();
                        this.showLeaveModal = false;

                        if (this.leaveAction === 'reload') {
                            window.location.reload();
                            return;
                        }

                        if (this.pendingLeaveUrl) {
                            window.location.href = this.pendingLeaveUrl;
                        }
                    },

                    requestLeave(event, url) {
                        if (!this.dirty) {
                            return;
                        }
                        event.preventDefault();
                        this.openLeaveModal('navigate', url);
                    },

                    onDocumentClick(event) {
                        if (!this.dirty || this.showLeaveModal) {
                            return;
                        }

                        const link = event.target.closest('a[href]');
                        if (!link || link.target === '_blank' || link.hasAttribute('download')) {
                            return;
                        }

                        if (this.$refs.offerForm?.contains(link)) {
                            return;
                        }

                        const href = link.getAttribute('href');
                        if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
                            return;
                        }

                        try {
                            const target = new URL(href, window.location.origin);
                            if (target.origin !== window.location.origin) {
                                return;
                            }
                            if (target.pathname === window.location.pathname && target.search === window.location.search) {
                                return;
                            }
                        } catch {
                            return;
                        }

                        event.preventDefault();
                        event.stopPropagation();
                        this.openLeaveModal('navigate', link.href);
                    },

                    onDocumentKeydown(event) {
                        if (!this.dirty || this.showLeaveModal) {
                            return;
                        }

                        const isReload = event.key === 'F5'
                            || ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'r');

                        if (isReload) {
                            event.preventDefault();
                            this.openLeaveModal('reload');
                        }
                    },

                    onImageSelect(event) {
                        const file = event.target.files?.[0];
                        if (!file) {
                            return;
                        }
                        this.markDirty();
                        this.revokeImagePreview();
                        this.imagePreview = URL.createObjectURL(file);
                        this.existingImage = null;
                        this.removeImage = false;
                        this.imageName = file.name;
                        this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                    },

                    clearImage() {
                        this.markDirty();
                        this.revokeImagePreview();
                        this.imagePreview = null;
                        this.imageName = '';
                        if (this.$refs.imageInput) {
                            this.$refs.imageInput.value = '';
                        }
                        if (this.$refs.imageInputEmpty) {
                            this.$refs.imageInputEmpty.value = '';
                        }
                        if (this.editingId && this.existingImage) {
                            this.removeImage = true;
                            this.existingImage = null;
                        }
                    },

                    revokeImagePreview() {
                        if (this.imagePreview?.startsWith('blob:')) {
                            URL.revokeObjectURL(this.imagePreview);
                        }
                    },
                };
            }

            document.addEventListener('alpine:initialized', () => {
                if (window.lucide) lucide.createIcons();
            });
        </script>
    @endpush
</x-partner-layout>
