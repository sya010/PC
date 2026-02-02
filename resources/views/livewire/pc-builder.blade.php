<div class="min-h-screen bg-slate-50">
    <!-- Page Header - Minimalist -->
    <div class="bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">PC Configurator</h1>
                <p class="text-sm text-slate-500 hidden sm:block">Customize your dream rig, part by part.</p>
            </div>
            
             <!-- Mobile Summary Toggle (Visible only on small screens) -->
            <div class="lg:hidden flex items-center gap-4">
               <div class="text-right">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Total</p>
                    <p class="font-black text-slate-900">{{ number_format($totalPrice, 0) }} <span class="text-xs">IQD</span></p>
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
                        'Core System' => [
                            'cpu' => ['icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'label' => 'Processor', 'desc' => 'The brain of your computer'],
                            'motherboard' => ['icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'label' => 'Motherboard', 'desc' => 'Connects all components'],
                            'gpu' => ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Graphics Card', 'desc' => 'For gaming and rendering'],
                            'ram' => ['icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label' => 'Memory', 'desc' => 'Multitasking capability'],
                            'storage' => ['icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4', 'label' => 'Storage', 'desc' => 'Store games and files'],
                            'cooling' => ['icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Cooling', 'desc' => 'Keep temperatures low'],
                            'psu' => ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Power Supply', 'desc' => 'Power your system'],
                            'case' => ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label' => 'Case', 'desc' => 'Housing for your parts'],
                        ],
                        'Peripherals & Accessories' => [
                            'monitor' => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Monitor', 'desc' => 'Display screen'],
                            'keyboard' => ['icon' => 'M15 12a1 1 0 11-2 0 1 1 0 012 0zm-7 0a1 1 0 11-2 0 1 1 0 012 0zm-5 6a1 1 0 100-2 1 1 0 000 2zm16 0a1 1 0 100-2 1 1 0 000 2zm-5 0a1 1 0 100-2 1 1 0 000 2z', 'label' => 'Keyboard', 'desc' => 'Typing interface'],
                            'mouse' => ['icon' => 'M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122', 'label' => 'Mouse', 'desc' => 'Pointing device'],
                            'headset' => ['icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z', 'label' => 'Headset', 'desc' => 'Audio output'],
                             'mousepad' => ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'label' => 'Mousepad', 'desc' => 'Surface for mouse'],
                            'microphone' => ['icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z', 'label' => 'Microphone', 'desc' => 'Input audio'],
                            'webcam' => ['icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'label' => 'Webcam', 'desc' => 'Video capture'],
                            'speakers' => ['icon' => 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z', 'label' => 'Speakers', 'desc' => 'External audio'],
                        ],
                    ];
                @endphp

                @foreach($groups as $groupName => $items)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-900">{{ $groupName }}</h2>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ count(array_filter($items, fn($item, $key) => $selectedComponents[$key] ?? null, ARRAY_FILTER_USE_BOTH)) }} Selected</span>
                        </div>
                        
                        <div class="divide-y divide-slate-50">
                            @foreach($items as $type => $info)
                                @php
                                    $component = $selectedComponents[$type] ?? null;
                                    $isSelected = $component !== null;
                                    $hasError = $this->hasIssue($type);
                                @endphp
                                
                                <div class="group p-5 hover:bg-slate-50 transition-colors">
                                    <div class="flex flex-col sm:flex-row gap-5 sm:items-center">
                                        <!-- Mobile Label (visible on small screens only) -->
                                        <div class="sm:hidden flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $info['icon'] }}" />
                                                </svg>
                                            </div>
                                            <div>
                                                 <span class="font-bold text-slate-900">{{ $info['label'] }}</span>
                                                 <p class="text-xs text-slate-500">{{ $info['desc'] }}</p>
                                            </div>
                                        </div>

                                        <!-- Desktop Icon & Label -->
                                        <div class="hidden sm:flex items-center gap-4 w-1/3">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors
                                                {{ $isSelected ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400 group-hover:bg-white group-hover:shadow-sm' }}
                                                {{ $hasError ? '!bg-red-100 !text-red-500' : '' }}">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $info['icon'] }}" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-bold text-slate-700 group-hover:text-slate-900 transition-colors">{{ $info['label'] }}</span>
                                                <span class="text-xs text-slate-500">{{ $info['desc'] }}</span>
                                            </div>
                                        </div>

                                        <!-- Selection Area -->
                                        <div class="flex-1 min-w-0">
                                            @if($isSelected)
                                                <div class="bg-white border border-slate-200 rounded-xl p-3 flex items-center gap-4 shadow-sm group-hover:border-indigo-200 transition-all">
                                                    <img src="{{ $component['image'] }}" alt="{{ $component['name'] }}" class="w-12 h-12 object-contain mix-blend-multiply">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-bold text-slate-900 truncate text-sm">{{ $component['name'] }}</p>
                                                        <p class="text-emerald-600 font-bold text-sm">{{ number_format($component['price'], 0) }} IQD</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('pc.select', $type) }}" wire:navigate class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Change">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                            </svg>
                                                        </a>
                                                        <button wire:click="removeComponent('{{ $type }}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('pc.select', $type) }}" wire:navigate class="block w-full border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-indigo-400 hover:bg-indigo-50/30 transition-all group/btn">
                                                    <span class="text-sm font-bold text-slate-400 group-hover/btn:text-indigo-600 flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Select {{ $info['label'] }}
                                                    </span>
                                                </a>
                                            @endif
                                            
                                             @if($hasError)
                                                <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Compatibility Issue
                                                </p>
                                             @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Summary Sidebar -->
            <div class="lg:w-96 flex-shrink-0">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-6 lg:sticky lg:top-24 space-y-6">
                     <h3 class="text-xl font-black text-slate-900">System Summary</h3>
                     
                     @if($this->getSelectedCount() > 0)
                        <div class="space-y-4">
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Parts</span>
                                    <p class="text-lg font-black text-slate-900">{{ $this->getSelectedCount() }}</p>
                                </div>
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Power</span>
                                    <p class="text-lg font-black text-slate-900">{{ $systemAnalysis['power']['total_draw'] ?? 0 }}W</p>
                                </div>
                            </div>
                            
                            <!-- Compatibility Status -->
                            <div class="p-4 rounded-xl {{ $compatibilityColor === 'green' ? 'bg-emerald-50 text-emerald-700' : 'bg-yellow-50 text-yellow-700' }}">
                                <div class="flex items-center gap-3 mb-2">
                                     <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                     </svg>
                                     <span class="font-bold">{{ $compatibilityLabel }}</span>
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
                                            {{ $warning['type'] === 'error' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                            {{ $warning['type'] === 'warning' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                            {{ $warning['type'] === 'info' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}">
                                            {{ $warning['message'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                     @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium text-sm">Start selecting parts to build your custom PC.</p>
                        </div>
                     @endif
                     
                     <div class="pt-6 border-t border-slate-100">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-sm font-bold text-slate-500">Total Price</span>
                            <span class="text-3xl font-black text-slate-900">{{ number_format($totalPrice, 0) }}<span class="text-sm text-slate-400 ml-1">IQD</span></span>
                        </div>
                        
                        <button 
                            wire:click="addBuildToCart"
                            class="w-full py-4 rounded-xl font-bold transition-all shadow-lg
                                {{ $this->isValidBuild() 
                                    ? 'bg-gradient-to-r from-slate-900 to-slate-800 text-white hover:shadow-xl hover:-translate-y-1' 
                                    : 'bg-slate-100 text-slate-400 cursor-not-allowed shadow-none' }}"
                            {{ !$this->isValidBuild() ? 'disabled' : '' }}
                        >
                            {{ $this->getSelectedCount() > 0 ? 'Add Build to Cart' : 'Make a Selection' }}
                        </button>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
