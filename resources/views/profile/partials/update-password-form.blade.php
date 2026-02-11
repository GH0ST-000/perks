<section>
    <header style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="material-icons" style="font-size: 24px; color: #10b981;">lock</span>
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">
                პაროლის განახლება
        </h2>
        </div>
        <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">
            დარწმუნდით, რომ თქვენი ანგარიში იყენებს გრძელ, შემთხვევით პაროლს უსაფრთხოებისთვის
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="display: flex; flex-direction: column; gap: 24px;">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                მიმდინარე პაროლი
            </label>
            <div style="position: relative;">
                <input 
                    id="update_password_current_password" 
                    name="current_password" 
                    type="password" 
                    autocomplete="current-password"
                    style="width: 100%; padding: 12px 48px 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                    onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
                />
                <button type="button" onclick="togglePassword('update_password_current_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 8px;">
                    <span class="material-icons" style="font-size: 20px; color: var(--text-tertiary);">visibility</span>
                </button>
            </div>
            @if($errors->updatePassword->has('current_password'))
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                ახალი პაროლი
            </label>
            <div style="position: relative;">
                <input 
                    id="update_password_password" 
                    name="password" 
                    type="password" 
                    autocomplete="new-password"
                    style="width: 100%; padding: 12px 48px 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                    onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
                    oninput="checkPasswordStrength(this.value)"
                />
                <button type="button" onclick="togglePassword('update_password_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 8px;">
                    <span class="material-icons" style="font-size: 20px; color: var(--text-tertiary);">visibility</span>
                </button>
            </div>
            <!-- Password Strength Indicator -->
            <div id="password-strength" style="display: none; margin-top: 8px;">
                <div style="display: flex; gap: 4px; margin-bottom: 6px;">
                    <div id="strength-bar-1" style="flex: 1; height: 4px; background-color: #2d3142; border-radius: 2px; transition: all 0.3s;"></div>
                    <div id="strength-bar-2" style="flex: 1; height: 4px; background-color: #2d3142; border-radius: 2px; transition: all 0.3s;"></div>
                    <div id="strength-bar-3" style="flex: 1; height: 4px; background-color: #2d3142; border-radius: 2px; transition: all 0.3s;"></div>
                    <div id="strength-bar-4" style="flex: 1; height: 4px; background-color: #2d3142; border-radius: 2px; transition: all 0.3s;"></div>
                </div>
                <p id="strength-text" style="font-size: 12px; color: #9ca3af; margin: 0;"></p>
            </div>
            @if($errors->updatePassword->has('password'))
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $errors->updatePassword->first('password') }}</p>
            @endif
            <p style="margin-top: 8px; font-size: 12px; color: #9ca3af;">მინიმუმ 8 სიმბოლო, ერთი დიდი ასო, ერთი ციფრი და ერთი სპეციალური სიმბოლო</p>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                პაროლის დადასტურება
            </label>
            <div style="position: relative;">
                <input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    autocomplete="new-password"
                    style="width: 100%; padding: 12px 48px 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                    onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
                />
                <button type="button" onclick="togglePassword('update_password_password_confirmation')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 8px;">
                    <span class="material-icons" style="font-size: 20px; color: var(--text-tertiary);">visibility</span>
                </button>
            </div>
            @if($errors->updatePassword->has('password_confirmation'))
                <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <!-- Save Button -->
        <div style="display: flex; align-items: center; gap: 16px; padding-top: 8px;">
            <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <span class="material-icons" style="font-size: 20px;">lock_reset</span>
                პაროლის განახლება
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    style="font-size: 14px; color: #10b981; font-weight: 500; display: flex; align-items: center; gap: 6px;"
                >
                    <span class="material-icons" style="font-size: 18px;">check_circle</span>
                    პაროლი წარმატებით განახლდა
                </p>
            @endif
        </div>
    </form>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling.querySelector('.material-icons');
            
            if (field.type === 'password') {
                field.type = 'text';
                button.textContent = 'visibility_off';
            } else {
                field.type = 'password';
                button.textContent = 'visibility';
            }
        }

        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('password-strength');
            const strengthText = document.getElementById('strength-text');
            const bars = [
                document.getElementById('strength-bar-1'),
                document.getElementById('strength-bar-2'),
                document.getElementById('strength-bar-3'),
                document.getElementById('strength-bar-4')
            ];

            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            // Reset all bars
            bars.forEach(bar => {
                bar.style.backgroundColor = '#2d3142';
            });

            const strengthLevels = [
                { text: 'სუსტი პაროლი', color: '#ef4444' },
                { text: 'საშუალო პაროლი', color: '#f59e0b' },
                { text: 'კარგი პაროლი', color: '#10b981' },
                { text: 'ძლიერი პაროლი', color: '#10b981' }
            ];

            for (let i = 0; i < strength; i++) {
                bars[i].style.backgroundColor = strengthLevels[strength - 1].color;
            }

            strengthText.textContent = strengthLevels[strength - 1].text;
            strengthText.style.color = strengthLevels[strength - 1].color;
        }
    </script>
</section>
