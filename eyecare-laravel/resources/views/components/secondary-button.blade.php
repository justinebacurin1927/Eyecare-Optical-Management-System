<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-sort', 'style' => 'border:none;']) }}>
    {{ $slot }}
</button>
