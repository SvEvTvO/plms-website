@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-xs tracking-wider text-gray-500 uppercase mb-2']) }}>
    {{ $value ?? $slot }}
</label>
