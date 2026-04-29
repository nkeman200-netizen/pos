@props(['class' => 'w-6 h-6'])
{{-- {{ $attributes->merge(['class' => $class]) }} --}}
<svg xmlns="http://www.w3.org/2000/svg" class="{{ $attributes->merge(['class' => $class]) }}text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
</svg>
