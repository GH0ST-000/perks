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
    <label class="flex flex-col items-center justify-center w-full h-32 md:h-36 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl md:rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
        <i data-lucide="upload" class="w-6 h-6 text-slate-300 mb-2"></i>
        <span class="text-xs font-bold text-slate-400">ატვირთეთ ფოტო</span>
        <input type="file" name="image" accept="image/*" class="hidden" @change="imageName = $event.target.files[0]?.name || ''">
        <span class="text-[10px] text-blue-600 mt-1" x-text="imageName" x-show="imageName"></span>
    </label>
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
