<x-guest-layout>
    <div class="w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white transition-colors">ანგარიშის შექმნა</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">დარეგისტრირდით დასაწყებად</p>
        </div>

        <!-- Step 1: Registration Form -->
        <div id="register-step" class="space-y-5">
            <form method="POST" action="{{ route('register.send-otp') }}" id="register-form">
        @csrf

        <!-- Name -->
        <div>
                    <label for="name" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 transition-colors">სრული სახელი</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full px-4 py-3 rounded-lg border focus:outline-none transition-colors bg-gray-200 dark:bg-[#36393F] border-gray-300 dark:border-[#4285F4] text-gray-900 dark:text-white"
                    />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 transition-colors">ელფოსტის მისამართი</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="w-full px-4 py-3 rounded-lg border focus:outline-none transition-colors bg-gray-200 dark:bg-[#36393F] border-gray-300 dark:border-[#4285F4] text-gray-900 dark:text-white"
                    />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 transition-colors">ტელეფონის ნომერი</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" style="color: #B9BBBE;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="555 00 00 00"
                            required
                            autocomplete="tel"
                            class="w-full px-4 py-3 pl-12 rounded-lg border focus:outline-none transition-colors bg-gray-200 dark:bg-[#36393F] border-gray-300 dark:border-[#4285F4] text-gray-900 dark:text-white"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 transition-colors">
                        SMS-ით გამოგიგზავნებათ დადასტურების კოდი
                    </p>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button
                        type="submit"
                        id="send-otp-btn"
                        class="w-full py-3 px-4 rounded-lg font-semibold text-sm uppercase tracking-wide transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90 bg-blue-600 text-white"
                    >
                        <span id="send-otp-text">გაგზავნა დადასტურების კოდი</span>
                        <span id="send-otp-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            გაგზავნა...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Step 2: OTP Verification -->
        <div id="otp-step" class="hidden space-y-5">
            <div class="text-center mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
                    შეიყვანეთ 6-ნიშნა კოდი, რომელიც გამოგეგზავნათ
                </p>
                <p class="text-sm font-semibold mt-1 text-gray-900 dark:text-white transition-colors" id="otp-phone-display"></p>
            </div>

            <form method="POST" action="{{ route('register.verify-otp') }}" id="otp-form">
                @csrf
                <input type="hidden" name="name" id="otp-name-input">
                <input type="hidden" name="email" id="otp-email-input">
                <input type="hidden" name="phone" id="otp-phone-input">

                <!-- OTP Input -->
                <div>
                    <label for="otp" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 transition-colors">დადასტურების კოდი</label>
                    <div class="mt-1">
                        <input
                            id="otp"
                            type="text"
                            name="otp"
                            placeholder="000000"
                            maxlength="6"
                            required
                            autofocus
                            pattern="[0-9]{6}"
                            class="w-full px-4 py-3 rounded-lg border text-center text-2xl tracking-widest font-mono focus:outline-none transition-colors bg-gray-200 dark:bg-[#36393F] border-gray-300 dark:border-[#4285F4] text-gray-900 dark:text-white"
                            onfocus="this.classList.add('dark:border-[#4285F4]', 'border-blue-500')"
                            onblur="this.classList.remove('border-blue-500')"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button
                        type="button"
                        id="resend-otp-btn"
                        class="text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-blue-400 hover:text-blue-300"
                    >
                        კოდის ხელახლა გაგზავნა
                    </button>
                    <span id="resend-timer" class="text-sm text-gray-500"></span>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button
                        type="submit"
                        id="verify-otp-btn"
                        class="w-full py-3 px-4 rounded-lg font-semibold text-sm uppercase tracking-wide transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90 bg-blue-600 text-white"
                    >
                        <span id="verify-otp-text">დადასტურება და ანგარიშის შექმნა</span>
                        <span id="verify-otp-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            დადასტურება...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
                უკვე გაქვთ ანგარიში?
                <a href="{{ route('login') }}" class="font-semibold ml-1 transition-colors text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                    შესვლა
                </a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('register-form');
            const otpStep = document.getElementById('otp-step');
            const registerStep = document.getElementById('register-step');
            const otpPhoneDisplay = document.getElementById('otp-phone-display');
            const otpNameInput = document.getElementById('otp-name-input');
            const otpEmailInput = document.getElementById('otp-email-input');
            const otpPhoneInput = document.getElementById('otp-phone-input');
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const sendOtpText = document.getElementById('send-otp-text');
            const sendOtpLoading = document.getElementById('send-otp-loading');
            const resendBtn = document.getElementById('resend-otp-btn');
            const resendTimer = document.getElementById('resend-timer');
            const verifyOtpBtn = document.getElementById('verify-otp-btn');
            const verifyOtpText = document.getElementById('verify-otp-text');
            const verifyOtpLoading = document.getElementById('verify-otp-loading');
            let resendCountdown = 0;

            // Format phone number input
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });

            // Format OTP input
            const otpInput = document.getElementById('otp');
            otpInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
            });

            // Handle registration form submission
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(registerForm);
                sendOtpBtn.disabled = true;
                sendOtpText.classList.add('hidden');
                sendOtpLoading.classList.remove('hidden');

                fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        registerStep.classList.add('hidden');
                        otpStep.classList.remove('hidden');
                        const phone = '+995' + formData.get('phone');
                        otpPhoneDisplay.textContent = phone;
                        otpPhoneInput.value = phone;
                        otpNameInput.value = formData.get('name');
                        otpEmailInput.value = formData.get('email');
                        otpInput.focus();
                        startResendTimer();
                    } else {
                        alert(data.message || 'OTP-ის გაგზავნა ვერ მოხერხდა. გთხოვთ, სცადოთ ხელახლა.');
                        sendOtpBtn.disabled = false;
                        sendOtpText.classList.remove('hidden');
                        sendOtpLoading.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('დაფიქსირდა შეცდომა. გთხოვთ, სცადოთ ხელახლა.');
                    sendOtpBtn.disabled = false;
                    sendOtpText.classList.remove('hidden');
                    sendOtpLoading.classList.add('hidden');
                });
            });

            // Handle resend OTP
            resendBtn.addEventListener('click', function() {
                if (resendCountdown > 0) return;

                resendBtn.disabled = true;
                const formData = new FormData(registerForm);

                fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        startResendTimer();
                        alert('დადასტურების კოდი წარმატებით გაიგზავნა!');
                    } else {
                        alert(data.message || 'OTP-ის ხელახლა გაგზავნა ვერ მოხერხდა. გთხოვთ, სცადოთ ხელახლა.');
                        resendBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('დაფიქსირდა შეცდომა. გთხოვთ, სცადოთ ხელახლა.');
                    resendBtn.disabled = false;
                });
            });

            // Start resend timer
            function startResendTimer() {
                resendCountdown = 60;
                resendBtn.disabled = true;
                resendTimer.textContent = `ხელახლა გაგზავნა ${resendCountdown}წმ-ში`;

                const timer = setInterval(() => {
                    resendCountdown--;
                    if (resendCountdown > 0) {
                        resendTimer.textContent = `ხელახლა გაგზავნა ${resendCountdown}წმ-ში`;
                    } else {
                        clearInterval(timer);
                        resendTimer.textContent = '';
                        resendBtn.disabled = false;
                    }
                }, 1000);
            }

            // Handle OTP form submission
            document.getElementById('otp-form').addEventListener('submit', function(e) {
                verifyOtpBtn.disabled = true;
                verifyOtpText.classList.add('hidden');
                verifyOtpLoading.classList.remove('hidden');
            });

            // Check if we're on OTP step (from server redirect)
            @if(session('otp_sent') && old('phone'))
                registerStep.classList.add('hidden');
                otpStep.classList.remove('hidden');
                otpPhoneDisplay.textContent = '{{ old("phone") }}';
                otpPhoneInput.value = '{{ old("phone") }}';
                otpNameInput.value = '{{ old("name") }}';
                otpEmailInput.value = '{{ old("email") }}';
                otpInput.focus();
                startResendTimer();
            @endif
        });
    </script>
</x-guest-layout>
