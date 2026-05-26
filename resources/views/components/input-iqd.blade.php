@props(['disabled' => false])

@php
    $wireModel = $attributes->wire('model')->value();
    $hasError = $wireModel && $errors->has($wireModel);
@endphp

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
        <span class="text-content-muted dark:text-dark-500 font-bold sm:text-sm group-focus-within:text-brand-accent transition-colors">IQD</span>
    </div>
    
    <input 
        type="text" 
        x-model="displayValue" 
        @input="update($event)"
        maxlength="15"
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->whereDoesntStartWith('wire:model') !!}
        class="block w-full px-4 py-3 rounded-xl border {{ $hasError ? 'border-red-500 bg-red-50/5 text-content-primary focus:ring-red-500/15 focus:border-red-500' : 'border-border-subtle dark:border-dark-700 bg-surface-secondary/50 dark:bg-dark-800' }} text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent focus:bg-surface-primary dark:focus:bg-dark-900 transition-all duration-200 hover:border-brand-base/30 shadow-sm pr-12 font-medium"
        placeholder="0"
    >
</div>
