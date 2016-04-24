@if ($items->total() > 1)
<nav class="pagination">
    <ul>
        @if ($items->currentPage() > 3)
            <li><a href="{{ $items->url(1) }}">1</a></li>
            <li><span>...</span></li>
        @endif
        @for ($i = 1; $i <= $items->lastPage(); $i++)
            @if ($i - 3 < $items->currentPage() && $i + 3 > $items->CurrentPage())
            <li><a class="{{ $i == $items->currentPage() ? 'is-active' : '' }}" href="{{ $items->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor
        @if ($items->lastPage() - $items->currentPage() >= 3)
            <li><span>...</span></li>
            <li><a href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a></li>
        @endif
    </ul>
</nav>
@endif
