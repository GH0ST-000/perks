<!-- Featured Offers Section -->
<section id="offers" class="py-20 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold dark:text-white mb-2">პრემიუმ შეთავაზებები</h2>
                <p class="text-gray-500">ექსკლუზიური გარიგებები ჩვენი ყველაზე მაღალი რეიტინგის პარტნიორებისგან</p>
            </div>
            <a href="{{ route('offers.index') }}" class="text-primary-600 font-bold flex items-center gap-2 hover:translate-x-1 transition-transform">
                <span>ყველას ნახვა</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($offers as $offer)
                <!-- Offer Card -->
                <a href="{{ route('offers.show', $offer) }}" class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        @if($offer->image)
                            <img src="{{ Storage::url($offer->image) }}" alt="{{ $offer->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=300&fit=crop" alt="{{ $offer->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80"></div>

                        @if($offer->is_premium)
                            <div class="absolute top-3 left-3 bg-amber-400 text-amber-950 text-xs font-black uppercase px-2 py-1 rounded shadow-sm flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <span>პრემიუმ</span>
                            </div>
                        @endif

                        <div class="absolute bottom-3 left-3 right-3 text-white">
                            {{-- Partner name and rating would go here if we had it --}}
                            @if($offer->partner)
                                <div class="font-bold text-lg leading-tight drop-shadow-md">{{ $offer->partner->name }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col relative">
                        @if($offer->discount > 0)
                            <div class="absolute -top-6 right-4 bg-primary-700 text-white w-12 h-12 flex flex-col items-center justify-center rounded-xl shadow-lg shadow-primary-900/20 border-2 border-white dark:border-gray-800">
                                <span class="text-sm font-bold">{{ number_format($offer->discount, 0) }}%</span>
                                <span class="text-xs uppercase font-bold opacity-100">OFF</span>
                            </div>
                        @endif

                        @if($offer->header_text)
                            <div class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                                {{ $offer->header_text }}
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $offer->name }}</h3>

                        @if($offer->description)
                            <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-2 mb-4 flex-1 leading-relaxed">
                                {{ $offer->description }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                            @if($offer->day_left > 0)
                                <div class="flex items-center gap-1 text-primary-700 dark:text-primary-400 font-bold text-sm">
                                    <div class="w-5 h-5 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-primary-600 dark:bg-primary-400"></div>
                                    </div>
                                    {{ $offer->day_left }} დღე
                                </div>
                            @else
                                <div class="text-xs text-gray-500">-</div>
                            @endif

                            <span class="text-gray-600 group-hover:text-primary-700 dark:text-gray-400 dark:group-hover:text-white transition-colors flex items-center gap-1 text-xs font-bold uppercase">
                                <span>დეტალურად</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <!-- No offers message -->
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">პრემიუმ შეთავაზებები არ მოიძებნა</h3>
                    <p class="text-gray-500">მალე დაბრუნდით ექსკლუზიური გარიგებებისთვის!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
