@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-gray-50 border-transparent focus:bg-white focus:border-primary focus:ring-primary rounded-[14px] shadow-sm text-sm']) }}>
