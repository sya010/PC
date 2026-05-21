<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.user_form.title') }}
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1">{{ __('messages.admin.user_form.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.users') }}" wire:navigate class="flex items-center gap-2 text-gray-500 dark:text-dark-300 hover:text-gray-900 dark:hover:text-dark-100 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'M14 5l7 7m0 0l-7 7m7-7H3' : 'M10 19l-7-7m0 0l7-7m-7 7h18' }}" /></svg>
            {{ __('messages.admin.user_form.back_to_users') }}
        </a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-8 space-y-6">
            <!-- Name -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.user_form.full_name') }}</label>
                <input type="text" wire:model="name" maxlength="255" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-gray-50/50 dark:bg-dark-950 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-900 transition-all duration-200 hover:border-brand-base/30 shadow-sm">
                @error('name') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.user_form.email') }}</label>
                <input type="email" wire:model="email" maxlength="255" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-gray-50/50 dark:bg-dark-950 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-900 transition-all duration-200 hover:border-brand-base/30 shadow-sm">
                @error('email') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">
                    {{ __('messages.admin.user_form.password') }} 
                    <span class="text-gray-400 dark:text-dark-400 font-normal {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'mr-1' : 'ml-1' }} text-xs">{{ __('messages.admin.user_form.password_hint') }}</span>
                </label>
                <input type="password" wire:model="password" maxlength="255" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-gray-50/50 dark:bg-dark-950 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-900 transition-all duration-200 hover:border-brand-base/30 shadow-sm" autocomplete="new-password">
                @error('password') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <hr class="border-gray-100 dark:border-dark-800">

            <!-- Role -->
            <div class="flex items-center justify-between p-2">
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.user_form.admin_access') }}</h4>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-0.5">{{ __('messages.admin.user_form.admin_access_desc') }}</p>
                </div>
                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                    <input type="checkbox" wire:model="is_admin" id="toggle-admin" class="peer absolute w-0 h-0 opacity-0" />
                    <label for="toggle-admin" class="block w-12 h-6 bg-gray-200 dark:bg-dark-800 rounded-full cursor-pointer transition-colors peer-checked:bg-brand-accent"></label>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <button type="submit" class="w-full py-3.5 px-4 bg-brand-base text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 hover:bg-brand-accent hover:shadow-brand-base/30 transform hover:-translate-y-0.5 transition-all">
                {{ __('messages.admin.user_form.save_changes') }}
            </button>
            <a href="{{ route('admin.users') }}" wire:navigate class="w-full py-3.5 px-4 bg-white dark:bg-dark-900 border border-gray-200 dark:border-dark-800 text-gray-700 dark:text-dark-200 font-bold rounded-xl text-center hover:bg-gray-50 dark:hover:bg-dark-850 hover:border-gray-300 dark:hover:border-dark-700 transition-all">
                {{ __('messages.admin.user_form.cancel') }}
            </a>
        </div>
    </form>
</div>
