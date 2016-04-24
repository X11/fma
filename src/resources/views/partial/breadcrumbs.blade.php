<section class="section small">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="{{ url('/') }}">FMA</a></li>
            @foreach($breadcrumbs as $crumb)
                <li><a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a></li>
            @endforeach
        </ul>
    </div>
</section>
