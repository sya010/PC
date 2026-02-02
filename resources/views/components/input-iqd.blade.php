@props(['disabled' => false])

<div 
    x-data="{ 
        value: @entangle($attributes->wire('model')),
        displayValue: '', 
        format(val) {
            if (!val) return '';
            // Remove non-numeric chars
            let num = val.toString().replace(/[^0-9]/g, '');
            // Format with commas
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
        update(event) {
            // Get value from input, remove commas
            let inputVal = event.target.value.replace(/,/g, '');
            // Update wire model (raw number)
            this.value = inputVal;
            // Update display value
            this.displayValue = this.format(inputVal);
        }
    }" 
    x-init="displayValue = format(value); $watch('value', val => displayValue = format(val))"
    class="relative group"
>
    <!-- IQD Badge -->
    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
        <span class="text-gray-400 font-bold sm:text-sm group-focus-within:text-indigo-500 transition-colors">IQD</span>
    </div>
    
    <input 
        type="text" 
        x-model="displayValue" 
        @input="update($event)"
        maxlength="15"
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->whereDoesntStartWith('wire:model') !!}
        class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all duration-200 hover:border-indigo-300 shadow-sm pr-12 font-medium"
        placeholder="0"
    >
</div>
