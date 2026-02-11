<div>
    <!-- Floating Chat Toggle Button -->
    @if(!$isOpen)
    <button 
        wire:click="toggleChat"
        class="fixed bottom-6 right-6 z-50 w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-2xl hover:shadow-cyan-500/50 transform hover:scale-110 transition-all duration-300 flex items-center justify-center ring-4 ring-white/10"
        aria-label="Open Chat"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center ring-2 ring-slate-900">
            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
        </span>
    </button>
    @endif

    <!-- Full Height Sidebar Chat Panel -->
    @if($isOpen)
    <div class="fixed inset-y-0 right-0 z-50 w-full sm:w-96 md:w-[420px] bg-slate-900 shadow-2xl border-l border-slate-700/50 flex flex-col animate-slide-in">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-cyan-600 to-blue-700 px-4 sm:px-5 py-4 flex items-center justify-between relative overflow-hidden flex-shrink-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Ccircle cx=\"15\" cy=\"15\" r=\"1\" fill=\"white\" fill-opacity=\"0.1\"/%3E%3C/svg%3E')]"></div>
            <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-base sm:text-lg">PC Assistant</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        <p class="text-white/80 text-xs sm:text-sm">Online</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1 relative z-10">
                <button wire:click="clearChat" class="text-white/70 hover:text-white hover:bg-white/10 p-2 sm:p-2.5 rounded-xl transition-all" title="New Chat">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <button wire:click="toggleChat" class="text-white/70 hover:text-white hover:bg-white/10 p-2 sm:p-2.5 rounded-xl transition-all" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div 
            class="flex-1 overflow-y-auto px-3 sm:px-5 py-4 sm:py-5 space-y-4 sm:space-y-5 bg-gradient-to-b from-slate-900 to-slate-950"
            id="chat-messages-{{ $sessionId }}"
            x-data
            x-init="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
            x-effect="$wire.messages; $nextTick(() => $el.scrollTop = $el.scrollHeight)"
        >
            @foreach($messages as $index => $message)
                <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    @if($message['role'] === 'bot')
                        <div class="flex items-start gap-2 sm:gap-3 w-full max-w-full">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg sm:rounded-xl flex-shrink-0 flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-2 sm:space-y-3 flex-1 min-w-0">
                                <div class="bg-slate-800/80 backdrop-blur-sm rounded-xl sm:rounded-2xl rounded-tl-md px-3 sm:px-4 py-2 sm:py-3 text-slate-200 text-xs sm:text-sm leading-relaxed shadow-lg border border-cyan-500/20">
                                    {!! nl2br(e($message['content'])) !!}
                                </div>
                                
                                <!-- Product Cards -->
                                @if(!empty($message['products']))
                                    <div class="grid gap-2">
                                        @foreach(array_slice($message['products'], 0, 4) as $product)
                                            <a href="{{ route('product.view', $product['id']) }}" class="block bg-slate-800/60 hover:bg-slate-700/60 rounded-lg sm:rounded-xl p-2 sm:p-3 border border-slate-700/50 hover:border-cyan-500/50 transition-all group">
                                                <div class="flex items-center gap-2 sm:gap-3">
                                                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                                                        <img src="{{ $product['image'] ?? '/images/placeholder.png' }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-white text-xs sm:text-sm font-medium truncate group-hover:text-cyan-300 transition-colors">{{ $product['name'] }}</p>
                                                        <p class="text-cyan-400 font-bold text-sm sm:text-lg">{{ number_format($product['price'], 0) }} IQD</p>
                                                    </div>
                                                    <div class="flex-shrink-0 hidden sm:block">
                                                        <span class="bg-cyan-600/20 text-cyan-400 text-xs px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg group-hover:bg-cyan-600 group-hover:text-white transition-all">
                                                            View
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Build Summary -->
                                @if(!empty($message['build']))
                                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-cyan-500/30 shadow-lg">
                                        <div class="flex items-center gap-2 mb-2 sm:mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                            </svg>
                                            <span class="text-cyan-400 text-xs sm:text-sm font-bold uppercase tracking-wide">Your Build</span>
                                        </div>
                                        <div class="space-y-1 sm:space-y-2">
                                            @foreach($message['build'] as $type => $component)
                                                <div class="flex justify-between items-center text-xs sm:text-sm py-1 sm:py-1.5 border-b border-slate-700/50 last:border-0 gap-2">
                                                    <span class="uppercase text-slate-500 text-xs font-medium flex-shrink-0">{{ $type }}</span>
                                                    <span class="text-slate-300 text-right truncate">{{ is_array($component) ? $component['name'] : $component }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($message['buildTotal'])
                                            <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-cyan-500/30 flex justify-between items-center">
                                                <span class="text-cyan-400 font-semibold text-sm">Total</span>
                                                <span class="text-white font-bold text-lg sm:text-xl">{{ number_format($message['buildTotal'], 0) }} IQD</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Action Options -->
                                @if(!empty($message['options']))
                                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                                        @foreach($message['options'] as $option)
                                            @php
                                                $actionParams = $option['params'] ?? [];
                                            @endphp
                                            <button 
                                                wire:click="handleAction('{{ $option['action'] }}', {{ json_encode($actionParams) }})"
                                                class="bg-slate-800 hover:bg-cyan-600 text-slate-300 hover:text-white text-xs px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl border border-slate-700 hover:border-cyan-500 transition-all duration-200 flex items-center gap-1.5 sm:gap-2 shadow-sm"
                                            >
                                                @if(str_contains(strtolower($option['label']), 'search') || str_contains(strtolower($option['label']), 'find') || str_contains(strtolower($option['label']), 'different'))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                @elseif(str_contains(strtolower($option['label']), 'build') || str_contains(strtolower($option['label']), 'pc builder'))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                                    </svg>
                                                @elseif(str_contains(strtolower($option['label']), 'compat') || str_contains(strtolower($option['label']), 'check'))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif(str_contains(strtolower($option['label']), 'view') || str_contains(strtolower($option['label']), 'go to'))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                @elseif(str_contains(strtolower($option['label']), 'iqd'))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif(str_contains(strtolower($option['label']), 'help'))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                                <span>{{ $option['label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 rounded-xl sm:rounded-2xl rounded-tr-md px-3 sm:px-4 py-2 sm:py-3 text-white text-xs sm:text-sm max-w-[85%] shadow-lg">
                            {{ $message['content'] }}
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Typing Indicator -->
            @if($isTyping)
                <div class="flex items-start gap-2 sm:gap-3">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg sm:rounded-xl flex-shrink-0 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="bg-slate-800 rounded-xl sm:rounded-2xl rounded-tl-md px-4 py-3 border border-slate-700/50">
                        <div class="flex gap-1.5">
                            <div class="w-2 h-2 sm:w-2.5 sm:h-2.5 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-2 h-2 sm:w-2.5 sm:h-2.5 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                            <div class="w-2 h-2 sm:w-2.5 sm:h-2.5 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions Bar -->
        <div class="px-3 sm:px-4 py-2 sm:py-3 bg-slate-800/50 border-t border-slate-700/50 flex-shrink-0">
            <div class="flex gap-1.5 sm:gap-2 overflow-x-auto pb-1">
                <button wire:click="quickAction('gpus')" class="flex-shrink-0 bg-slate-700/50 hover:bg-cyan-600 text-slate-400 hover:text-white text-xs px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg transition-all flex items-center gap-1 sm:gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    GPUs
                </button>
                <button wire:click="quickAction('cpus')" class="flex-shrink-0 bg-slate-700/50 hover:bg-cyan-600 text-slate-400 hover:text-white text-xs px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg transition-all flex items-center gap-1 sm:gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    CPUs
                </button>
                <button wire:click="quickAction('build')" class="flex-shrink-0 bg-slate-700/50 hover:bg-cyan-600 text-slate-400 hover:text-white text-xs px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg transition-all flex items-center gap-1 sm:gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Build
                </button>
                <button wire:click="quickAction('compat')" class="flex-shrink-0 bg-slate-700/50 hover:bg-cyan-600 text-slate-400 hover:text-white text-xs px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg transition-all flex items-center gap-1 sm:gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Compat
                </button>
                <button wire:click="quickAction('help')" class="flex-shrink-0 bg-slate-700/50 hover:bg-cyan-600 text-slate-400 hover:text-white text-xs px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg transition-all flex items-center gap-1 sm:gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Help
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 sm:p-4 border-t border-slate-700/50 bg-slate-900 flex-shrink-0">
            <form wire:submit.prevent="sendMessage" class="flex gap-2 sm:gap-3">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        wire:model="userMessage"
                        placeholder="Ask about PC parts..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-white placeholder-slate-500 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                        autocomplete="off"
                    >
                </div>
                <button 
                    type="submit"
                    class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white p-2.5 sm:p-3 rounded-lg sm:rounded-xl transition-all shadow-lg hover:shadow-cyan-500/25 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    wire:loading.attr="disabled"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Backdrop for mobile -->
    <div 
        wire:click="toggleChat"
        class="fixed inset-0 bg-black/50 z-40 sm:hidden"
    ></div>
    @endif

    <style>
        @keyframes slide-in {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>
</div>
