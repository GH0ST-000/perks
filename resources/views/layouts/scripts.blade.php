<script>
    // Translations
    const translations = {
        ka: {
            nav: {
                offers: 'შეთავაზებები',
                companies: 'კომპანიები',
                partners: 'პარტნიორები',
                shop: 'მაღაზია',
                resources: 'რესურსები',
                login: 'შესვლა',
                dashboard: 'პანელი'
            },
            hero: {
                tag: 'თანამშრომელთა ბენეფიტები',
                title1: 'გახსენი ექსკლუზიური ',
                titleHighlight: 'შეღავათები',
                title2: ' შენს საყვარელ ადგილებში',
                subtitle: 'შეუერთდი ათასობით თანამშრომელს, რომლებიც სარგებლობენ ექსკლუზიური ფასდაკლებებით, ჯილდოებით და შეღავათებით საქართველოს ასობით პარტნიორთან.',
                exploreBtn: 'იხილე შეთავაზებები',
                companiesBtn: 'კომპანიებისთვის'
            },
            offers: {
                title: 'პრემიუმ შეთავაზებები',
                subtitle: 'ექსკლუზიური გარიგებები ჩვენი ყველაზე მაღალი რეიტინგის პარტნიორებისგან',
                seeAll: 'ყველას ნახვა',
                premium: 'პრემიუმ',
                viewDetails: 'დეტალურად',
                pcoin: 'პ-კოინი'
            },
            categories: {
                title: 'იკვლევე კატეგორიით',
                subtitle: 'აღმოაჩინე ექსკლუზიური შეთავაზებები შენს ყველა საყვარელ კატეგორიაში',
                restaurant: 'რესტორანი',
                hotel: 'სასტუმრო',
                fitness: 'ფიტნესი',
                wellness: 'ველნესი',
                entertainment: 'გასართობი'
            },
            pricing: {
                title: 'აირჩიე შენი პაკეტი',
                subtitle: 'მოქნილი ფასები ინდივიდუალებისთვის და კომპანიებისთვის',
                individual: 'ინდივიდუალური',
                individualDesc: 'იდეალური ერთი მომხმარებლისთვის',
                business: 'ბიზნესი',
                businessDesc: 'გუნდებისა და კომპანიებისთვის',
                month: 'თვეში',
                mostPopular: 'ყველაზე პოპულარული',
                chooseBtn: 'აირჩიე პაკეტი'
            },
            testimonials: {
                title: 'რას ამბობენ ჩვენი წევრები',
                quote: 'Perks-მა შეცვალა ის, თუ როგორ ვთავაზობთ ბენეფიტებს ჩვენს თანამშრომლებს. პლატფორმა მარტივია გამოსაყენებლად და ჩვენს გუნდს უყვარს ხელმისაწვდომი ექსკლუზიური შეთავაზებების მრავალფეროვნება.',
                name: 'გიორგი ბერიძე',
                role: 'HR დირექტორი, TechCorp საქართველო'
            },
            footer: {
                desc: 'საქართველოს წამყვანი თანამშრომელთა ბენეფიტების პლატფორმა, რომელიც აკავშირებს კომპანიებს ექსკლუზიურ შეთავაზებებთან ქვეყნის საუკეთესო პარტნიორებთან.',
                platform: 'პლატფორმა',
                resources: 'რესურსები',
                contact: 'კონტაქტი',
                blog: 'ბლოგი',
                aboutUs: 'ჩვენ შესახებ',
                careers: 'კარიერა',
                privacy: 'კონფიდენციალურობა',
                terms: 'წესები და პირობები',
                rights: 'ყველა უფლება დაცულია.',
                forCompanies: 'კომპანიებისთვის',
                forPartners: 'პარტნიორებისთვის'
            }
        },
        en: {
            nav: {
                offers: 'Offers',
                companies: 'Companies',
                partners: 'Partners',
                shop: 'Shop',
                resources: 'Resources',
                login: 'Login',
                dashboard: 'Dashboard'
            },
            hero: {
                tag: 'Employee Benefits',
                title1: 'Unlock Exclusive ',
                titleHighlight: 'Benefits',
                title2: ' at Your Favorite Places',
                subtitle: 'Join thousands of employees enjoying exclusive discounts, rewards, and perks at hundreds of partner locations across Georgia.',
                exploreBtn: 'Explore Offers',
                companiesBtn: 'For Companies'
            },
            offers: {
                title: 'Premium Offers',
                subtitle: 'Exclusive deals from our top-rated partners',
                seeAll: 'See All',
                premium: 'Premium',
                viewDetails: 'View Details',
                pcoin: 'P-Coin'
            },
            categories: {
                title: 'Explore by Category',
                subtitle: 'Discover exclusive offers across all your favorite categories',
                restaurant: 'Restaurant',
                hotel: 'Hotel',
                fitness: 'Fitness',
                wellness: 'Wellness',
                entertainment: 'Entertainment'
            },
            pricing: {
                title: 'Choose Your Plan',
                subtitle: 'Flexible pricing options for individuals and companies',
                individual: 'Individual',
                individualDesc: 'Perfect for single users',
                business: 'Business',
                businessDesc: 'For teams and companies',
                month: '/month',
                mostPopular: 'Most Popular',
                chooseBtn: 'Choose Plan'
            },
            testimonials: {
                title: 'What Our Members Say',
                quote: 'Perks has transformed how we offer benefits to our employees. The platform is easy to use and our team loves the variety of exclusive offers available.',
                name: 'Giorgi Beridze',
                role: 'HR Director, TechCorp Georgia'
            },
            footer: {
                desc: 'Georgia\'s leading employee benefits platform, connecting companies with exclusive offers from top partners nationwide.',
                platform: 'Platform',
                resources: 'Resources',
                contact: 'Contact',
                blog: 'Blog',
                aboutUs: 'About Us',
                careers: 'Careers',
                privacy: 'Privacy Policy',
                terms: 'Terms of Service',
                rights: 'All rights reserved.',
                forCompanies: 'For Companies',
                forPartners: 'For Partners'
            }
        }
    };

    let currentLanguage = 'en';

    // Language switcher function (global scope)
    function changeLanguage(lang) {
        currentLanguage = lang;
        localStorage.setItem('language', lang);

        // Update all elements with data-i18n attribute
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const keys = element.getAttribute('data-i18n').split('.');
            let translation = translations[lang];

            for (const key of keys) {
                if (translation[key] !== undefined) {
                    translation = translation[key];
                }
            }

            if (typeof translation === 'string') {
                element.textContent = translation;
            }
        });

        // Update language buttons
        const buttons = {
            ka: document.getElementById('lang-ka'),
            en: document.getElementById('lang-en'),
            kaMobile: document.getElementById('lang-ka-mobile'),
            enMobile: document.getElementById('lang-en-mobile')
        };

        // Desktop buttons
        if (lang === 'ka') {
            if (buttons.ka) {
                buttons.ka.classList.add('bg-white', 'dark:bg-gray-700', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                buttons.ka.classList.remove('text-gray-500');
            }
            if (buttons.en) {
                buttons.en.classList.remove('bg-white', 'dark:bg-gray-700', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                buttons.en.classList.add('text-gray-500');
            }

            // Mobile buttons
            if (buttons.kaMobile) {
                buttons.kaMobile.classList.add('bg-white', 'shadow');
            }
            if (buttons.enMobile) {
                buttons.enMobile.classList.remove('bg-white', 'shadow');
            }
        } else {
            if (buttons.en) {
                buttons.en.classList.add('bg-white', 'dark:bg-gray-700', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                buttons.en.classList.remove('text-gray-500');
            }
            if (buttons.ka) {
                buttons.ka.classList.remove('bg-white', 'dark:bg-gray-700', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                buttons.ka.classList.add('text-gray-500');
            }

            // Mobile buttons
            if (buttons.enMobile) {
                buttons.enMobile.classList.add('bg-white', 'shadow');
            }
            if (buttons.kaMobile) {
                buttons.kaMobile.classList.remove('bg-white', 'shadow');
            }
        }
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }

    // Close mobile menu when clicking a link
    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.add('hidden');
        });
    });

    // Hero Slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.slide-indicator');
    const totalSlides = slides.length;
    let slideInterval;

    function changeSlide(index) {
        // Hide all slides
        slides.forEach(slide => {
            slide.classList.remove('opacity-100');
            slide.classList.add('opacity-0');
        });

        // Reset all indicators
        indicators.forEach(indicator => {
            indicator.classList.remove('w-8', 'bg-primary-500');
            indicator.classList.add('w-2', 'bg-white/50');
        });

        // Show selected slide
        slides[index].classList.remove('opacity-0');
        slides[index].classList.add('opacity-100');

        // Highlight selected indicator
        indicators[index].classList.remove('w-2', 'bg-white/50');
        indicators[index].classList.add('w-8', 'bg-primary-500');

        currentSlide = index;

        // Reset auto-play timer
        clearInterval(slideInterval);
        startSlideShow();
    }

    function nextSlide() {
        const next = (currentSlide + 1) % totalSlides;
        changeSlide(next);
    }

    function startSlideShow() {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    // Testimonials Slider
    let currentTestimonial = 0;
    let testimonialInterval;
    let testimonials = [];
    let testimonialIndicators = [];

    function changeTestimonial(index) {
        if (testimonials.length === 0) return;

        // Hide all testimonials
        testimonials.forEach(testimonial => {
            testimonial.classList.remove('opacity-100');
            testimonial.classList.add('opacity-0');
        });

        // Reset all indicators
        testimonialIndicators.forEach(indicator => {
            indicator.classList.remove('w-8', 'bg-primary-500');
            indicator.classList.add('w-2', 'bg-gray-300', 'dark:bg-gray-700');
        });

        // Show selected testimonial
        if (testimonials[index]) {
            testimonials[index].classList.remove('opacity-0');
            testimonials[index].classList.add('opacity-100');
        }

        // Highlight selected indicator
        if (testimonialIndicators[index]) {
            testimonialIndicators[index].classList.remove('w-2', 'bg-gray-300', 'dark:bg-gray-700');
            testimonialIndicators[index].classList.add('w-8', 'bg-primary-500');
        }

        currentTestimonial = index;

        // Reset auto-rotation timer
        clearInterval(testimonialInterval);
        startTestimonialAutoRotate();
    }

    function nextTestimonial() {
        if (testimonials.length === 0) return;
        const next = (currentTestimonial + 1) % testimonials.length;
        changeTestimonial(next);
    }

    function startTestimonialAutoRotate() {
        if (testimonials.length <= 1) return; // Don't rotate if there's only one or no testimonials
        clearInterval(testimonialInterval);
        testimonialInterval = setInterval(nextTestimonial, 5000); // Change every 5 seconds
    }

    // Start the slideshow when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startSlideShow();
        // Load saved language preference
        const savedLang = localStorage.getItem('language') || 'en';
        changeLanguage(savedLang);

        // Initialize testimonials slider
        testimonials = Array.from(document.querySelectorAll('.testimonial-slide'));
        testimonialIndicators = Array.from(document.querySelectorAll('.testimonial-indicator'));
        
        // Start testimonials auto-rotation if there are testimonials
        if (testimonials.length > 1) {
            startTestimonialAutoRotate();
        }
    });
</script>
