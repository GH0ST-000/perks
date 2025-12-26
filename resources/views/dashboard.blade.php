<x-dashboard-layout>
    <!-- Welcome Section -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0 0 8px 0;">გამარჯობა, {{ auth()->user()->name }}</h1>
        <p style="font-size: 16px; color: #a0aec0; margin: 0;">Welcome back to your perks dashboard.</p>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- Left Column: P PERKS Card -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px; position: relative; overflow: hidden;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <span style="font-size: 24px; font-weight: 700; color: #ffffff;">P PERKS</span>
                        <span style="font-size: 12px; font-weight: 600; color: #10b981; background-color: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 12px;">ACTIVE</span>
                    </div>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.7); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 1px;">CARD HOLDER</p>
                    <p style="font-size: 20px; font-weight: 600; color: #ffffff; margin: 0 0 8px 0;">{{ auth()->user()->name }}</p>
                    <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0;">P-{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div style="width: 80px; height: 80px; background-color: rgba(255,255,255,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <span class="material-icons" style="font-size: 48px; color: rgba(255,255,255,0.5);">qr_code</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats Cards -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Balance Card -->
            <div style="background-color: #252836; border-radius: 16px; padding: 20px; border: 1px solid #2d3142; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span style="font-size: 12px; font-weight: 500; color: #a0aec0; text-transform: uppercase;">ბალანსი</span>
                    <span class="material-icons" style="font-size: 20px; color: #a0aec0; cursor: pointer;">visibility</span>
                </div>
                <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0;">{{ auth()->user()->p_coins ?? 0 }} P</p>
            </div>

            <!-- Savings Card -->
            <div style="background-color: #252836; border-radius: 16px; padding: 20px; border: 1px solid #2d3142; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span style="font-size: 12px; font-weight: 500; color: #a0aec0; text-transform: uppercase;">დანაზოგი</span>
                    <span class="material-icons" style="font-size: 20px; color: #a0aec0;">trending_up</span>
                </div>
                <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0;">0 ₾</p>
            </div>

            <!-- Visits Card -->
            <div style="background-color: #252836; border-radius: 16px; padding: 20px; border: 1px solid #2d3142; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span style="font-size: 12px; font-weight: 500; color: #a0aec0; text-transform: uppercase;">ვიზიტები</span>
                    <span class="material-icons" style="font-size: 20px; color: #a0aec0;">location_on</span>
                </div>
                <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0;">{{ auth()->user()->visits()->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Exclusive Offer Section -->
    <div style="margin-top: 32px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <span class="material-icons" style="font-size: 24px; color: #a855f7;">star</span>
            <h2 style="font-size: 20px; font-weight: 600; color: #ffffff; margin: 0;">Exclusive For You</h2>
        </div>

        <div style="background-color: #252836; border-radius: 16px; padding: 24px; border: 1px solid #2d3142;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <span style="font-size: 12px; font-weight: 600; color: #a855f7; background-color: rgba(168,85,247,0.1); padding: 6px 12px; border-radius: 12px;">INDIVIDUAL OFFER</span>
                <span style="font-size: 12px; color: #a0aec0;">{{ date('m/d/Y', strtotime('+30 days')) }}</span>
            </div>
            <h3 style="font-size: 24px; font-weight: 700; color: #ffffff; margin: 0 0 8px 0;">Birthday Special</h3>
            <p style="font-size: 16px; color: #a0aec0; margin: 0 0 20px 0;">Get a free dessert with any main course!</p>
            <a href="#" style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #3b82f6; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#60a5fa';" onmouseout="this.style.color='#3b82f6';">
                Redeem Offer
                <span class="material-icons" style="font-size: 18px;">arrow_forward</span>
            </a>
        </div>
    </div>
</x-dashboard-layout>
