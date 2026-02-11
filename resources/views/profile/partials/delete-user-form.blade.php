<section>
    <header style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="material-icons" style="font-size: 24px; color: #ef4444;">delete_forever</span>
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">
                ანგარიშის წაშლა
        </h2>
        </div>
        <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">
            როდესაც თქვენი ანგარიში წაიშლება, მისი ყველა რესურსი და მონაცემი სამუდამოდ წაიშლება. ანგარიშის წაშლამდე, გთხოვთ გადმოწეროთ ნებისმიერი მონაცემი ან ინფორმაცია, რომლის შენარჩუნება გსურთ.
        </p>
    </header>

    <!-- Warning Box -->
    <div style="background-color: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
        <div style="display: flex; gap: 12px;">
            <span class="material-icons" style="font-size: 24px; color: #ef4444; flex-shrink: 0;">warning</span>
            <div>
                <h3 style="font-size: 14px; font-weight: 600; color: #ef4444; margin: 0 0 4px 0;">გაფრთხილება</h3>
                <p style="font-size: 13px; color: #fca5a5; margin: 0; line-height: 1.5;">
                    ეს მოქმედება შეუქცევადია. თქვენი ყველა მონაცემი, ტრანზაქციები და ბალანსი სამუდამოდ წაიშლება.
                </p>
            </div>
        </div>
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
    >
        <span class="material-icons" style="font-size: 20px;">delete</span>
        ანგარიშის წაშლა
    </button>

    <!-- Delete Confirmation Modal -->
    <div
        x-data="{ show: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }"
        x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') show = true"
        x-on:close.window="show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 16px;"
        x-cloak
    >
        <!-- Backdrop -->
        <div
            x-show="show"
            x-on:click="show = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="position: absolute; inset: 0; background-color: rgba(0, 0, 0, 0.75);"
        ></div>

        <!-- Modal Content -->
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            style="position: relative; background-color: #252836; border-radius: 16px; max-width: 500px; width: 100%; border: 1px solid #2d3142; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.04);"
            x-on:click.stop
        >
            <form method="post" action="{{ route('profile.destroy') }}" style="padding: 32px;">
            @csrf
            @method('delete')

                <!-- Modal Header -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; background-color: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-icons" style="font-size: 28px; color: #ef4444;">warning</span>
                    </div>
                    <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">
                        დარწმუნებული ხართ?
            </h2>
                </div>

                <p style="font-size: 14px; color: var(--text-secondary); margin: 0 0 24px 0; line-height: 1.6;">
                    როდესაც თქვენი ანგარიში წაიშლება, მისი ყველა რესურსი და მონაცემი სამუდამოდ წაიშლება. გთხოვთ შეიყვანოთ თქვენი პაროლი, რათა დაადასტუროთ, რომ გსურთ თქვენი ანგარიშის სამუდამოდ წაშლა.
                </p>

                <!-- Password Input -->
                <div style="margin-bottom: 24px;">
                    <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">
                        პაროლი
                    </label>
                    <input
                    id="password"
                    name="password"
                    type="password"
                        placeholder="შეიყვანეთ თქვენი პაროლი"
                        style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-hover); border-radius: 10px; color: var(--text-primary); font-size: 14px; transition: all 0.2s;"
                        onfocus="this.style.borderColor='#ef4444'; this.style.outline='none'; this.style.backgroundColor='var(--bg-primary)';"
                        onblur="this.style.borderColor='var(--border-hover)'; this.style.backgroundColor='var(--bg-secondary)';"
                    />
                    @if($errors->userDeletion->has('password'))
                        <p style="margin-top: 8px; font-size: 14px; color: #ef4444;">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
            </div>

                <!-- Action Buttons -->
                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button
                        type="button"
                        x-on:click="show = false"
                        style="padding: 10px 20px; background-color: var(--bg-secondary); color: var(--text-primary); font-size: 14px; font-weight: 500; border-radius: 10px; border: 1px solid var(--border-hover); cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='var(--bg-hover)';"
                        onmouseout="this.style.backgroundColor='var(--bg-secondary)';"
                    >
                        გაუქმება
                    </button>

                    <button
                        type="submit"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                    >
                        <span class="material-icons" style="font-size: 18px;">delete</span>
                        ანგარიშის წაშლა
                    </button>
            </div>
        </form>
        </div>
    </div>
</section>
