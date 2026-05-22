@php
    $statusLabels = [
        'approved' => ['აქტიური', 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border-green-100 dark:border-green-900/30'],
        'pending' => ['მოლოდინში', 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 border-yellow-100 dark:border-yellow-900/30'],
        'rejected' => ['უარყოფილი', 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-900/30'],
        'expired' => ['ვადაგასული', 'bg-slate-100 dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700'],
    ];
@endphp

<x-partner-layout :partner="$partner">
    <div x-data="partnerOffers()" class="animate-fade-in">

        @if(session('success'))
            <div class="mb-4 md:mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- List view (server-rendered, always in DOM) --}}
        <div x-show="!showForm">
            <div class="space-y-6 md:space-y-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">აქტიური შეთავაზებები</h2>
                        <p class="text-xs md:text-sm text-slate-400 font-medium">თქვენი მიმდინარე აქციები</p>
                    </div>
                    <button type="button" @click="openCreate()"
                        class="partner-btn-finish w-full md:w-auto font-bold px-5 py-3 rounded-xl flex items-center justify-center gap-2 shadow-md active:scale-95 min-h-[48px]">
                        <i data-lucide="plus" class="w-[18px] h-[18px]"></i>
                        <span class="text-sm">ახალი შეთავაზება</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach($offers as $offer)
                        @php
                            [$statusText, $statusClass] = $statusLabels[$offer['status']] ?? $statusLabels['pending'];
                        @endphp
                        <div class="group bg-white dark:bg-slate-900 rounded-2xl md:rounded-[32px] overflow-hidden shadow-sm border border-slate-50 dark:border-slate-800 flex flex-col transition-all duration-300 hover:shadow-xl">
                            <div class="relative h-40 md:h-48 overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($offer['image'])
                                    <img src="{{ $offer['image'] }}" alt="{{ $offer['title'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-12 h-12"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                                <div class="absolute top-3 left-3">
                                    <span class="flex items-center gap-1.5 px-2 py-0.5 text-[9px] font-black uppercase tracking-widest rounded-full border {{ $statusClass }}">{{ $statusText }}</span>
                                </div>
                                @if($offer['can_edit'] || $offer['can_delete'])
                                    <div class="absolute top-3 right-3 flex items-center gap-1.5 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        @if($offer['can_edit'])
                                            <button type="button"
                                                @click="openEdit(@js($offer))"
                                                class="p-1.5 bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 rounded-lg shadow-sm hover:text-blue-600 backdrop-blur-sm">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                        @if($offer['can_delete'])
                                            <form action="{{ route('partner.offers.destroy', $offer['id']) }}" method="POST" class="inline"
                                                onsubmit="return confirm('ნამდვილად გსურთ ამ შეთავაზების წაშლა?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 rounded-lg shadow-sm hover:text-red-500 backdrop-blur-sm">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                <div class="absolute bottom-3 left-3 right-3 flex justify-between items-end text-white">
                                    <div>
                                        <span class="text-[8px] font-black uppercase tracking-widest opacity-80">ფასდაკლება</span>
                                        <div class="text-xl md:text-2xl font-black">-{{ $offer['discount'] }}%</div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[8px] font-black uppercase tracking-widest opacity-80">P-Coin</span>
                                        <div class="flex items-center justify-end gap-1 font-black text-base md:text-lg">
                                            <i data-lucide="coins" class="w-3.5 h-3.5 text-yellow-400"></i>
                                            {{ $offer['p_coins'] }}P
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 md:p-6 flex-1 flex flex-col">
                                @if(!empty($offer['header_text']))
                                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-1">{{ $offer['header_text'] }}</p>
                                @endif
                                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-1 md:mb-2 truncate">{{ $offer['title'] }}</h3>
                                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-4 flex-1 line-clamp-2">{{ $offer['description'] }}</p>
                                <div class="flex items-center justify-between pt-3 md:pt-4 border-t border-slate-50 dark:border-slate-800">
                                    <div>
                                        <button type="button" @click="openDetail(@js($offer))"
                                            class="text-[10px] md:text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline text-left">
                                            დეტალურად
                                        </button>
                                        <span class="text-[8px] md:text-[9px] font-bold text-slate-400 dark:text-slate-500 flex items-center gap-1 mt-1">
                                            <i data-lucide="clock" class="w-2.5 h-2.5"></i>
                                            {{ $offer['period'] }}
                                        </span>
                                    </div>
                                    <span class="text-[8px] md:text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-widest">ID: {{ $offer['id'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button type="button" @click="openCreate()"
                        class="border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-2xl md:rounded-[32px] p-8 md:p-12 flex flex-col items-center justify-center text-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all min-h-[150px] md:min-h-[350px] w-full">
                        <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                            <i data-lucide="plus" class="w-6 h-6 md:w-8 md:h-8"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-400 text-sm md:text-base">დაამატე</h3>
                            <p class="text-[10px] md:text-xs text-slate-300">მოიზიდე მეტი ვიზიტორი</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- Create / edit form --}}
        <div x-show="showForm" x-cloak class="max-w-2xl mx-auto">
            <button type="button" @click="closeForm()" class="flex items-center gap-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 mb-4 md:mb-6 transition-colors group">
                <div class="p-2 bg-white dark:bg-slate-900 rounded-lg md:rounded-xl shadow-sm group-hover:scale-105 transition-transform">
                    <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
                </div>
                <span class="font-bold text-xs md:text-sm">უკან</span>
            </button>

            <div class="bg-white dark:bg-slate-900 p-5 md:p-8 rounded-2xl md:rounded-[32px] shadow-sm border border-slate-50 dark:border-slate-800">
                <div class="flex items-center gap-3 mb-6 md:mb-8">
                    <div class="p-2 md:p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl md:rounded-2xl">
                        <i data-lucide="plus" class="w-5 h-5" x-show="!editingId"></i>
                        <i data-lucide="pencil" class="w-5 h-5" x-show="editingId"></i>
                    </div>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white" x-text="editingId ? 'შეთავაზების რედაქტირება' : 'ახალი შეთავაზება'"></h2>
                        <p class="text-[10px] md:text-xs text-slate-400 font-semibold" x-text="editingId ? 'შეცვალეთ შეთავაზების დეტალები' : 'შეავსეთ დეტალები ვერიფიკაციისთვის'"></p>
                    </div>
                </div>

                {{-- New offer: POST only, no _method spoofing --}}
                <form x-show="showForm && !editingId"
                      action="{{ route('partner.offers.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-4 md:space-y-6">
                    @csrf
                    @include('partner.partials.offer-form-fields')
                    <button type="submit" class="partner-btn-primary w-full font-bold py-3.5 md:py-4 rounded-xl md:rounded-2xl transition-all shadow-lg flex items-center justify-center gap-2 min-h-[52px]">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        <span>გაგზავნა ადმინისტრატორთან</span>
                    </button>
                </form>

                {{-- Edit pending offer: PATCH to /partner/offers/{id} --}}
                <form x-show="showForm && editingId"
                      :action="editUrl"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-4 md:space-y-6">
                    @csrf
                    @method('PATCH')
                    @include('partner.partials.offer-form-fields')
                    <button type="submit" class="partner-btn-primary w-full font-bold py-3.5 md:py-4 rounded-xl md:rounded-2xl transition-all shadow-lg flex items-center justify-center gap-2 min-h-[52px]">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        <span>ცვლილებების შენახვა</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Detail modal --}}
        <div x-show="selected" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="selected = null">
            <template x-if="selected">
                <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl" @click.stop>
                    <div class="relative h-56 md:h-64 bg-slate-200 dark:bg-slate-800">
                        <img x-show="selected?.image" :src="selected?.image" :alt="selected?.title" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <button type="button" @click="selected = null" class="absolute top-4 right-4 p-2 bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-md">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span x-html="selected ? statusBadge(selected.status) : ''"></span>
                                <span class="px-2 py-0.5 bg-blue-500 text-white text-[9px] font-black uppercase tracking-widest rounded-full" x-text="selected?.period"></span>
                            </div>
                            <p x-show="selected?.header_text" class="text-[10px] font-black uppercase tracking-widest text-blue-300 mb-1" x-text="selected?.header_text"></p>
                            <h3 class="text-xl md:text-2xl font-black text-white" x-text="selected?.title"></h3>
                        </div>
                    </div>
                    <div class="p-6 md:p-8 space-y-4">
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" x-text="selected?.description"></p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 text-center">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">ფასდაკლება</div>
                                <div class="text-2xl font-black text-blue-600 dark:text-blue-400" x-text="selected ? '-' + selected.discount + '%' : ''"></div>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 text-center">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">P-Coin</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white" x-text="selected ? selected.p_coins + 'P' : ''"></div>
                            </div>
                        </div>
                        <p x-show="selected?.status === 'rejected' && selected?.rejection_reason"
                           class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-3 rounded-xl"
                           x-text="selected?.rejection_reason"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @push('head')
        <style>
            [x-cloak] { display: none !important; }
            /* Show list before Alpine boots */
            .partner-offers-fallback .partner-offers-list { display: block; }
        </style>
    @endpush

    @push('scripts')
        <script>
            function partnerOffers() {
                return {
                    showForm: {{ request()->boolean('create') ? 'true' : 'false' }},
                    editingId: null,
                    selected: null,
                    imageName: '',
                    form: { title: '', header_text: '', description: '', discount: '20', p_coins: '0', period: '1 თვე' },
                    updateUrlTemplate: @js(route('partner.offers.update', ['offer' => '__ID__'])),

                    get editUrl() {
                        return this.editingId
                            ? this.updateUrlTemplate.replace('__ID__', String(this.editingId))
                            : '#';
                    },

                    openCreate() {
                        this.editingId = null;
                        this.form = { title: '', header_text: '', description: '', discount: '20', p_coins: '0', period: '1 თვე' };
                        this.imageName = '';
                        this.showForm = true;
                        this.refreshIcons();
                    },

                    openEdit(offer) {
                        this.editingId = offer.id;
                        this.form = {
                            title: offer.title,
                            header_text: offer.header_text || '',
                            description: offer.description,
                            discount: String(offer.discount),
                            p_coins: String(offer.p_coins ?? 0),
                            period: offer.period,
                        };
                        this.imageName = '';
                        this.showForm = true;
                        this.refreshIcons();
                    },

                    openDetail(offer) {
                        this.selected = offer;
                        this.refreshIcons();
                    },

                    closeForm() {
                        this.showForm = false;
                        this.editingId = null;
                        this.refreshIcons();
                    },

                    statusBadge(status) {
                        const map = {
                            approved: '<span class="flex items-center gap-1.5 px-2 py-0.5 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-green-100 dark:border-green-900/30">აქტიური</span>',
                            pending: '<span class="flex items-center gap-1.5 px-2 py-0.5 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-yellow-100 dark:border-yellow-900/30">მოლოდინში</span>',
                            rejected: '<span class="flex items-center gap-1.5 px-2 py-0.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-red-100 dark:border-red-900/30">უარყოფილი</span>',
                            expired: '<span class="flex items-center gap-1.5 px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[9px] font-black uppercase tracking-widest rounded-full border border-slate-200">ვადაგასული</span>',
                        };
                        return map[status] || map.pending;
                    },

                    refreshIcons() {
                        this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                    },
                };
            }

            document.addEventListener('alpine:initialized', () => {
                if (window.lucide) lucide.createIcons();
            });
        </script>
    @endpush
</x-partner-layout>
