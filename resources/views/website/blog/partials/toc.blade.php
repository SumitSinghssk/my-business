{{-- Table of contents links. Expects $toc; highlights the `active` id from the enclosing Alpine scope. --}}
<nav class="space-y-0.5" aria-label="Table of contents">
    @foreach ($toc as $item)
        <a
            href="#{{ $item['id'] }}"
            :class="active === @js($item['id']) ? 'border-primary bg-surface-container text-on-surface' : 'border-transparent text-on-surface-variant'"
            class="font-body-sm text-body-sm hover:bg-surface-container hover:text-primary flex gap-2 border-l-2 py-1.5 pr-2 pl-3 leading-snug transition-colors"
        >
            <span class="text-outline shrink-0 font-mono text-xs leading-5">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            <span>{{ $item['title'] }}</span>
        </a>
    @endforeach
</nav>
