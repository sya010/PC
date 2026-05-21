<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ $product ? __('messages.admin.product_form.edit_product') : __('messages.admin.product_form.new_product') }}
            </h1>
            <p class="text-gray-500 dark:text-dark-200 mt-1">
                {{ $product ? __('messages.admin.product_form.update_subtitle') : __('messages.admin.product_form.create_subtitle') }}
            </p>
        </div>
        <a href="{{ route('admin.products') }}" wire:navigate class="flex items-center gap-2 text-gray-500 hover:text-gray-900 dark:text-dark-300 dark:hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'M14 5l7 7m0 0l-7 7m7-7H3' : 'M10 19l-7-7m0 0l7-7m-7 7h18' }}" /></svg>
            {{ __('messages.admin.product_form.back_to_products') }}
        </a>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- General Info Card -->
                <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        {{ __('messages.admin.product_form.general_info') }}
                    </h3>
                    
                    <div class="space-y-5">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.product_form.product_name') }}</label>
                            <input type="text" wire:model="name" maxlength="255" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-400 focus:outline-none focus:ring-4 focus:ring-brand-base/10 focus:border-brand-base focus:bg-white dark:focus:bg-dark-700 transition-all duration-200 hover:border-brand-base/30 shadow-sm" placeholder="{{ __('messages.admin.product_form.product_name_placeholder') }}">
                            @error('name') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.product_form.description') }}</label>
                            <textarea wire:model="description" maxlength="5000" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-400 focus:outline-none focus:ring-4 focus:ring-brand-base/10 focus:border-brand-base focus:bg-white dark:focus:bg-dark-700 transition-all duration-200 hover:border-brand-base/30 shadow-sm resize-none" placeholder="{{ __('messages.admin.product_form.description_placeholder') }}"></textarea>
                            @error('description') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.product_form.price') }}</label>
                                <!-- Using Custom IQD Input Component -->
                                <x-input-iqd wire:model="price" />
                                @error('price') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.product_form.stock_quantity') }}</label>
                                <input type="number" wire:model="stock" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-400 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-700 transition-all duration-200 hover:border-brand-base/30 shadow-sm">
                                @error('stock') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications Card -->
                <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            {{ __('messages.admin.product_form.specifications') }}
                        </h3>
                        <button type="button" wire:click="addSpec" class="text-sm px-4 py-2 bg-brand-base/10 dark:bg-brand-base/20 text-brand-base dark:text-brand-light rounded-lg hover:bg-brand-base/20 font-bold transition-all hover:shadow-sm">
                            {{ __('messages.admin.product_form.add_spec') }}
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($specs as $index => $spec)
                            <div class="flex items-center gap-3 group">
                                <div class="flex-1 grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            wire:model="specs.{{ $index }}.key" 
                                            maxlength="100" 
                                            placeholder="{{ __('messages.admin.product_form.key_placeholder') }}" 
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-sm text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-400 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-700 transition-all shadow-sm font-medium"
                                        >
                                        @error("specs.{$index}.key") <span class="text-red-500 text-xs mt-1 block absolute -bottom-4 left-0">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            wire:model="specs.{{ $index }}.value" 
                                            maxlength="255" 
                                            placeholder="{{ __('messages.admin.product_form.value_placeholder') }}" 
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-sm text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-400 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-700 transition-all shadow-sm"
                                        >
                                        @error("specs.{$index}.value") <span class="text-red-500 text-xs mt-1 block absolute -bottom-4 left-0">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <button type="button" wire:click="removeSpec({{ $index }})" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-colors flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                            <div class="h-1"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Status & Category (Custom Alpine Select) -->
                <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4">{{ __('messages.admin.product_form.organization') }}</h3>
                    
                    <div class="space-y-5">
                        <div class="group" x-data="{ 
                            open: false, 
                            selected: @entangle('category'), 
                            options: @js($availableCategories),
                            newCategory: '',
                            isAdding: false,
                            addCategory() {
                                const cat = this.newCategory.trim();
                                if (cat !== '') {
                                    if (!this.options.includes(cat)) {
                                        this.options.push(cat);
                                    }
                                    this.selected = cat;
                                    this.newCategory = '';
                                    this.isAdding = false;
                                }
                            }
                        }">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.admin.products.category') }}</label>
                            
                            <div class="flex gap-2 h-[48px]">
                                <!-- Regular Select Mode -->
                                <div class="relative flex-1" x-show="!isAdding">
                                    <button type="button" @click="open = !open; isAdding = false" @click.away="open = false" 
                                        class="w-full h-full px-4 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-gray-900 dark:text-dark-100 cursor-pointer focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-white dark:focus:bg-dark-700 transition-all duration-200 flex items-center justify-between shadow-sm">
                                        <span x-text="selected ? selected : '{{ __('messages.admin.product_form.select_category') }}'" :class="selected ? 'text-gray-900 dark:text-dark-100 font-medium' : 'text-gray-400 dark:text-dark-400'"></span>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 w-full mt-2 bg-white dark:bg-dark-800 rounded-xl shadow-xl border border-gray-100 dark:border-dark-700 overflow-hidden flex flex-col"
                                        style="display: none;">

                                        <div class="max-h-60 overflow-y-auto py-1">
                                            <template x-for="option in options" :key="option">
                                                <div @click="selected = option; open = false" 
                                                    class="px-4 py-2.5 hover:bg-brand-base/5 dark:hover:bg-dark-700 cursor-pointer flex items-center justify-between group/item transition-colors">
                                                    <span x-text="option" class="font-medium text-gray-700 dark:text-dark-200 group-hover/item:text-brand-accent"></span>
                                                    <span x-show="selected === option" class="text-brand-base">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Adding Mode (Input) -->
                                <input x-show="isAdding" type="text" x-model="newCategory" @keydown.enter.prevent="addCategory()" placeholder="{{ __('messages.admin.product_form.add_new_category') }}" 
                                    class="flex-1 w-full h-full px-4 rounded-xl border border-gray-200 dark:border-dark-600 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent bg-white dark:bg-dark-900 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-400 shadow-sm transition-all text-sm" style="display: none;">
                                
                                <button x-show="isAdding" type="button" @click="addCategory()" class="h-full px-4 bg-brand-base text-white rounded-xl text-sm font-bold hover:bg-brand-accent transition-colors shadow-sm whitespace-nowrap" style="display: none;">
                                    {{ __('messages.admin.product_form.add') }}
                                </button>

                                <!-- Toggle Button -->
                                <button type="button" @click="isAdding = !isAdding; open = false" 
                                    class="h-full aspect-square flex items-center justify-center rounded-xl flex-shrink-0 transition-all shadow-sm"
                                    :class="isAdding ? 'bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white' : 'bg-brand-base/5 text-brand-base hover:bg-brand-base hover:text-white'"
                                    title="{{ __('messages.admin.product_form.add_new_category') }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="isAdding ? 'rotate-45 transition-transform' : 'transition-transform'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                            
                            @error('category') <span class="text-red-500 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50/50 dark:bg-dark-900/50 rounded-xl border border-gray-100 dark:border-dark-700">
                            <span class="text-sm font-medium text-gray-700 dark:text-dark-200">{{ __('messages.admin.product_form.active_status') }}</span>
                            <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                                <input type="checkbox" wire:model="is_active" id="toggle-active" class="peer absolute w-0 h-0 opacity-0" />
                                <label for="toggle-active" class="block w-12 h-6 bg-gray-200 dark:bg-dark-700 rounded-full cursor-pointer transition-colors peer-checked:bg-green-500"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media (Drag & Drop) -->
                <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4">{{ __('messages.admin.product_form.media') }}</h3>
                    
                    <div class="space-y-4">
                         <!-- Drag & Drop Zone -->
                         <div
                            x-data="{ isDropping: false }"
                            @dragover.prevent="isDropping = true"
                            @dragleave.prevent="isDropping = false"
                            @drop.prevent="isDropping = false"
                            class="relative border-2 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all cursor-pointer group"
                            :class="isDropping ? 'border-brand-base bg-brand-base/5' : 'border-gray-200 dark:border-dark-600 hover:border-brand-light hover:bg-gray-50 dark:hover:bg-dark-900/50'"
                        >
                            <input type="file" wire:model="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg, image/webp">
                            
                            <div class="pointer-events-none transform group-hover:scale-105 transition-transform">
                                <div class="w-12 h-12 mx-auto bg-brand-base/10 text-brand-base rounded-xl flex items-center justify-center mb-3 group-hover:bg-brand-base/20 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.product_form.click_or_drop') }}</p>
                                <p class="text-xs text-gray-500 dark:text-dark-300 mt-1 font-medium">{{ __('messages.admin.product_form.file_types') }}</p>
                            </div>
                        </div>
                        
                        <!-- Preview -->
                        @if($image && !is_string($image) && $image->temporaryUrl())
                             <div class="relative rounded-xl overflow-hidden aspect-video border border-gray-200 dark:border-dark-700 shadow-sm group">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-bold bg-black/50 px-3 py-1.5 rounded-full backdrop-blur-sm">{{ __('messages.admin.product_form.new_upload') }}</span>
                                </div>
                            </div>
                        @elseif($existingImage)
                            <div class="relative rounded-xl overflow-hidden aspect-video border border-gray-200 dark:border-dark-700 shadow-sm">
                                <img src="{{ $existingImage }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        @error('image') <span class="text-red-500 text-sm font-medium block bg-red-50 p-2 rounded-lg border border-red-100">{{ $message }}</span> @enderror
                        
                        <!-- Fallback URL Input (Optional) -->
                        <div x-data="{ show: false }">
                             <button type="button" @click="show = !show" class="text-xs text-brand-base hover:underline font-medium mb-2">{{ __('messages.admin.product_form.or_enter_url') }}</button>
                             <div x-show="show" class="group">
                                <input type="text" wire:model="existingImage" placeholder="https://..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-dark-600 bg-gray-50/50 dark:bg-dark-900/50 text-xs text-gray-700 dark:text-dark-100 focus:outline-none focus:ring-2 focus:ring-brand-base/10 focus:border-brand-base focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-3.5 px-4 bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 hover:shadow-brand-base/30 hover:bg-brand-base transform hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2">
                        <span wire:loading.remove>{{ $product ? __('messages.admin.product_form.update_product') : __('messages.admin.product_form.create_product') }}</span>
                        <span wire:loading class="animate-pulse">{{ __('messages.admin.product_form.saving') }}</span>
                    </button>
                    <a href="{{ route('admin.products') }}" wire:navigate class="w-full py-3.5 px-4 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl text-center hover:bg-gray-50 hover:border-gray-300 transition-all dark:bg-dark-800 dark:border-dark-700 dark:text-dark-200 dark:hover:bg-dark-700">
                        {{ __('messages.admin.product_form.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
