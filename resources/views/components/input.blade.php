@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-red-300 focus:border-red-200 focus:ring-red-200 rounded-md shadow-sm']) !!}>
