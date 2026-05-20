<div class="min-h-screen bg-surface-secondary dark:bg-dark-950">
    <!-- Page Header - Minimalist -->
    <div class="bg-surface-primary dark:bg-dark-900 border-b border-border-subtle dark:border-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-content-primary dark:text-dark-100">{{ __('messages.pc_builder.title') }}</h1>
                <p class="text-sm text-content-muted dark:text-dark-500 hidden sm:block">{{ __('messages.pc_builder.subtitle') }}</p>
            </div>
            
             <!-- Mobile Summary Toggle (Visible only on small screens) -->
            <div class="lg:hidden flex items-center gap-4">
               <div class="text-end">
                    <p class="text-[10px] uppercase font-bold text-content-muted dark:text-dark-500">{{ __('messages.build.total') }}</p>
                    <p class="font-black text-content-primary dark:text-dark-100">{{ number_format($totalPrice, 0) }} <span class="text-xs">{{ __('messages.currency') }}</span></p>
               </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Build Area -->
            <div class="flex-1 space-y-10">
                
                @php
                    $groups = [
                        __('messages.pc_builder.core_system') => [
                            'cpu' => ['icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'label' => __('messages.pc_builder.processor'), 'desc' => __('messages.pc_builder.processor_desc')],
                            'motherboard' => ['icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'label' => __('messages.pc_builder.motherboard'), 'desc' => __('messages.pc_builder.motherboard_desc')],
                            'gpu' => ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => __('messages.pc_builder.graphics_card'), 'desc' => __('messages.pc_builder.graphics_card_desc')],
                            'ram' => ['icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label' => __('messages.pc_builder.memory'), 'desc' => __('messages.pc_builder.memory_desc')],
                            'storage' => ['icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4', 'label' => __('messages.pc_builder.storage'), 'desc' => __('messages.pc_builder.storage_desc')],
                            'cooling' => ['icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => __('messages.pc_builder.cooling'), 'desc' => __('messages.pc_builder.cooling_desc')],
                            'psu' => ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => __('messages.pc_builder.power_supply'), 'desc' => __('messages.pc_builder.power_supply_desc')],
                            'case' => ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label' => __('messages.pc_builder.case'), 'desc' => __('messages.pc_builder.case_desc')],
                        ],
                        __('messages.pc_builder.peripherals') => [
                            'monitor' => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => __('messages.pc_builder.monitor'), 'desc' => __('messages.pc_builder.monitor_desc')],
                            'keyboard' => ['icon' => 'M15 12a1 1 0 11-2 0 1 1 0 012 0zm-7 0a1 1 0 11-2 0 1 1 0 012 0zm-5 6a1 1 0 100-2 1 1 0 000 2zm16 0a1 1 0 100-2 1 1 0 000 2zm-5 0a1 1 0 100-2 1 1 0 000 2z', 'label' => __('messages.pc_builder.keyboard'), 'desc' => __('messages.pc_builder.keyboard_desc')],
                            'mouse' => ['icon' => 'M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122', 'label' => __('messages.pc_builder.mouse'), 'desc' => __('messages.pc_builder.mouse_desc')],
                            'headset' => ['icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z', 'label' => __('messages.pc_builder.headset'), 'desc' => __('messages.pc_builder.headset_desc')],
                             'mousepad' => ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'label' => __('messages.pc_builder.mousepad'), 'desc' => __('messages.pc_builder.mousepad_desc')],
                            'microphone' => ['icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z', 'label' => __('messages.pc_builder.microphone'), 'desc' => __('messages.pc_builder.microphone_desc')],
                            'webcam' => ['icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'label' => __('messages.pc_builder.webcam'), 'desc' => __('messages.pc_builder.webcam_desc')],
                            'speakers' => ['icon' => 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z', 'label' => __('messages.pc_builder.speakers'), 'desc' => __('messages.pc_builder.speakers_desc')],
                        ],
                    ];
                @endphp

                @foreach($groups as $groupName => $items)
                    <div class="bg-surface-primary dark:bg-dark-900 rounded-3xl border border-border-subtle dark:border-dark-800 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-surface-secondary/50 dark:bg-dark-950/50 border-b border-border-subtle dark:border-dark-800 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-content-primary dark:text-dark-100">{{ $groupName }}</h2>
                            <div class="flex items-center gap-4">
                                @php
                                    $selectedCount = count(array_filter($items, fn($item, $key) => $selectedComponents[$key] ?? null, ARRAY_FILTER_USE_BOTH));
                                @endphp
                                @if($selectedCount > 0)
                                    <button wire:click="confirmReset('{{ $groupName === __('messages.pc_builder.core_system') ? 'core' : 'peripherals' }}')" class="text-xs font-bold text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                        {{ __('messages.compatibility.reset_group') }} {{ $groupName }}
                                    </button>
                                @endif
                                <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider">{{ $selectedCount }} {{ __('messages.pc_builder.selected') }}</span>
                            </div>
                        </div>
                        
                        <div class="divide-y divide-border-subtle dark:divide-dark-800">
                            @foreach($items as $type => $info)
                                @php
                                    $component = $selectedComponents[$type] ?? null;
                                    $isSelected = $component !== null;
                                    $hasError = $this->hasIssue($type);
                                    $isQtyType = $this->isQuantityType($type);
                                    $qty = $this->getQuantity($type);
                                 @endphp
                                
                                <div class="group p-5 hover:bg-surface-secondary dark:hover:bg-dark-950 transition-colors">
                                    <div class="flex flex-col sm:flex-row gap-5 sm:items-center">
                                        <!-- Mobile Label (visible on small screens only) -->
                                        <div class="sm:hidden flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-surface-secondary dark:bg-dark-850 flex items-center justify-center text-content-secondary dark:text-dark-300">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $info['icon'] }}" />
                                                </svg>
                                            </div>
                                            <div>
                                                 <span class="font-bold text-content-primary dark:text-dark-100">{{ $info['label'] }}</span>
                                                 <p class="text-xs text-content-muted dark:text-dark-500">{{ $info['desc'] }}</p>
                                            </div>
                                        </div>

                                        <!-- Desktop Icon & Label -->
                                        <div class="hidden sm:flex items-center gap-4 w-1/3">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors
                                                {{ $isSelected ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-surface-secondary dark:bg-dark-850 text-content-muted dark:text-dark-500 group-hover:bg-surface-primary dark:group-hover:bg-dark-800 group-hover:shadow-sm' }}
                                                {{ $hasError ? '!bg-red-100 dark:!bg-red-900/30 !text-red-500 dark:!text-red-400' : '' }}">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $info['icon'] }}" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-bold text-content-secondary group-hover:text-content-primary dark:text-dark-300 dark:group-hover:text-dark-100 transition-colors">{{ $info['label'] }}</span>
                                                <span class="text-xs text-content-muted dark:text-dark-500">{{ $info['desc'] }}</span>
                                            </div>
                                        </div>

                                        <!-- Selection Area -->
                                        <div class="flex-1 min-w-0">
                                            @if($isSelected)
                                                <div class="bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-800 rounded-xl p-3 shadow-sm group-hover:border-brand-base/20 transition-all">
                                                    <div class="flex items-center gap-4">
                                                        <img src="{{ $component['image'] }}" alt="{{ $component['name'] }}" class="w-12 h-12 object-contain mix-blend-multiply dark:mix-blend-normal">
                                                        <div class="flex-1 min-w-0">
                                                            <p class="font-bold text-content-primary dark:text-dark-100 truncate text-sm">{{ $component['name'] }}</p>
                                                            <div class="flex items-center gap-2">
                                                                <p class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">{{ number_format($component['price'], 0) }} {{ __('messages.currency') }}</p>
                                                                @if($isQtyType && $qty > 1)
                                                                    <span class="text-content-muted dark:text-dark-500 text-xs font-medium">× {{ $qty }} = {{ number_format($component['price'] * $qty, 0) }} {{ __('messages.currency') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            {{-- Quantity controls for RAM, Storage, Cooling --}}
                                                            @if($isQtyType)
                                                                <div class="flex items-center gap-1 bg-surface-secondary dark:bg-dark-950 rounded-lg border border-border-subtle dark:border-dark-800 p-0.5">
                                                                    <button 
                                                                        wire:click="decrementQuantity('{{ $type }}')" 
                                                                        class="w-7 h-7 flex items-center justify-center rounded-md text-content-secondary dark:text-dark-400 hover:bg-surface-primary dark:hover:bg-dark-800 hover:text-content-primary dark:hover:text-dark-100 hover:shadow-sm transition-all {{ $qty <= 1 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                                        {{ $qty <= 1 ? 'disabled' : '' }}
                                                                    >
                                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" />
                                                                        </svg>
                                                                    </button>
                                                                    <span class="w-7 text-center text-sm font-black text-content-primary dark:text-dark-100">{{ $qty }}</span>
                                                                    <button 
                                                                        wire:click="incrementQuantity('{{ $type }}')" 
                                                                        class="w-7 h-7 flex items-center justify-center rounded-md text-content-secondary dark:text-dark-400 hover:bg-surface-primary dark:hover:bg-dark-800 hover:text-content-primary dark:hover:text-dark-100 hover:shadow-sm transition-all {{ $qty >= 8 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                                        {{ $qty >= 8 ? 'disabled' : '' }}
                                                                    >
                                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            @endif

                                                            <a href="{{ route('pc.select', $type) }}" wire:navigate class="p-2 text-content-muted dark:text-dark-500 hover:text-brand-base hover:bg-brand-base/5 rounded-lg transition-colors" title="Change">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                            </a>
                                                            <button wire:click="removeComponent('{{ $type }}')" class="p-2 text-content-muted dark:text-dark-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Remove">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('pc.select', $type) }}" wire:navigate class="block w-full border-2 border-dashed border-border-subtle dark:border-dark-850 rounded-xl p-4 text-center hover:border-brand-light hover:bg-brand-base/5 transition-all group/btn">
                                                    <span class="text-sm font-bold text-content-muted dark:text-dark-500 group-hover/btn:text-brand-base flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        {{ __('messages.build.select') }} {{ $info['label'] }}
                                                    </span>
                                                </a>
                                            @endif
                                            
                                             @if($hasError)
                                                <p class="mt-2 text-xs font-bold text-red-500 dark:text-red-400 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ __('messages.compatibility.issue') }}
                                                </p>
                                             @endif
                                        </div>
                                    </div>

                                    {{-- Extra Slots (Multiple components of the same type) --}}
                                    @if($this->isMultiSlotType($type) && $isSelected)
                                        <div class="mt-4 sm:ps-[calc(33.333333%+1rem)] flex flex-col gap-3">
                                            
                                            {{-- Render existing extra components --}}
                                            @foreach($this->extraComponents[$type] ?? [] as $index => $extraComp)
                                                @php $extraQty = $this->getExtraQuantity($type, $index); @endphp
                                                <div class="bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-800 rounded-xl p-3 shadow-sm flex items-center gap-4">
                                                    <img src="{{ $extraComp['image'] }}" alt="{{ $extraComp['name'] }}" class="w-10 h-10 object-contain mix-blend-multiply dark:mix-blend-normal">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-bold text-content-primary dark:text-dark-100 truncate text-sm">{{ $extraComp['name'] }}</p>
                                                        <div class="flex items-center gap-2">
                                                            <p class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">{{ number_format($extraComp['price'], 0) }} {{ __('messages.currency') }}</p>
                                                            @if($extraQty > 1)
                                                                <span class="text-content-muted dark:text-dark-500 text-xs font-medium">× {{ $extraQty }} = {{ number_format($extraComp['price'] * $extraQty, 0) }} {{ __('messages.currency') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        {{-- Extra Quantity Controls --}}
                                                        <div class="flex items-center gap-1 bg-surface-secondary dark:bg-dark-950 rounded-lg border border-border-subtle dark:border-dark-800 p-0.5">
                                                            <button 
                                                                wire:click="decrementExtraQuantity('{{ $type }}', {{ $index }})" 
                                                                class="w-6 h-6 flex items-center justify-center rounded-md text-content-secondary dark:text-dark-400 hover:bg-surface-primary dark:hover:bg-dark-800 hover:text-content-primary dark:hover:text-dark-100 hover:shadow-sm transition-all {{ $extraQty <= 1 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                                {{ $extraQty <= 1 ? 'disabled' : '' }}
                                                            >
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                                            </button>
                                                            <span class="w-6 text-center text-xs font-black text-content-primary dark:text-dark-100">{{ $extraQty }}</span>
                                                            <button 
                                                                wire:click="incrementExtraQuantity('{{ $type }}', {{ $index }})" 
                                                                class="w-6 h-6 flex items-center justify-center rounded-md text-content-secondary dark:text-dark-400 hover:bg-surface-primary dark:hover:bg-dark-800 hover:text-content-primary dark:hover:text-dark-100 hover:shadow-sm transition-all {{ $extraQty >= 8 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                                {{ $extraQty >= 8 ? 'disabled' : '' }}
                                                            >
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                            </button>
                                                        </div>
                                                            <a href="{{ route('pc.select', $type) }}?extra=1&edit_index={{ $index }}" wire:navigate class="p-1.5 text-content-muted dark:text-dark-500 hover:text-brand-base hover:bg-brand-base/5 rounded-lg transition-colors" title="Change">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                            </a>
                                                            <button wire:click="removeExtraComponent('{{ $type }}', {{ $index }})" class="p-1.5 text-content-muted dark:text-dark-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Remove">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                            @endforeach

                                            {{-- Add Another Button --}}
                                            @if($this->canAddExtra($type))
                                                <div class="pt-1">
                                                    <a href="{{ route('pc.select', $type) }}?extra=1" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-brand-base bg-brand-base/5 hover:bg-brand-base/10 rounded-lg transition-colors border border-brand-base/10">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                                        + {{ $info['label'] }}
                                                    </a>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Summary Sidebar -->
            <div class="lg:w-96 flex-shrink-0">
                <div class="bg-surface-primary dark:bg-dark-900 rounded-3xl border border-border-subtle dark:border-dark-800 shadow-xl p-6 lg:sticky lg:top-24 space-y-6">
                     <div class="flex justify-between items-center">
                        <h3 class="text-xl font-black text-content-primary dark:text-dark-100">{{ __('messages.pc_builder.system_summary') }}</h3>
                        @if($this->getSelectedCount() > 0)
                            <button wire:click="confirmReset('all')" class="text-xs font-bold text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 rounded-lg transition-colors border border-red-500/20">
                            {{ __('messages.pc_builder.reset_build') }}
                            </button>
                        @endif
                     </div>
                     
                     @if($this->getSelectedCount() > 0)
                        <div class="space-y-4">
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-surface-secondary dark:bg-dark-950 rounded-xl border border-border-subtle dark:border-dark-800">
                                    <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase">{{ __('messages.pc_builder.parts') }}</span>
                                    <p class="text-lg font-black text-content-primary dark:text-dark-100">{{ $this->getSelectedCount() }}</p>
                                </div>
                                <div class="p-3 bg-surface-secondary dark:bg-dark-950 rounded-xl border border-border-subtle dark:border-dark-800">
                                    <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase">{{ __('messages.pc_builder.power') }}</span>
                                    <p class="text-lg font-black text-content-primary dark:text-dark-100">{{ $systemAnalysis['power']['total_draw'] ?? 0 }}W</p>
                                </div>
                            </div>
                            
                            <!-- Compatibility Status -->
                            <div class="p-4 rounded-xl {{ $compatibilityColor === 'green' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                <div class="flex items-center gap-3 mb-2">
                                     <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                     </svg>
                                     <span class="font-bold">{{ __('messages.compatibility.' . $compatibilityStatus) }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-black/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-current" style="width: {{ $compatibilityScore }}%"></div>
                                </div>
                            </div>
                            
                            <!-- Detailed Warnings -->
                             @if(count($compatibilityWarnings) > 0)
                                <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
                                    @foreach($compatibilityWarnings as $warning)
                                        <div class="p-2.5 rounded-lg text-xs font-medium border
                                            {{ $warning['type'] === 'error' ? 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20' : '' }}
                                            {{ $warning['type'] === 'warning' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20' : '' }}
                                            {{ $warning['type'] === 'info' ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20' : '' }}">
                                            {{ $warning['message'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Selected Parts Summary with Quantities -->
                            <div class="space-y-2 pt-2">
                                <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider">{{ __('messages.pc_builder.price_breakdown') }}</span>
                                @foreach($selectedComponents as $type => $comp)
                                    @if($comp)
                                        @php
                                            $isQty = $this->isQuantityType($type);
                                            $q = $this->getQuantity($type);
                                        @endphp
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-content-secondary dark:text-dark-300 truncate flex-1">
                                                {{ $comp['name'] }}
                                                @if($isQty && $q > 1)
                                                    <span class="text-content-muted dark:text-dark-500 font-medium">× {{ $q }}</span>
                                                @endif
                                            </span>
                                            <span class="font-bold text-content-primary dark:text-dark-100 ms-3 whitespace-nowrap">{{ number_format($comp['price'] * $q, 0) }} {{ __('messages.currency') }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                     @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-surface-secondary dark:bg-dark-950 rounded-full flex items-center justify-center mx-auto mb-3 text-content-muted dark:text-dark-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <p class="text-content-secondary dark:text-dark-300 font-medium text-sm">{{ __('messages.pc_builder.start_selecting') }}</p>
                        </div>
                     @endif
                     
                     <div class="pt-6 border-t border-border-subtle dark:border-dark-800">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-sm font-bold text-content-secondary dark:text-dark-300">{{ __('messages.pc_builder.total_price') }}</span>
                            <span class="text-3xl font-black text-content-primary dark:text-dark-100">{{ number_format($totalPrice, 0) }}<span class="text-sm text-content-muted dark:text-dark-500 ms-1">{{ __('messages.currency') }}</span></span>
                        </div>
                        
                        <button 
                            wire:click="addBuildToCart"
                            class="w-full py-4 rounded-xl font-bold transition-all shadow-lg
                                {{ $this->isValidBuild() 
                                    ? 'bg-gradient-to-r from-brand-base to-brand-accent text-white hover:shadow-xl hover:-translate-y-1' 
                                    : 'bg-surface-secondary dark:bg-dark-800 text-content-muted dark:text-dark-500 cursor-not-allowed shadow-none' }}"
                            {{ !$this->isValidBuild() ? 'disabled' : '' }}
                        >
                            {{ $this->getSelectedCount() > 0 ? __('messages.build.add_to_cart') : __('messages.pc_builder.make_selection') }}
                        </button>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    @if($showResetModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="cancelReset"></div>
            
            <!-- Modal Content -->
            <div class="bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden relative z-10 transform transition-all">
                <div class="p-6 sm:p-8">
                    <div class="w-12 h-12 rounded-full bg-red-500/10 text-red-500 dark:text-red-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-black text-content-primary dark:text-dark-100 mb-2">
                        @if($resetTarget === 'all')
                            {{ __('messages.pc_builder.reset_all_title') }}
                        @elseif($resetTarget === 'core')
                            {{ __('messages.pc_builder.reset_core_title') }}
                        @else
                            {{ __('messages.pc_builder.reset_peri_title') }}
                        @endif
                    </h3>
                    
                    <p class="text-content-secondary dark:text-dark-300 text-sm">
                        @if($resetTarget === 'all')
                            {{ __('messages.pc_builder.reset_all_desc') }}
                        @elseif($resetTarget === 'core')
                            {{ __('messages.pc_builder.reset_core_desc') }}
                        @else
                            {{ __('messages.pc_builder.reset_peri_desc') }}
                        @endif
                    </p>
                    
                    <div class="mt-8 flex gap-3">
                        <button wire:click="cancelReset" class="flex-1 px-4 py-3 bg-surface-secondary hover:bg-surface-secondary/80 dark:bg-dark-800 dark:hover:bg-dark-700 text-content-secondary dark:text-dark-200 font-bold rounded-xl transition-colors">
                            {{ __('messages.pc_builder.cancel') }}
                        </button>
                        <button wire:click="executeReset" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-red-500/30">
                            {{ __('messages.pc_builder.yes_reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
