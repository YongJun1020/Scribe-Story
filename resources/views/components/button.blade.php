@props([
    'type' => 'primary',
    'color' => null,
])

@php
$presets = [
    'primary'   => 'bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-gray-500',
    'secondary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:ring-blue-400',
    'outline'   => 'bg-white text-blue-700 border-blue-200 hover:bg-blue-50 focus:ring-blue-300 border',
    'ghost'     => 'bg-transparent text-blue-600 hover:text-blue-800 hover:bg-blue-50 focus:ring-blue-200',
    'danger'    => 'bg-red-500 text-white hover:bg-red-600 focus:bg-red-600 active:bg-red-700 focus:ring-red-400',
    'success'   => 'bg-cyan-500 text-white hover:bg-cyan-600 focus:bg-cyan-600 active:bg-cyan-700 focus:ring-cyan-400',
];

$selectedColor = $color ?? ($presets[$type] ?? $presets['primary']);

$classes = $selectedColor . ' inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
    {{ $slot }}
</button>