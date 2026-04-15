<!-- Footer -->
<footer class="bg-gray-900 text-white pt-20 pb-10">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center text-white font-bold">P</div>
                <span class="text-xl font-bold">Perks.ge</span>
            </div>
            <p class="text-gray-400 text-sm leading-relaxed">
                საქართველოს წამყვანი თანამშრომელთა ბენეფიტების პლატფორმა, რომელიც აკავშირებს კომპანიებს ექსკლუზიურ შეთავაზებებთან ქვეყნის საუკეთესო პარტნიორებთან.
            </p>
            <div class="flex gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div>
            <h4 class="font-bold text-lg mb-6">პლატფორმა</h4>
            <ul class="space-y-4 text-sm text-gray-400">
                <li><a href="{{ route('offers.index') }}" class="hover:text-white transition-colors">შეთავაზებები</a></li>
                <li><a href="{{ route('vacancies.index') }}" class="hover:text-white transition-colors">ვაკანსიები</a></li>
                <li><a href="{{ route('companies') }}" class="hover:text-white transition-colors">კომპანიებისთვის</a></li>
                <li><a href="{{ route('partners') }}" class="hover:text-white transition-colors">პარტნიორებისთვის</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-bold text-lg mb-6">რესურსები</h4>
            <ul class="space-y-4 text-sm text-gray-400">
                <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">ბლოგი</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">ჩვენს შესახებ</a></li>
                <li><a href="{{ route('vacancies.index') }}" class="hover:text-white transition-colors">კარიერა</a></li>
                <li><a href="#" onclick="openFooterModal('termsModal'); return false;" class="hover:text-white transition-colors cursor-pointer">წესები და პირობები</a></li>
                <li><a href="#" onclick="openFooterModal('privacyModal'); return false;" class="hover:text-white transition-colors cursor-pointer">კონფიდენციალურობის პოლიტიკა</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-bold text-lg mb-6">კონტაქტი</h4>
            <ul class="space-y-4 text-sm text-gray-400">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Tbilisi, Georgia</span>
                </li>
                <li class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>+995 32 2 00 00 00</span>
                </li>
                <li class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>info@perks.ge</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} Perks.ge. ყველა უფლება დაცულია.</p>
        <div class="flex gap-6">
            <a href="#" onclick="openFooterModal('termsModal'); return false;" class="hover:text-white transition-colors">წესები და პირობები</a>
            <a href="#" onclick="openFooterModal('privacyModal'); return false;" class="hover:text-white transition-colors">კონფიდენციალურობის პოლიტიკა</a>
        </div>
    </div>
</footer>

<!-- Privacy Policy Modal -->
<div id="privacyModal" class="fixed inset-0 z-[9999] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeFooterModal('privacyModal')"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl max-h-[85vh] flex flex-col transform transition-all">
                <div class="sticky top-0 z-10 bg-white rounded-t-2xl border-b border-gray-100 px-8 py-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">კონფიდენციალურობის პოლიტიკა</h2>
                        <p class="text-sm text-gray-500 mt-1">შპს ''პერქს'' · ს/ნ: 400455690</p>
                    </div>
                    <button onclick="closeFooterModal('privacyModal')" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-8 py-6 text-gray-700 leading-relaxed space-y-6 text-sm">
                    <p>შპს ''პერქს'' ს/ნ: 400455690 (შემდგომში „Perks", „ჩვენ" ან „კომპანია") პატივს სცემს თქვენს პირად სივრცეს. წინამდებარე დოკუმენტი შემუშავებულია „პერსონალურ მონაცემთა დაცვის შესახებ" საქართველოს კანონის მოთხოვნათა სრული დაცვით და განსაზღვრავს ჩვენი პლატფორმით (www.perks.ge) სარგებლობისას თქვენი მონაცემების დამუშავების, შენახვისა და უსაფრთხოების წესებს.</p>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">1. რა მონაცემებს ვაგროვებთ და რა არის დამუშავების სამართლებრივი საფუძველი?</h3>
                        <p class="mb-3">ჩვენ ვაგროვებთ მხოლოდ იმ ინფორმაციას, რაც აუცილებელია სერვისის უზრუნველსაყოფად. თითოეული მონაცემის დამუშავებას აქვს თავისი მკაცრი სამართლებრივი საფუძველი:</p>

                        <div class="space-y-4 pl-4">
                            <div>
                                <h4 class="font-semibold text-gray-800">საიდენტიფიკაციო, ლოკაციური და საკონტაქტო მონაცემები:</h4>
                                <ul class="list-disc pl-5 mt-1 space-y-1 text-gray-600">
                                    <li>სახელი, გვარი;</li>
                                    <li>პირადი ნომერი (პლატფორმაზე მომხმარებლის უნიკალური იდენტიფიკაციისთვის, გაორებული ანგარიშებისა და თაღლითობის პრევენციის მიზნით);</li>
                                    <li>საცხოვრებელი ქალაქი (თქვენს ლოკაციაზე მაქსიმალურად მორგებული, რელევანტური პარტნიორი ობიექტების შესათავაზებლად);</li>
                                    <li>ტელეფონის ნომერი (აუცილებელია ფასდაკლების SMS ვერიფიკაციისთვის) და ელ.ფოსტა.</li>
                                </ul>
                                <p class="mt-1 text-gray-500 italic">სამართლებრივი საფუძველი: სახელშეკრულებო ვალდებულების შესრულება და თქვენი თანხმობა.</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-800">კორპორატიული მონაცემები:</h4>
                                <p class="text-gray-600">ინფორმაცია თქვენი დამსაქმებელი კომპანიის შესახებ და არჩეული სააბონენტო პაკეტი.</p>
                                <p class="mt-1 text-gray-500 italic">სამართლებრივი საფუძველი: სახელშეკრულებო ვალდებულების შესრულება.</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-800">ტრანზაქციული და აქტივობის მონაცემები:</h4>
                                <p class="text-gray-600">პარტნიორ ობიექტებში ვიზიტების ისტორია, დაგროვებული და დახარჯული P-Coin-ები.</p>
                                <p class="mt-1 text-gray-500 italic">სამართლებრივი საფუძველი: ჩვენი ლეგიტიმური ინტერესი (სერვისის გაუმჯობესება) და სახელშეკრულებო ვალდებულება.</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-800">ტექნოლოგიური მონაცემები (Cookies):</h4>
                                <p class="text-gray-600">IP მისამართი, მოწყობილობისა და ბრაუზერის ტიპი, სესიის სტატუსი.</p>
                                <p class="mt-1 text-gray-500 italic">სამართლებრივი საფუძველი: თქვენი თანხმობა (ვებსაიტზე ავტორიზაციისას).</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-800">ფინანსური მონაცემები:</h4>
                                <p class="text-gray-600">საგადახდო ბარათის ნაწილობრივი მონაცემები (მხოლოდ პირველი 6 და ბოლო 4 ციფრი) გამოწერის ავტომატური განახლების სამართავად (სრულ მონაცემებს ამუშავებს პარტნიორი ბანკი).</p>
                                <p class="mt-1 text-gray-500 italic">სამართლებრივი საფუძველი: კანონისმიერი ვალდებულება და ხელშეკრულების შესრულება.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">2. რატომ ვაგროვებთ და როგორ ვიყენებთ თქვენს მონაცემებს?</h3>
                        <p class="mb-2">ჩვენ ვიყენებთ თქვენს მონაცემებს მხოლოდ მკაცრად განსაზღვრული მიზნებისთვის:</p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li><strong class="text-gray-800">ვერიფიკაცია და მომსახურების გაწევა:</strong> თქვენი ტელეფონის ნომერი აუცილებელია პარტნიორ ობიექტზე ერთჯერადი SMS კოდის მისაღებად და ფასდაკლების ვალიდაციისთვის.</li>
                            <li><strong class="text-gray-800">ანგარიშის მართვა:</strong> Family Sharing-ის ფარგლებში ოჯახის წევრის დამატების ან პაკეტის ცვლილების პროცესების უზრუნველსაყოფად.</li>
                            <li><strong class="text-gray-800">პირდაპირი მარკეტინგი და კომუნიკაცია:</strong> სიახლეების, ახალი პარტნიორი ობიექტებისა და სპეციალური შეთავაზებების შესახებ ინფორმაციის მოსაწოდებლად.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">3. ვის ვუზიარებთ მომხმარებლის მონაცემებს?</h3>
                        <p class="mb-3">Perks იცავს თქვენი მონაცემების კონფიდენციალურობას და იღებს ვალდებულებას არ გადასცეს მესამე პირს. მონაცემების გაზიარება ხდება მხოლოდ გამონაკლის შემთხვევებში:</p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li><strong class="text-gray-800">პარტნიორ ობიექტებთან:</strong> ვალიდაციის პროცესში პარტნიორი ობიექტი იყენებს მხოლოდ თქვენს საიდენტიფიკაციო მონაცემს (ტელეფონის ნომერს/კოდს), რათა მოხდეს ფასდაკლების დადასტურება ჩვენს სისტემაში. მათ არ აქვთ წვდომა თქვენს სრულ პირად პროფილსა და ვიზიტების ისტორიაზე.</li>
                            <li><strong class="text-gray-800">სერვის-პროვაიდერებთან (მესამე მხარეებთან):</strong> სატელეკომუნიკაციო ოპერატორებთან (SMS კოდების გამოსაგზავნად), საგადახდო სისტემებთან და სერვერულ პროვაიდერებთან, რომლებიც მოქმედებენ მკაცრი კონფიდენციალურობის ხელშეკრულების საფუძველზე.</li>
                            <li><strong class="text-gray-800">სახელმწიფო ორგანოებთან:</strong> საქართველოს მოქმედი კანონმდებლობით გათვალისწინებულ შემთხვევებში (მაგ. დანაშაულის გამოძიება ან საგადასახადო აუდიტი).</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">4. თაღლითობის პრევენცია და ავტომატური გადაწყვეტილებები (პროფაილინგი)</h3>
                        <p>თქვენი მონაცემები გვეხმარება, დავადგინოთ ხომ არ გამოიყენება პროფილი არაკეთილსინდისიერად (მაგალითად, SMS კოდის სხვა პირისთვის გადაცემა). სისტემამ შესაძლოა ავტომატური რეჟიმით დროებით შეაჩეროს თქვენი ანგარიში, თუ დაფიქსირდა არაბუნებრივი ან საეჭვო ტრანზაქციული აქტივობა. თქვენ გაქვთ უფლება, მოითხოვოთ ამგვარი ავტომატური გადაწყვეტილების გადახედვა ჩვენი გუნდის (ადამიანის) მიერ.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">5. მონაცემთა შენახვის ვადები</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li><strong class="text-gray-800">აქტიური მომსახურება:</strong> თქვენი მონაცემები ინახება პლატფორმით სარგებლობის მთელი პერიოდის განმავლობაში.</li>
                            <li><strong class="text-gray-800">სესიის მონაცემები:</strong> ავტორიზაციის სტატუსი ვებსაიტზე ინახება 4 თვის ვადით, გარდა იმ შემთხვევისა, თუ თქვენ თავად არ გამოხვალთ სისტემიდან (Log out).</li>
                            <li><strong class="text-gray-800">მომსახურების შეწყვეტის შემდეგ:</strong> ანგარიშის გაუქმების შემდეგ, საგადასახადო/ფინანსური ანგარიშგებისა და შესაძლო სამართლებრივი დავების პრევენციის მიზნით, მონაცემები ინახება არაუმეტეს 3 წლის ვადით. ამ ვადის გასვლის შემდეგ მონაცემები სრულად ნადგურდება.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">6. პირდაპირი მარკეტინგის შეწყვეტის უფლება</h3>
                        <p class="mb-2">თქვენ ნებისმიერ დროს გაქვთ უფლება, მოითხოვოთ პირდაპირი მარკეტინგის (სარეკლამო SMS ან Email შეტყობინებების) შეწყვეტა:</p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li><strong class="text-gray-800">პირადი პროფილიდან:</strong> პარამეტრების განყოფილებაში შესაბამისი ღილაკის მონიშვნის მოხსნით.</li>
                            <li><strong class="text-gray-800">ელ.ფოსტით:</strong> მოგვწერეთ მისამართზე privacy@perks.ge სათაურით "MARKETING-OFF".</li>
                        </ul>
                        <p class="mt-2 text-gray-500 italic">მარკეტინგული შეტყობინებების გათიშვა არ იწვევს საოპერაციო (მაგ. ვერიფიკაციის SMS კოდები) შეტყობინებების მიწოდების შეწყვეტას.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">7. არასრულწლოვანი პირები</h3>
                        <p>Perks-ის პლატფორმა შექმნილია სრულწლოვანი პირებისთვის. ჩვენ მიზანმიმართულად არ ვაგროვებთ მონაცემებს 18 წლამდე პირების შესახებ. პლატფორმაზე ავტორიზაციით თქვენ ადასტურებთ, რომ ხართ სულ მცირე 18 წლის. არასრულწლოვანი პირის მიერ რეგისტრაციის აღმოჩენის შემთხვევაში, მისი პროფილი დაუყოვნებლივ გაუქმდება.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">8. მომხმარებლის უფლებები და მონაცემთა მართვა</h3>
                        <p class="mb-2">საქართველოს კანონმდებლობის შესაბამისად, თქვენ ნებისმიერ დროს გაქვთ უფლება მოითხოვოთ:</p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li>ინფორმაცია იმის შესახებ, თუ რომელი მონაცემები მუშავდება ჩვენ მიერ;</li>
                            <li>მონაცემების გასწორება, განახლება ან დამატება;</li>
                            <li>მონაცემების პორტირება (ასლის გადმოცემა);</li>
                            <li>მონაცემთა დამუშავების შეწყვეტა, დაბლოკვა ან სრულად წაშლა (Right to be Forgotten), თუ აღარ არსებობს მათი შენახვის კანონისმიერი საფუძველი;</li>
                            <li>თქვენ მიერ გაცემული თანხმობის გამოხმობა.</li>
                        </ul>
                        <p class="mt-2 text-gray-500 italic">იმ შემთხვევაში, თუ თვლით, რომ თქვენი უფლებები დაირღვა, გაქვთ უფლება საჩივრით მიმართოთ პერსონალურ მონაცემთა დაცვის სამსახურს.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">9. უსაფრთხოება</h3>
                        <p>ჩვენ ვიყენებთ უსაფრთხოების თანამედროვე ტექნიკურ და ორგანიზაციულ სტანდარტებს (მათ შორის, დაშიფრვას) თქვენი მონაცემების არაავტორიზებული წვდომისგან, დაკარგვისგან ან განადგურებისგან დასაცავად. გთხოვთ, არ გაანდოთ თქვენი პაროლი და SMS კოდები მესამე პირებს, წინააღმდეგ შემთხვევაში კომპანია იხსნის პასუხისმგებლობას მონაცემთა გაჟონვაზე.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">10. ცვლილებები პოლიტიკაში</h3>
                        <p>წინამდებარე დოკუმენტში შეიძლება განხორციელდეს ცვლილებები. მნიშვნელოვანი ცვლილებების შემთხვევაში, თქვენ მიიღებთ პირად შეტყობინებას ან განახლებული ვერსია გამოქვეყნდება ვებგვერდზე. პლატფორმით სარგებლობის გაგრძელება ჩაითვლება განახლებულ პირობებზე თანხმობად.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">11. საკონტაქტო ინფორმაცია და მონაცემთა დაცვის ოფიცერი (DPO)</h3>
                        <p>თუ გაქვთ კითხვები მონაცემთა დაცვასთან დაკავშირებით ან გსურთ თქვენი უფლებების გამოყენება, გთხოვთ დაგვიკავშირდეთ - ელ.ფოსტა: <a href="mailto:privacy@perks.ge" class="text-blue-600 hover:underline">privacy@perks.ge</a></p>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-white rounded-b-2xl border-t border-gray-100 px-8 py-4 flex justify-end">
                    <button onclick="closeFooterModal('privacyModal')" class="px-6 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">
                        დახურვა
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms & Conditions Modal -->
<div id="termsModal" class="fixed inset-0 z-[9999] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeFooterModal('termsModal')"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl max-h-[85vh] flex flex-col transform transition-all">
                <div class="sticky top-0 z-10 bg-white rounded-t-2xl border-b border-gray-100 px-8 py-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 leading-snug">Perks - ვებ-გვერდითა და სერვისებით სარგებლობის წესები და პირობები</h2>
                        <p class="text-sm text-gray-500 mt-1">www.perks.ge</p>
                    </div>
                    <button onclick="closeFooterModal('termsModal')" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-8 py-6 text-gray-700 leading-relaxed space-y-6 text-sm">
                    <p>წინამდებარე დოკუმენტი არეგულირებს პლატფორმა Perks-სა (შემდგომში - „კომპანია") და პლატფორმით მოსარგებლე პირს (შემდგომში - „მომხმარებელი") შორის არსებულ სამართლებრივ ურთიერთობებს. ვებ-გვერდზე (www.perks.ge) ავტორიზაციითა და მომსახურების პირობებზე თანხმობის ღილაკის მონიშვნით, თქვენ სრულად ეთანხმებით ქვემოთ მოცემულ წესებს.</p>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">1. ტერმინთა განმარტება</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li><strong class="text-gray-800">კლიენტი (დამსაქმებელი):</strong> იურიდიული პირი, რომელთანაც Perks-ს გაფორმებული აქვს კორპორატიული მომსახურების ხელშეკრულება.</li>
                            <li><strong class="text-gray-800">მომხმარებელი:</strong> კლიენტის თანამშრომელი (ან მისი ოჯახის წევრი Limited პაკეტის ფარგლებში), რომელიც რეგისტრირდება Perks-ის ვებ-გვერდზე.</li>
                            <li><strong class="text-gray-800">პარტნიორი ობიექტი:</strong> ბიზნესი (რესტორანი, მაღაზია, სერვისი), რომელიც უშუალოდ გასცემს შეთავაზებას/ფასდაკლებას.</li>
                            <li><strong class="text-gray-800">ინტერაქტიული სერვისი / პლატფორმა:</strong> კომპანიის კუთვნილი ვებ-გვერდი და მისი ფუნქციონალი. (Perks არ იყენებს მობილურ აპლიკაციას).</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">2. სერვისის არსი და პასუხისმგებლობის შეზღუდვა</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">2.1.</strong> Perks წარმოადგენს დამაკავშირებელ, ინტერაქტიულ პლატფორმას მომხმარებელსა და პარტნიორ ობიექტს შორის.</li>
                            <li><strong class="text-gray-800">2.2. პასუხისმგებლობა მომსახურებაზე:</strong> Perks არ არის იმ პროდუქტების/სერვისების მფლობელი, რომლებსაც სთავაზობს პლატფორმის მეშვეობით. შესაბამისად, პარტნიორი ობიექტის მიერ გაწეული მომსახურების ხარისხზე, უსაფრთხოებასა თუ რაიმე სახის ფიზიკურ/მატერიალურ ზიანზე ერთპიროვნულად პასუხისმგებელია თავად პარტნიორი ობიექტი.</li>
                            <li><strong class="text-gray-800">2.3. ტექნიკური შეფერხება:</strong> ინტერნეტქსელის არსიდან გამომდინარე, კომპანია არ იძლევა გარანტიას, რომ ვებ-გვერდის მუშაობა იქნება უწყვეტი. კომპანია არ აგებს პასუხს პარტნიორის ობიექტზე ინტერნეტის გათიშვის ან მობილური ოპერატორის მიერ SMS-ის დაგვიანების გამო შეთავაზების მიღების შეფერხებაზე.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">3. ანგარიშსწორება და გამოწერის ავტომატური განახლება</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">3.1.</strong> მომხმარებლის მიერ სერვისის (სტანდარტი ან Limited პაკეტი) შეძენა ხდება ვებ-გვერდზე საბანკო ბარათის მიბმით.</li>
                            <li><strong class="text-gray-800">3.2. ავტომატური ჩამოჭრა:</strong> მომსახურება წარმოადგენს ყოველთვიურ გამოწერას (Subscription). მომდევნო საანგარიშო პერიოდის (თვის) საფასურის ავტომატური ჩამოჭრა მიბმული ბარათიდან განხორციელდება ახალი პერიოდის დაწყებამდე შეთანხმებული წესით. თუ მომხმარებელს არ სურს გამოწერის გაგრძელება, მან უნდა გააუქმოს იგი პირად პროფილში ჩამოჭრის თარიღამდე.</li>
                            <li><strong class="text-gray-800">3.3. ფასის ცვლილება:</strong> კომპანია იტოვებს უფლებას ცალმხრივად შეცვალოს მომსახურების პაკეტების საფასური. ამის შესახებ მომხმარებელს ეცნობება ელ.ფოსტის ან პროფილის მეშვეობით, ახალი ფასის ძალაში შესვლამდე არანაკლებ 30 დღით ადრე. შეცვლილი ფასი არ იმოქმედებს უკვე გადახდილ თვეზე.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">4. ფასდაკლების გამოყენება (SMS ვერიფიკაცია) და თაღლითობის პრევენცია</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">4.1.</strong> შეთავაზების მისაღებად მომხმარებელმა ანგარიშსწორებამდე პარტნიორ ობიექტს უნდა წარუდგინოს თავისი პერსონალური საიდენტიფიკაციო კოდი / ტელეფონის ნომერი.</li>
                            <li><strong class="text-gray-800">4.2.</strong> ვალიდაცია სრულდება მომხმარებლის ტელეფონზე მოსული ერთჯერადი SMS კოდის ობიექტის წარმომადგენლისთვის კარნახით.</li>
                            <li><strong class="text-gray-800">4.3. ფიქტიური ვიზიტები და გადაცემა:</strong> სასტიკად იკრძალება საკუთარი ნომრისა და SMS კოდის სხვა პირისთვის გადაცემა, ასევე ფიქტიური ვიზიტის დაფიქსირება სერვისის მიღების გარეშე. ამ წესის დარღვევის ან არაკეთილსინდისიერი ქცევის აღმოჩენის შემთხვევაში, Perks იტოვებს უფლებას მომენტალურად, თანხის დაბრუნების გარეშე, გააუქმოს მომხმარებლის პროფილი.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">5. Family Sharing (ოჯახის წევრის დამატება)</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">5.1.</strong> Limited პაკეტის მფლობელს უფლება აქვს, თავისი პროფილის მეშვეობით მიიწვიოს ოჯახის 1 წევრი.</li>
                            <li><strong class="text-gray-800">5.2.</strong> ოჯახის წევრი გადის დამოუკიდებელ რეგისტრაციას საკუთარი ტელეფონის ნომრით და შეთავაზებებით სარგებლობს ინდივიდუალური SMS ვერიფიკაციის გავლით.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">6. ხელშეკრულების შეწყვეტა, შეჩერება და თანხის დაბრუნება (Refund)</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">6.1. 14-დღიანი უარის უფლება:</strong> საქართველოს კანონმდებლობის შესაბამისად, მომხმარებელს უფლება აქვს მომსახურების პირველადი შეძენიდან 14 კალენდარული დღის განმავლობაში ყოველგვარი მიზეზის გარეშე უარი თქვას ხელშეკრულებაზე. ამ შემთხვევაში მას დაუბრუნდება გადახდილი თანხა, უკვე მიღებული სარგებლის (ასეთის არსებობისას) პროპორციული გამოკლებით.</li>
                            <li><strong class="text-gray-800">6.2. ხელშეკრულების შეწყვეტა:</strong> მომხმარებელს უფლება აქვს სრულად გააუქმოს პლატფორმის წევრობა კომპანიის 1 (ერთი) თვით ადრე წერილობითი გაფრთხილების საფუძველზე.</li>
                            <li><strong class="text-gray-800">6.3. შეჩერება და დაბრუნების პოლიტიკა:</strong> მომხმარებელს უფლება აქვს ნებისმიერ დროს გააუქმოს გამოწერა (მომსახურების შეჩერება) შემდგომი საანგარიშო პერიოდიდან. თუმცა, მომხმარებელს უფლება არ აქვს მოითხოვოს შესაბამის საანგარიშო (მიმდინარე/უკვე გადახდილ) პერიოდზე გადახდილი საფასურის უკან დაბრუნება. სერვისით სარგებლობა გაგრძელდება გადახდილი თვის ბოლომდე.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">7. საავტორო უფლებები და პლატფორმის საკუთრება</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">7.1.</strong> ვებ-გვერდზე განთავსებული სრული კონტენტი (დიზაინი, ლოგო, ტექსტები, სტრუქტურა) დაცულია საავტორო უფლებებით და წარმოადგენს კომპანიის ექსკლუზიურ საკუთრებას.</li>
                            <li><strong class="text-gray-800">7.2.</strong> აკრძალულია პლატფორმის კონტენტის, მასალების ან მონაცემთა ბაზის სრულად ან ნაწილობრივ კოპირება, მოდიფიცირება ან კომერციული მიზნებისთვის გამოყენება კომპანიის წინასწარი წერილობითი თანხმობის გარეშე.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">8. ფორს-მაჟორი</h3>
                        <p><strong class="text-gray-800">8.1.</strong> მხარეები თავისუფლდებიან პასუხისმგებლობისგან ვალდებულებების შეუსრულებლობისთვის, თუ ეს გამოწვეულია დაუძლეველი ძალით (ფორს-მაჟორი), რაც მოიცავს: სტიქიურ უბედურებებს, ომს, პანდემიას, კიბერ-შეტევებს, სახელმწიფო ორგანოების მიერ მიღებულ ისეთ აქტებს, რომლებიც შეუძლებელს ხდის პლატფორმის ოპერირებას.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">9. მონაცემთა დამუშავება, ქუქი-ფაილები (Cookies) და ანალიტიკა</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li><strong class="text-gray-800">9.1.</strong> პლატფორმის შეუფერხებელი მუშაობის, უსაფრთხოებისა და სერვისის გაუმჯობესების მიზნით, Perks იყენებს ქუქი-ფაილებს (Cookies) და მსგავს ტექნოლოგიებს.</li>
                            <li><strong class="text-gray-800">9.2.</strong> ჩვენ ვაგროვებთ ტექნიკურ ინფორმაციას (IP მისამართი, ბრაუზერის ტიპი, საიტზე გატარებული დრო), რათა გავაანალიზოთ მომხმარებელთა ქცევა და აღმოვფხვრათ ხარვეზები. პლატფორმაზე ასევე ინტეგრირებულია მესამე მხარის ანალიტიკური სერვისები (მაგ: Google Analytics).</li>
                            <li><strong class="text-gray-800">9.3.</strong> მომხმარებლის პერსონალური მონაცემების დამუშავების, შენახვისა და მესამე პირებზე (პარტნიორ ობიექტებზე ვერიფიკაციის მიზნით) გაცემის დეტალური წესები რეგულირდება Perks-ის ცალკეული კონფიდენციალურობის პოლიტიკის (Privacy Policy) დოკუმენტით, რომელიც წინამდებარე წესების განუყოფელი ნაწილია.</li>
                        </ul>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-white rounded-b-2xl border-t border-gray-100 px-8 py-4 flex justify-end">
                    <button onclick="closeFooterModal('termsModal')" class="px-6 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">
                        დახურვა
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openFooterModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const scrollBody = modal.querySelector('.max-w-4xl div.overflow-y-auto.px-8');
        if (scrollBody) scrollBody.scrollTop = 0;
    }

    function closeFooterModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function onFooterModalEscape(e) {
        if (e.key !== 'Escape') return;
        ['privacyModal', 'termsModal'].forEach(function(id) {
            const modal = document.getElementById(id);
            if (modal && !modal.classList.contains('hidden')) {
                closeFooterModal(id);
            }
        });
    });
</script>
