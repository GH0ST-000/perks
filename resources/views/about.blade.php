@extends('layouts.landing')

@section('title', 'ჩვენი ისტორია - Perks')

@section('content')
@include('components.landing.header')

<main class="bg-white text-gray-900 dark:bg-slate-950 dark:text-white transition-colors duration-300">
<div class="animate-fade-in pb-0">
    <!-- Story hero -->
    <section class="relative pt-12 pb-16 md:pt-16 md:pb-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-950"></div>
        <div class="max-w-3xl mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">ჩვენი ისტორია</h1>
            <div class="h-1 w-24 mx-auto rounded-full bg-gradient-to-r from-primary-500 to-purple-500 mb-10"></div>
            <div class="rounded-3xl bg-white border border-gray-200 shadow-sm dark:bg-slate-900/80 dark:border-slate-700/80 dark:shadow-none p-6 md:p-10 text-left space-y-6 text-gray-600 dark:text-slate-300 leading-relaxed text-base md:text-lg">
                <p>
                    კომპანია Perks 2026 წელს შეიქმნა კონკრეტული მიზნით — შეგვექმნა სივრთე, სადაც დამსაქმებელიც და დასაქმებულიც ერთნაირად მოგებული რჩება. ჩვენ დავინახეთ, რომ თანამედროვე სამყაროში მხოლოდ სტანდარტული კორპორატიული პაკეტები აღარ არის საკმარისი — ადამიანებს სურთ რეალური, მოქნილი სისტემა, რომელიც მოტივაციასა და კმაყოფილებას ზრდის სამუშაო სივრცეში.
                </p>
                <p>
                    ჩვენ ვეხმარებით კომპანიებს, მარტივად შექმნან ისეთი სამუშაო გარემო და კულტურა, საიდანაც საუკეთესო ტალანტებს წასვლა არ მოუნდებათ. Perks ათავისუფლებს HR მენეჯერებს რუტინული ბიუროკრატიისგან და აძლევს მათ ძალას, განავითარონ გუნდის კეთილდღეობის სტრატეგია ციფრული ინსტრუმენტებით.
                </p>
                <p>
                    ამავდროულად, უზრუნველვყოფთ, რომ თითოეულმა თანამშრომელმა მიიღოს ექსკლუზიური ბენეფიტები და იგრძნოს დამსაქმებლის რეალური მხარდაჭერა სამუშაო საათების მიღმაც. ჩვენი პლატფორმის დახმარებით, ისინი ყოველდღიურად იზოგავენ დროსა და ფულს, ხოლო მათი კეთილდღეობა ხდება სამსახურის პრიორიტეტი.
                </p>
            </div>
            <blockquote class="mt-10 rounded-2xl border border-primary-200 bg-primary-50 text-primary-900 px-6 py-5 text-lg md:text-xl font-medium leading-relaxed dark:border-primary-500/30 dark:bg-primary-950/40 dark:text-primary-100">
                საბოლოო ჯამში, ჩვენ არ ვართ უბრალოდ ფასდაკლების პლატფორმა — ჩვენ ვართ დამაკავშირებელი ხიდი ბიზნესსა და მის ყველაზე ღირებულ აქტივს, ადამიანებს შორის.
            </blockquote>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-12 md:py-16 bg-gray-50 border-y border-gray-200 dark:bg-slate-900/50 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 md:p-8 text-center dark:bg-slate-800/60 dark:border-slate-700 dark:shadow-none">
                <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">1 000+</div>
                <div class="text-sm text-gray-500 font-medium dark:text-slate-400">აქტიური მომხმარებელი</div>
            </div>
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 md:p-8 text-center dark:bg-slate-800/60 dark:border-slate-700 dark:shadow-none">
                <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">100+</div>
                <div class="text-sm text-gray-500 font-medium dark:text-slate-400">პარტნიორი კომპანია</div>
            </div>
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 md:p-8 text-center dark:bg-slate-800/60 dark:border-slate-700 dark:shadow-none">
                <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">100+</div>
                <div class="text-sm text-gray-500 font-medium dark:text-slate-400">ექსკლუზიური შეთავაზება</div>
            </div>
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 md:p-8 text-center col-span-2 lg:col-span-1 dark:bg-slate-800/60 dark:border-slate-700 dark:shadow-none">
                <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-1">₾100 000+</div>
                <div class="text-sm text-gray-500 font-medium dark:text-slate-400">დაზოგილი თანხა</div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-16 md:py-24 bg-white dark:bg-slate-950">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-3">ჩვენი ღირებულებები</h2>
                <div class="h-1 w-20 mx-auto rounded-full bg-gradient-to-r from-primary-500 to-purple-500"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="rounded-3xl bg-gray-50 border border-gray-200 p-8 hover:border-primary-300 transition-colors dark:bg-slate-900 dark:border-slate-800 dark:hover:border-primary-500/40">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 mb-5 dark:bg-primary-500/20 dark:text-primary-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">მისია</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base dark:text-slate-400">
                        ჩვენი მისიაა, დავეხმაროთ ბიზნესს ძლიერი და ლოიალური გუნდის შენებაში, ხოლო დასაქმებულებს მივცეთ ხელშესახები სარგებელი მათი ყოველდღიური ცხოვრებისა და ფინანსების გასაუმჯობესებლად.
                    </p>
                </div>
                <div class="rounded-3xl bg-gray-50 border border-gray-200 p-8 hover:border-primary-300 transition-colors dark:bg-slate-900 dark:border-slate-800 dark:hover:border-primary-500/40">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-5 dark:bg-purple-500/20 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">ხედვა</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base dark:text-slate-400">
                        ვქმნით ახალ კორპორატიულ სტანდარტს, სადაც თანამშრომელთა ბენეფიტების მართვა აღარ არის რთული რუტინა. ჩვენი მიზანია, ეს პროცესი ვაქციოთ მარტივ, გამჭვირვალე და ავტომატიზებულ ინსტრუმენტად ნებისმიერი ზომის კომპანიისთვის, რომელიც სერიოზულად ემსახურება თავის გუნდს.
                    </p>
                </div>
                <div class="rounded-3xl bg-gray-50 border border-gray-200 p-8 hover:border-primary-300 transition-colors dark:bg-slate-900 dark:border-slate-800 dark:hover:border-primary-500/40 md:col-span-1 col-span-1">
                    <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600 mb-5 dark:bg-pink-500/20 dark:text-pink-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">ზრუნვა</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base dark:text-slate-400">
                        ჩვენთვის ზრუნვა მხოლოდ სიტყვა არ არის — ეს რეალური, ყოველდღიური კომფორტი და დაზოგვაა. ყველაფერი, რასაც პლატფორმაზე ვქმნით, ემსახურება ერთ მიზანს: თითოეულმა ადამიანმა გუნდში იგრძნოს ნამდვილი დაფასება და მხარდაჭერა — არა მხოლოდ სიტყვებით, არამედ კონკრეტული, ხელშესახები სარგებლით.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why us -->
    <section class="py-16 md:py-24 bg-gray-50 border-t border-gray-200 dark:bg-slate-900/40 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-3">რატომ ჩვენ?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg dark:text-slate-400">
                    Perks ქმნის ღირებულებას ყველასთვის — როგორც გუნდის წევრებისთვის, ისე ბიზნესის მფლობელებისთვის.
                </p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
                <div>
                    <h3 class="text-xl font-bold text-primary-600 mb-6 dark:text-primary-400">დასაქმებულებისთვის</h3>
                    <ul class="space-y-6">
                        <li class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 dark:shadow-none">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">ექსკლუზიური ფასდაკლებები</h4>
                            <p class="text-gray-600 text-sm leading-relaxed dark:text-slate-400">წვდომა საუკეთესო ბრენდების სპეციალურ შეთავაზებებზე, რომლებიც მხოლოდ Perks-ის მომხმარებლებისთვისაა ხელმისაწვდომი.</p>
                        </li>
                        <li class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 dark:shadow-none">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">პერსონალიზებული ბენეფიტები</h4>
                            <p class="text-gray-600 text-sm leading-relaxed dark:text-slate-400">თქვენზე მორგებული შეთავაზებები, რომლებიც პასუხობს თქვენს ინდივიდუალურ საჭიროებებსა და ინტერესებს.</p>
                        </li>
                        <li class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 dark:shadow-none">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">კეთილდღეობაზე ზრუნვა</h4>
                            <p class="text-gray-600 text-sm leading-relaxed dark:text-slate-400">მხარდაჭერა ჯანმრთელობის, დასვენებისა და თვითგანვითარების მიმართულებით სამუშაო საათების მიღმაც.</p>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-purple-600 mb-6 dark:text-purple-400">დამსაქმებლებისთვის</h3>
                    <ul class="space-y-6">
                        <li class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 dark:shadow-none">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">ტალანტების შენარჩუნება</h4>
                            <p class="text-gray-600 text-sm leading-relaxed dark:text-slate-400">შექმენით მიმზიდველი სამუშაო გარემო და შეამცირეთ კადრების დენადობა საუკეთესო ბენეფიტების სისტემით.</p>
                        </li>
                        <li class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 dark:shadow-none">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">გაზრდილი ლოიალობა</h4>
                            <p class="text-gray-600 text-sm leading-relaxed dark:text-slate-400">აჩვენეთ თანამშრომლებს რეალური ზრუნვა, რაც პირდაპირ აისახება მათ პროდუქტიულობასა და ერთგულებაზე.</p>
                        </li>
                        <li class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 dark:shadow-none">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">მარტივი მართვა</h4>
                            <p class="text-gray-600 text-sm leading-relaxed dark:text-slate-400">ავტომატიზებული პლატფორმა, რომელიც მინიმუმამდე ამცირებს ადმინისტრაციულ ხარჯებსა და დროს.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-16 md:py-24 bg-white border-t border-gray-200 dark:bg-slate-950 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-3">ჩვენი გუნდი</h2>
                <p class="text-gray-600 max-w-xl mx-auto dark:text-slate-400">გაიცანით ადამიანები, რომლებიც ყოველდღიურად მუშაობენ თქვენი კმაყოფილებისთვის.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
                    ['ლ', 'ლუკა ბერიძე', 'CEO & Founder'],
                    ['მ', 'მარიამ კაპანაძე', 'Head of Partnerships'],
                    ['გ', 'გიორგი ტაბატაძე', 'CTO'],
                    ['ნ', 'ნინო ჩიქოვანი', 'Product Designer'],
                    ['დ', 'დავით ჯაფარიძე', 'Marketing Lead'],
                    ['ა', 'ანა კვარაცხელია', 'Customer Success'],
                ] as $member)
                <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6 flex items-center gap-4 hover:border-gray-300 transition-colors dark:bg-slate-900 dark:border-slate-800 dark:hover:border-slate-600">
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-primary-600 to-purple-600 flex items-center justify-center text-white font-black text-lg">{{ $member[0] }}</div>
                    <div>
                        <div class="font-bold text-gray-900 dark:text-white">{{ $member[1] }}</div>
                        <div class="text-sm text-gray-500 dark:text-slate-400">{{ $member[2] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-primary-50 via-white to-purple-50 border-t border-gray-200 dark:from-primary-900/40 dark:via-slate-900 dark:to-purple-900/30 dark:border-slate-800">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4">შემოუერთდი ჩვენს გუნდს</h2>
            <p class="text-gray-600 mb-10 text-lg leading-relaxed dark:text-slate-300">
                ჩვენ მუდმივად ვეძებთ ნიჭიერ და ენერგიულ ადამიანებს, რომლებსაც სურთ გააუმჯობესონ კორპორატიული კულტურა საქართველოში.
            </p>
            <a href="{{ route('vacancies.index') }}" class="inline-flex items-center justify-center px-10 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-lg transition-colors shadow-lg dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">
                იხილე ვაკანსიები
            </a>
        </div>
    </section>
</div>
</main>

@include('components.landing.footer')
@endsection
