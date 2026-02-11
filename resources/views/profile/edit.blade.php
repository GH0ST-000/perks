<x-dashboard-layout>
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="material-icons" style="font-size: 32px; color: #3b82f6;">account_circle</span>
            <h1 style="font-size: 32px; font-weight: 700; color: var(--text-primary); margin: 0;">პროფილის რედაქტირება</h1>
        </div>
        <p style="font-size: 16px; color: var(--text-secondary); margin: 0;">განაახლეთ თქვენი პროფილის ინფორმაცია და უსაფრთხოების პარამეტრები</p>
    </div>

    <!-- Profile Sections -->
    <div style="display: flex; flex-direction: column; gap: 24px; max-width: 1200px;">
        <!-- Profile Information Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 32px; border: none; box-shadow: var(--shadow-card);">
                    @include('profile.partials.update-profile-information-form')
            </div>

        <!-- Update Password Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 32px; border: none; box-shadow: var(--shadow-card);">
                    @include('profile.partials.update-password-form')
            </div>

        <!-- Delete Account Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 32px; border: none; box-shadow: var(--shadow-card);">
                    @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-dashboard-layout>
