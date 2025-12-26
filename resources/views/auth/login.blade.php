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
            <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white transition-colors">ავტორიზაცია</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">მოგესალმებით</p>
        </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Step 1: Phone Number Input -->
        <div id="phone-step" class="space-y-5">
            <form method="POST" action="{{ route('login.send-otp') }}" id="phone-form">
        @csrf

                <!-- Phone Number -->
        <div>
                    <label for="phone" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 transition-colors">მობილურის ნომერი</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 transition-colors text-sm">+995</span>
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
                            class="w-full px-4 py-3 pl-16 rounded-lg border focus:outline-none transition-colors bg-gray-200 dark:bg-[#36393F] border-gray-300 dark:border-[#4285F4] text-gray-900 dark:text-white"
                            onfocus="this.classList.add('dark:border-[#4285F4]', 'border-blue-500')"
                            onblur="this.classList.remove('border-blue-500')"
                            maxlength="9"
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
                <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
                    შეიყვანეთ 6-ნიშნა კოდი, რომელიც გამოგეგზავნათ
                </p>
                <p class="text-sm font-semibold mt-1 text-gray-900 dark:text-white transition-colors" id="otp-phone-display"></p>
            </div>

            <form method="POST" action="{{ route('login.verify-otp') }}" id="otp-form">
                @csrf
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
                    <span id="resend-timer" class="text-sm text-gray-500 dark:text-gray-500"></span>
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
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors">
                არ გაქვთ ანგარიში?
                <a href="{{ route('register') }}" class="font-semibold ml-1 transition-colors text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                    დარეგისტრირდით
                </a>
            </p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-300 ease-in-out opacity-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3 min-w-[300px] max-w-md">
            <div id="toast-icon" class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center">
                <!-- Icon will be inserted here -->
            </div>
            <div class="flex-1">
                <p id="toast-title" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                <p id="toast-message" class="text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
            <button onclick="hideToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Toast notification functions
        function showToast(type, title, message) {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toast-icon');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');

            // Set icon based on type
            let iconHtml = '';
            let iconBg = '';
            if (type === 'success') {
                iconBg = 'bg-green-100 dark:bg-green-900/30';
                iconHtml = '<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                iconBg = 'bg-red-100 dark:bg-red-900/30';
                iconHtml = '<svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                iconBg = 'bg-blue-100 dark:bg-blue-900/30';
                iconHtml = '<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }

            toastIcon.className = `flex-shrink-0 w-10 h-10 ${iconBg} rounded-full flex items-center justify-center`;
            toastIcon.innerHTML = iconHtml;
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            // Show toast with animation
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');

            // Auto hide after 4 seconds
            setTimeout(() => {
                hideToast();
            }, 4000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
        }

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

            // Format phone number input - only numbers, max 9 digits
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                // Remove all non-numeric characters
                let value = e.target.value.replace(/\D/g, '');
                // Limit to 9 digits
                value = value.slice(0, 9);
                e.target.value = value;
            });

            // Validate phone number format (Georgian: 9 digits)
            function validatePhoneNumber(phone) {
                // Remove all non-numeric characters
                const cleaned = phone.replace(/\D/g, '');
                // Check if it's exactly 9 digits
                return /^[0-9]{9}$/.test(cleaned);
            }

            // Format OTP input
            const otpInput = document.getElementById('otp');
            otpInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
            });

            // Handle phone form submission
            phoneForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const phoneValue = phoneInput.value.trim();
                
                // Validate phone number
                if (!validatePhoneNumber(phoneValue)) {
                    showToast('error', 'შეცდომა', 'გთხოვთ, შეიყვანოთ სწორი ქართული ტელეფონის ნომერი (9 ციფრი)');
                    phoneInput.focus();
                    return;
                }

                // Ensure phone number has +995 prefix
                const phone = phoneValue.startsWith('+995') ? phoneValue : '+995' + phoneValue;
                
                const formData = new FormData();
                formData.append('phone', phoneValue); // Send without +995, server will add it
                formData.append('_token', document.querySelector('input[name="_token"]').value);

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
                        otpPhoneDisplay.textContent = phone;
                        otpPhoneInput.value = phone;
                        otpInput.focus();
                        startResendTimer();
                        showToast('success', 'წარმატება', 'დადასტურების კოდი გაიგზავნა!');
                    } else {
                        showToast('error', 'შეცდომა', data.message || 'OTP-ის გაგზავნა ვერ მოხერხდა. გთხოვთ, სცადოთ ხელახლა.');
                        sendOtpBtn.disabled = false;
                        sendOtpText.classList.remove('hidden');
                        sendOtpLoading.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'შეცდომა', 'დაფიქსირდა შეცდომა. გთხოვთ, სცადოთ ხელახლა.');
                    sendOtpBtn.disabled = false;
                    sendOtpText.classList.remove('hidden');
                    sendOtpLoading.classList.add('hidden');
                });
            });

            // Handle resend OTP
            resendBtn.addEventListener('click', function() {
                if (resendCountdown > 0) return;

                resendBtn.disabled = true;
                const phoneValue = phoneInput.value.trim();
                const phone = phoneValue.startsWith('+995') ? phoneValue : '+995' + phoneValue;
                
                const formData = new FormData();
                formData.append('phone', phoneValue);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

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
                        showToast('success', 'წარმატება', 'დადასტურების კოდი წარმატებით გაიგზავნა!');
                    } else {
                        showToast('error', 'შეცდომა', data.message || 'OTP-ის ხელახლა გაგზავნა ვერ მოხერხდა. გთხოვთ, სცადოთ ხელახლა.');
                        resendBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'შეცდომა', 'დაფიქსირდა შეცდომა. გთხოვთ, სცადოთ ხელახლა.');
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
