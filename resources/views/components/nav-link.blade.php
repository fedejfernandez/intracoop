@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-green-300 dark:border-green-400 text-sm font-medium leading-5 text-white dark:text-white focus:outline-none focus:border-green-400 dark:focus:border-green-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-green-100 dark:text-green-200 hover:text-white dark:hover:text-white hover:border-green-200 dark:hover:border-green-400 focus:outline-none focus:text-white dark:focus:text-white focus:border-green-200 dark:focus:border-green-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
