<x-dashboard-layout>
    <div style="max-width: 1400px; margin: 0 auto; padding: 0;">
        <!-- Success Message -->
        @if(session('success'))
            <div id="success-message" style="background-color: #10b981; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('success-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div id="error-message" style="background-color: #ef4444; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-weight: 600;">შეცდომა</span>
                    <button onclick="document.getElementById('error-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
                </div>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Header -->
        <div style="margin-bottom: 32px;">
            <h1 style="font-size: 32px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">Perks Family</h1>
            <p style="color: #ef4444; font-size: 14px; margin: 0; display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
                გააზიარე ბენეფიტები შენს საყვარელ ადამიანებს
            </p>
        </div>

        <!-- Add Family Member Form -->
        <div style="background-color: var(--bg-card); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-card); margin-bottom: 32px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" x2="19" y1="8" y2="14"/>
                        <line x1="22" x2="16" y1="11" y2="11"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">ნათესავის დამატება</h2>
                    <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">შეავსეთ ინფორმაცია ქვემოთ</p>
                </div>
            </div>

            <form action="{{ route('family-members.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <!-- First Name -->
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">სახელი</label>
                        <input 
                            type="text" 
                            name="first_name" 
                            value="{{ old('first_name') }}"
                            placeholder="მაგ: მარიამ"
                            required
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; background-color: var(--bg-input); color: var(--text-primary); transition: all 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">გვარი</label>
                        <input 
                            type="text" 
                            name="last_name" 
                            value="{{ old('last_name') }}"
                            placeholder="მაგ: ბერიძე"
                            required
                            style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; background-color: var(--bg-input); color: var(--text-primary); transition: all 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';">
                    </div>
                </div>

                <!-- Personal Number -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">პირადი ნომერი</label>
                    <input 
                        type="text" 
                        name="personal_number" 
                        value="{{ old('personal_number') }}"
                        placeholder="11 ციფრი"
                        maxlength="11"
                        pattern="[0-9]{11}"
                        required
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; background-color: var(--bg-input); color: var(--text-primary); transition: all 0.2s;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                        onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                </div>

                <!-- Relationship -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">კავშირი</label>
                    <select 
                        name="relationship" 
                        required
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; background-color: var(--bg-input); color: var(--text-primary); transition: all 0.2s; cursor: pointer;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                        onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';">
                        <option value="">აირჩიეთ კავშირი</option>
                        <option value="spouse" {{ old('relationship') == 'spouse' ? 'selected' : '' }}>მეუღლე</option>
                        <option value="child" {{ old('relationship') == 'child' ? 'selected' : '' }}>შვილი</option>
                        <option value="parent" {{ old('relationship') == 'parent' ? 'selected' : '' }}>მშობელი</option>
                        <option value="sibling" {{ old('relationship') == 'sibling' ? 'selected' : '' }}>ძმა/და</option>
                        <option value="other" {{ old('relationship') == 'other' ? 'selected' : '' }}>სხვა</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    style="width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';">
                    <span>დამატება</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" x2="19" y1="8" y2="14"/>
                        <line x1="22" x2="16" y1="11" y2="11"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Current Family Members List -->
        @if($familyMembers->count() > 0)
            <div style="background-color: var(--bg-card); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-card);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">ოჯახის წევრები</h2>
                            <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">სულ {{ $familyMembers->count() }} წევრი</p>
                        </div>
                    </div>
                    <div style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 4px;">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        {{ $familyMembers->count() }} წევრი
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                    @foreach($familyMembers as $member)
                        <div style="background-color: var(--bg-secondary); border-radius: 12px; padding: 20px; border: 1px solid var(--border-color); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <div style="display: flex; align-items: start; gap: 12px; margin-bottom: 16px;">
                                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <span style="color: #ffffff; font-size: 18px; font-weight: 600;">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h3 style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $member->full_name }}</h3>
                                    <span style="display: inline-block; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 2px 8px; border-radius: 6px; font-size: 12px; font-weight: 500;">{{ $member->relationship_name }}</span>
                                </div>
                            </div>

                            <div style="background-color: var(--bg-card); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                                <p style="font-size: 12px; color: var(--text-secondary); margin: 0 0 4px 0;">პირადი ნომერი</p>
                                <p style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0; font-family: monospace;">{{ $member->personal_number }}</p>
                            </div>

                            <div style="display: flex; gap: 8px;">
                                <button 
                                    onclick="showDeleteModal({{ $member->id }}, '{{ $member->full_name }}')"
                                    style="flex: 1; padding: 8px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.backgroundColor='#dc2626';"
                                    onmouseout="this.style.backgroundColor='#ef4444';">
                                    წაშლა
                                </button>
                                <form id="delete-form-{{ $member->id }}" action="{{ route('family-members.destroy', $member) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div style="background-color: var(--bg-card); border-radius: 16px; padding: 48px; box-shadow: var(--shadow-card); text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 8px 0;">ჯერ არ გაქვთ დამატებული ოჯახის წევრები</h3>
                <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">გამოიყენეთ ზემოთ მოცემული ფორმა ოჯახის წევრის დასამატებლად</p>
            </div>
        @endif

        <!-- Custom Delete Confirmation Modal -->
        <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); animation: fadeIn 0.2s ease-in-out;">
            <div style="background-color: var(--bg-card); border-radius: 16px; padding: 32px; max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: slideUp 0.3s ease-out; position: relative;">
                <!-- Icon -->
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        <line x1="10" x2="10" y1="11" y2="17"/>
                        <line x1="14" x2="14" y1="11" y2="17"/>
                    </svg>
                </div>

                <!-- Title -->
                <h3 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 12px 0; text-align: center;">დარწმუნებული ხართ?</h3>
                
                <!-- Message -->
                <p style="font-size: 14px; color: var(--text-secondary); margin: 0 0 24px 0; text-align: center; line-height: 1.6;">
                    გსურთ <strong id="memberName" style="color: var(--text-primary);"></strong>-ის წაშლა? <br>
                    <span style="font-size: 13px; color: #ef4444;">ეს მოქმედება შეუქცევადია.</span>
                </p>

                <!-- Buttons -->
                <div style="display: flex; gap: 12px;">
                    <button 
                        onclick="hideDeleteModal()"
                        style="flex: 1; padding: 12px; background-color: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='var(--bg-hover)';"
                        onmouseout="this.style.backgroundColor='var(--bg-secondary)';">
                        გაუქმება
                    </button>
                    <button 
                        id="confirmDeleteBtn"
                        onclick="confirmDelete()"
                        style="flex: 1; padding: 12px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)';">
                        წაშლა
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(20px);
                opacity: 0;
            }
        }

        #deleteModal.hiding {
            animation: fadeOut 0.2s ease-in-out;
        }

        #deleteModal.hiding > div {
            animation: slideDown 0.2s ease-out;
        }
    </style>

    <script>
        let currentDeleteFormId = null;

        function showDeleteModal(memberId, memberName) {
            currentDeleteFormId = memberId;
            document.getElementById('memberName').textContent = memberName;
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hiding');
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.remove('hiding');
                document.body.style.overflow = 'auto';
                currentDeleteFormId = null;
            }, 200);
        }

        function confirmDelete() {
            if (currentDeleteFormId) {
                document.getElementById('delete-form-' + currentDeleteFormId).submit();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideDeleteModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                hideDeleteModal();
            }
        });
    </script>
</x-dashboard-layout>

