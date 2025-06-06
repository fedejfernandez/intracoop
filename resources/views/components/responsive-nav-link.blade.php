@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-green-500 dark:border-green-300 text-start text-base font-medium text-green-700 dark:text-green-100 bg-green-100 dark:bg-green-900 focus:outline-none focus:text-green-800 dark:focus:text-green-50 focus:bg-green-200 dark:focus:bg-green-800 focus:border-green-600 dark:focus:border-green-200 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-green-800 dark:text-green-200 hover:text-green-900 dark:hover:text-green-100 hover:bg-green-50 dark:hover:bg-green-700 hover:border-green-300 dark:hover:border-green-500 focus:outline-none focus:text-green-900 dark:focus:text-green-100 focus:bg-green-50 dark:focus:bg-green-700 focus:border-green-300 dark:focus:border-green-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
