<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-edit', 'style' => 'text-transform:uppercase;border:none;']) }}>
    {{ $slot }}
</button>
