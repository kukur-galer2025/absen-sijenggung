@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-400 text-start text-base font-medium text-emerald-50 bg-emerald-900 focus:outline-none focus:text-emerald-100 focus:bg-emerald-800 focus:border-emerald-500 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-emerald-200 hover:text-white hover:bg-emerald-700 hover:border-emerald-400 focus:outline-none focus:text-white focus:bg-emerald-700 focus:border-emerald-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
