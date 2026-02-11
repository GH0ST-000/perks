<section>
    <header style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="material-icons" style="font-size: 24px; color: #3b82f6;">person</span>
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">
                პროფილის ინფორმაცია
        </h2>
        </div>
        <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">
            განაახლეთ თქვენი ანგარიშის პროფილის ინფორმაცია და ელ.ფოსტის მისამართი
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 24px;">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                პროფილის ფოტო
            </label>
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="position: relative;">
                    @php
                        $user = auth()->user();
                        $profilePhoto = $user->profile_photo;
                        $photoUrl = null;
                        if ($profilePhoto) {
                            if (str_starts_with($profilePhoto, 'http://') || str_starts_with($profilePhoto, 'https://')) {
                                $photoUrl = $profilePhoto;
                            } elseif (str_starts_with($profilePhoto, 'data:image')) {
                                $photoUrl = $profilePhoto;
                            } else {
                                $photoUrl = asset('storage/' . ltrim($profilePhoto, '/'));
                            }
                        }
                    @endphp
                    <div id="profile-photo-preview" style="width: 80px; height: 80px; border-radius: 50%; background-color: #374151; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid #2d3142;">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;" id="current-photo">
                        @else
                            <span class="material-icons" style="font-size: 40px; color: #9ca3af;">person</span>
                        @endif
                    </div>
                </div>
                <div style="flex: 1;">
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;" onchange="previewProfilePhoto(event)">
                    <label for="profile_photo" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; border: none; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <span class="material-icons" style="font-size: 20px;">upload</span>
                        ფოტოს ატვირთვა
                    </label>
                    <p style="font-size: 12px; color: #9ca3af; margin: 8px 0 0 0;">JPG, PNG, GIF არაუმეტეს 2MB</p>
                </div>
            </div>
            @error('profile_photo')
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Name -->
        <div>
            <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                სახელი
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
                style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
            />
            @error('name')
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                ელ.ფოსტა
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
                style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
            />
            @error('email')
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 12px; padding: 12px; background-color: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <p style="font-size: 14px; color: #92400e; margin: 0 0 8px 0;">
                        თქვენი ელ.ფოსტის მისამართი არ არის დადასტურებული.
                    </p>
                    <button form="send-verification" type="submit" style="font-size: 14px; font-weight: 500; color: #f59e0b; background: none; border: none; text-decoration: underline; cursor: pointer; padding: 0;">
                        დააჭირეთ აქ, რომ ხელახლა გაგზავნოთ დამადასტურებელი ელ.ფოსტა
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 8px; font-size: 14px; font-weight: 500; color: #10b981;">
                            ახალი დამადასტურებელი ბმული გაიგზავნა თქვენს ელ.ფოსტაზე.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone Number (optional) -->
        <div>
            <label for="phone" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                ტელეფონის ნომერი <span style="color: var(--text-tertiary); font-weight: 400;">(არასავალდებულო)</span>
            </label>
            <input 
                id="phone" 
                name="phone" 
                type="tel" 
                value="{{ old('phone', $user->phone ?? '') }}" 
                autocomplete="tel"
                placeholder="+995 XXX XXX XXX"
                style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
            />
            @error('phone')
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Save Button -->
        <div style="display: flex; align-items: center; gap: 16px; padding-top: 8px;">
            <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <span class="material-icons" style="font-size: 20px;">save</span>
                შენახვა
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    style="font-size: 14px; color: #10b981; font-weight: 500; display: flex; align-items: center; gap: 6px;"
                >
                    <span class="material-icons" style="font-size: 18px;">check_circle</span>
                    წარმატებით შეინახა
                </p>
            @endif
        </div>
    </form>

    <script>
        function previewProfilePhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile-photo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</section>
