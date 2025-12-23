<x-guest-layout>
    <div class="w-full">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center">
                <span class="text-white text-3xl font-bold">P</span>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold mb-2 text-white">ავტორიზაცია</h2>
            <p class="text-sm text-gray-400">მოგესალმებით</p>
        </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Step 1: Phone Number Input -->
        <div id="phone-step" class="space-y-5">
            <form method="POST" action="{{ route('login.send-otp') }}" id="phone-form">
        @csrf

                <!-- Phone Number -->
        <div>
                    <label for="phone" class="block text-sm font-medium mb-2 text-gray-300">მობილურის ნომერი ან ID</label>
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
                            autofocus
                            autocomplete="tel"
                            class="w-full px-4 py-3 pl-12 rounded-lg border focus:outline-none transition-colors"
                            style="background-color: #36393F; border-color: #4285F4; color: #ffffff;"
                            onfocus="this.style.borderColor='#4285F4'"
                            onblur="this.style.borderColor='#4285F4'"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button
                        type="submit"
                        id="send-otp-btn"
                        class="w-full py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90 bg-blue-600 text-white"
                    >
                        <span id="send-otp-text">გაგრძელება</span>
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
                <p class="text-sm text-gray-400">
                    შეიყვანეთ 6-ნიშნა კოდი, რომელიც გამოგეგზავნათ
                </p>
                <p class="text-sm font-semibold mt-1 text-white" id="otp-phone-display"></p>
            </div>

            <form method="POST" action="{{ route('login.verify-otp') }}" id="otp-form">
                @csrf
                <input type="hidden" name="phone" id="otp-phone-input">

                <!-- OTP Input -->
                <div>
                    <label for="otp" class="block text-sm font-medium mb-2 text-gray-300">დადასტურების კოდი</label>
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
                            class="w-full px-4 py-3 rounded-lg border text-center text-2xl tracking-widest font-mono focus:outline-none transition-colors"
                            style="background-color: #36393F; border-color: #4285F4; color: #ffffff;"
                            onfocus="this.style.borderColor='#4285F4'"
                            onblur="this.style.borderColor='#4285F4'"
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
                        class="w-full py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90 bg-blue-600 text-white"
                    >
                        <span id="verify-otp-text">დადასტურება და შესვლა</span>
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

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-400">
                არ გაქვთ ანგარიში?
                <a href="{{ route('register') }}" class="font-semibold ml-1 transition-colors text-blue-400 hover:text-blue-300">
                    დარეგისტრირდით
                </a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneForm = document.getElementById('phone-form');
            const otpStep = document.getElementById('otp-step');
            const phoneStep = document.getElementById('phone-step');
            const otpPhoneDisplay = document.getElementById('otp-phone-display');
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

            // Format phone number input - allow both phone and ID
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                // Allow alphanumeric for ID, or just numbers for phone
                // Remove formatting but keep alphanumeric
                let value = e.target.value.replace(/[^a-zA-Z0-9\s]/g, '');
                e.target.value = value;
            });

            // Format OTP input
            const otpInput = document.getElementById('otp');
            otpInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
            });

            // Handle phone form submission
            phoneForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(phoneForm);
                sendOtpBtn.disabled = true;
                sendOtpText.classList.add('hidden');
                sendOtpLoading.classList.remove('hidden');

                fetch(phoneForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        phoneStep.classList.add('hidden');
                        otpStep.classList.remove('hidden');
                        const phone = formData.get('phone');
                        otpPhoneDisplay.textContent = phone;
                        otpPhoneInput.value = phone;
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
                const formData = new FormData(phoneForm);

                fetch(phoneForm.action, {
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
                phoneStep.classList.add('hidden');
                otpStep.classList.remove('hidden');
                otpPhoneDisplay.textContent = '{{ old("phone") }}';
                otpPhoneInput.value = '{{ old("phone") }}';
                otpInput.focus();
                startResendTimer();
            @endif
        });
    </script>
</x-guest-layout>
