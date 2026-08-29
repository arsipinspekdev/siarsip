@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'helper' => null,
    'placeholder' => '-- Pilih Opsi --',
    'id' => null,
])

@php
$inputId = $id ?? $name;
$hasError = $errors->has($name);
$selectedValue = old($name, $selected);
@endphp

<div>
    @if($label)
        <label for="{{ $inputId }}" class="block text-xs font-bold text-neutral-700 mb-1.5 uppercase tracking-wider">
            {{ $label }}
            @if($required)
                <span class="text-danger-600" title="Wajib diisi">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $inputId }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full px-3.5 py-2.5 text-sm text-neutral-900 bg-neutral-50/80 border rounded-xl transition duration-150 focus:outline-none focus:bg-white appearance-none cursor-pointer pr-10 ' . 
                ($hasError 
                    ? 'border-danger-500 focus:border-danger-500 focus:ring-2 focus:ring-danger-100' 
                    : 'border-neutral-300/80 focus:border-primary-500 focus:ring-2 focus:ring-primary-100')
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled {{ $selectedValue === null || $selectedValue === '' ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @foreach($options as $val => $optLabel)
                <option value="{{ $val }}" {{ (string)$selectedValue === (string)$val ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>
        
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @if($helper && !$hasError)
        <p class="mt-1.5 text-xs text-neutral-500">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-bold text-danger-600 flex items-center gap-1" role="alert">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
