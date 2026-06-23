<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-delete', 'style' => 'border:none;']) }}>
    {{ $slot }}
</button>
