<div class="space-y-1 md:space-y-2">
    <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">შეთავაზების სათაური</label>
    <div class="relative">
        <i data-lucide="tag" class="absolute left-4 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-300 pointer-events-none"></i>
        <input type="text" name="title" required placeholder="მაგ: საღამოს კოქტეილები"
            x-model="form.title"
            class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-3.5 partner-input border-2 border-transparent focus:border-blue-600 rounded-xl md:rounded-2xl font-medium outline-none text-sm md:text-base transition-all">
    </div>
</div>

<div class="space-y-1 md:space-y-2">
    <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">სათაურის ტექსტი (ბარათზე)</label>
    <div class="relative">
        <i data-lucide="type" class="absolute left-4 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-300 pointer-events-none"></i>
        <input type="text" name="header_text" placeholder="მაგ: ექსკლუზივი"
            x-model="form.header_text"
            class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-3.5 partner-input border-2 border-transparent focus:border-blue-600 rounded-xl md:rounded-2xl font-medium outline-none text-sm md:text-base transition-all">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
    <div class="space-y-1 md:space-y-2">
        <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">ფასდაკლება (%)</label>
        <div class="relative">
            <i data-lucide="percent" class="absolute left-4 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-300 pointer-events-none"></i>
            <input type="number" name="discount" required min="1" max="100" placeholder="20"
                x-model="form.discount"
                class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-3.5 partner-input border-2 border-transparent focus:border-blue-600 rounded-xl md:rounded-2xl font-medium outline-none text-sm md:text-base transition-all">
        </div>
    </div>
    <div class="space-y-1 md:space-y-2">
        <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">ხანგრძლივობა</label>
        <div class="relative">
            <i data-lucide="clock" class="absolute left-4 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-300 pointer-events-none"></i>
            <select name="period" x-model="form.period"
                class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-3.5 partner-input border-2 border-transparent focus:border-blue-600 rounded-xl md:rounded-2xl font-medium outline-none text-sm md:text-base transition-all appearance-none cursor-pointer">
                <option value="1 თვე">1 თვე</option>
                <option value="2 თვე">2 თვე</option>
                <option value="3 თვე">3 თვე</option>
            </select>
        </div>
    </div>
</div>

<div class="space-y-1 md:space-y-2">
    <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">P-Coin ჯილდო</label>
    <div class="relative">
        <i data-lucide="coins" class="absolute left-4 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-300 pointer-events-none"></i>
        <input type="number" name="p_coins_reward" required min="0" placeholder="50"
            x-model="form.p_coins"
            class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-3.5 partner-input border-2 border-transparent focus:border-blue-600 rounded-xl md:rounded-2xl font-medium outline-none text-sm md:text-base transition-all">
    </div>
</div>

<div class="space-y-1 md:space-y-2">
    <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">სურათი</label>

    <div x-show="hasImagePreview" x-cloak class="w-full space-y-2">
        <div class="relative rounded-xl md:rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
            <img :src="imagePreview || existingImage" alt="" class="w-full h-36 md:h-44 object-cover block">
        </div>
        <div class="flex gap-2">
            <button type="button"
                    @click="clearImage()"
                    class="partner-image-btn-delete flex-1 inline-flex items-center justify-center gap-2 py-3 text-sm font-bold rounded-xl shadow-md transition-colors min-h-[44px]"
                    title="წაშლა">
                <span aria-hidden="true" class="text-base leading-none">✕</span>
                <span>წაშლა</span>
            </button>
            <label class="partner-image-btn-change flex-1 inline-flex items-center justify-center py-3 text-sm font-bold rounded-xl shadow-md cursor-pointer transition-colors min-h-[44px]">
                შეცვლა
                <input type="file" name="image" accept="image/*" class="hidden" x-ref="imageInput" @change="onImageSelect($event)">
            </label>
        </div>
    </div>

    <label x-show="!hasImagePreview" x-cloak
           class="flex flex-col items-center justify-center w-full h-32 md:h-36 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl md:rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
        <i data-lucide="upload" class="w-6 h-6 text-slate-300 mb-2"></i>
        <span class="text-xs font-bold text-slate-400">ატვირთეთ ფოტო</span>
        <input type="file" name="image" accept="image/*" class="hidden" x-ref="imageInputEmpty" @change="onImageSelect($event)">
    </label>

    <template x-if="editingId && removeImage">
        <input type="hidden" name="remove_image" value="1">
    </template>
</div>

<div class="space-y-1 md:space-y-2">
    <label class="text-[9px] md:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">აღწერა</label>
    <div class="relative">
        <i data-lucide="align-left" class="absolute left-4 top-4 w-3.5 h-3.5 text-slate-300 pointer-events-none"></i>
        <textarea name="description" required rows="3" placeholder="აღწერეთ შეთავაზება..."
            x-model="form.description"
            class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-3.5 partner-input border-2 border-transparent focus:border-blue-600 rounded-xl md:rounded-2xl font-medium outline-none text-sm md:text-base transition-all resize-none"></textarea>
    </div>
</div>
