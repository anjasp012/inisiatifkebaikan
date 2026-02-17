<li class="sidebar-item">
    <a {{ $attributes->merge(['class' => 'sidebar-link']) }} wire:navigate wire:current="active">
        {{ $slot }}
    </a>
</li>
