<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.password_requests.review_request') }}
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1">
                {{ __('messages.admin.password_requests.subtitle') }}
            </p>
        </div>
        <a href="{{ route('admin.password-requests') }}" wire:navigate class="flex items-center gap-2 text-gray-500 dark:text-dark-300 hover:text-gray-900 dark:hover:text-dark-100 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'M14 5l7 7m0 0l-7 7m7-7H3' : 'M10 19l-7-7m0 0l7-7m-7 7h18' }}" /></svg>
            {{ __('messages.admin.user_form.back_to_users') }}
        </a>
    </div>

    <!-- Review Form Card -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-8 space-y-6">
        <div class="space-y-6 text-sm">
            <!-- Email -->
            <div class="group border-b border-gray-100 dark:border-dark-800 pb-4">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('messages.admin.password_requests.email') }}</span>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-gray-900 dark:text-dark-100 select-all">{{ $request->email }}</span>
                    <button type="button" @click="navigator.clipboard.writeText('{{ $request->email }}'); alert('Email copied!');" class="p-1.5 text-gray-400 hover:text-brand-base transition-colors rounded-lg bg-gray-50 dark:bg-dark-950 border border-gray-200 dark:border-dark-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                    </button>
                </div>
            </div>

            <!-- Details grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-gray-100 dark:border-dark-800 pb-4">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('messages.admin.password_requests.reason') }}</span>
                    <span class="font-semibold text-base text-gray-800 dark:text-dark-200">
                        {{ __('messages.auth.reason_' . strtolower(explode(' ', trim($request->reason))[0])) ?: $request->reason }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('messages.admin.password_requests.date_submitted') }}</span>
                    <span class="font-semibold text-base text-gray-800 dark:text-dark-200">{{ $request->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            <!-- Current Status Badge -->
            <div class="border-b border-gray-100 dark:border-dark-800 pb-4">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('messages.admin.password_requests.status') }}</span>
                @if($request->status === 'pending')
                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                        {{ __('messages.admin.password_requests.pending') }}
                    </span>
                @elseif($request->status === 'completed')
                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                        {{ __('messages.admin.password_requests.completed') }}
                    </span>
                @else
                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                        {{ __('messages.admin.password_requests.cancelled') }}
                    </span>
                @endif
            </div>

            <!-- Description -->
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('messages.admin.password_requests.detailed_description') }}</span>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-850 text-gray-700 dark:text-dark-300 font-medium leading-relaxed max-h-48 overflow-y-auto">
                    {{ $request->description }}
                </div>
            </div>

            <hr class="border-gray-100 dark:border-dark-800">

            <!-- Admin Notes -->
            <div class="group">
                <label for="adminNote" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.password_requests.admin_note') }}</label>
                <textarea 
                    id="adminNote" 
                    wire:model="adminNote" 
                    rows="4" 
                    placeholder="{{ __('messages.admin.password_requests.admin_note_placeholder') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-gray-50/50 dark:bg-dark-950 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-900 transition-all duration-200 hover:border-brand-base/30 shadow-sm resize-none"
                ></textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <button type="button" wire:click="updateStatus('completed')" class="flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-600/10 text-sm transition-all text-center">
                {{ __('messages.admin.password_requests.mark_completed') }}
            </button>
            <button type="button" wire:click="updateStatus('cancelled')" class="flex-1 py-3 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-600/10 text-sm transition-all text-center">
                {{ __('messages.admin.password_requests.cancel_request') }}
            </button>
            <a href="{{ route('admin.password-requests') }}" wire:navigate class="flex-1 py-3 px-4 bg-gray-100 dark:bg-dark-750 text-gray-700 dark:text-dark-200 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-dark-700 text-sm transition-all text-center">
                {{ __('messages.admin.password_requests.close') }}
            </a>
        </div>
    </div>
</div>
