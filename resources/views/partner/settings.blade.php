<x-partner-layout :partner="$partner" title="პარამეტრები" headerTitle="პარამეტრები">
    <div class="space-y-4 md:space-y-6 max-w-xl">
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('partner.settings.update') }}" class="bg-white dark:bg-slate-900 p-5 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-slate-50 dark:border-slate-800 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">სახელი</label>
                <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">ელფოსტა</label>
                <input type="email" name="email" value="{{ old('email', $partner->email) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">ტელეფონი (შესვლა)</label>
                <input type="text" name="phone" value="{{ old('phone', $partner->phone) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">ვებ-საიტი</label>
                <input type="url" name="website" value="{{ old('website', $partner->website) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">მისამართი</label>
                <input type="text" name="address" value="{{ old('address', $partner->address) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">ქალაქი</label>
                <input type="text" name="city" value="{{ old('city', $partner->city) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <button type="submit"
                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-200/50 dark:shadow-none transition-colors">
                შენახვა
            </button>
        </form>
    </div>
</x-partner-layout>
