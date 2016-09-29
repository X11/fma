@extends('layouts.admin')

@section('hero.icon', 'wrench')
@section('hero.title', 'Updates')
@section('hero.content', 'Update application')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.0/Chart.min.js"></script>
@endpush

@section('main')
<section class="section is-paddingless">
    <div class="columns">
        <div class="column">
            <div class="heading">
                <h2 class="title">Jobs</h2>
                <p class="subtitle">Current: <strong>{{ $jobCount }}</strong></p>
            </div>
        </div>
        <div class="column">
            <div class="heading">
                <h2 class="title">Failed</h2>
                <p class="subtitle">Current: <strong>{{ $failedJobCount }}</strong></p>
            </div>
        </div>
    </div>
    <br>
    @foreach ($chars as $key => $char)
        <div class="heading">
            <h2 class="title">{{ $char['title'] }}</h2>
            @if ($char['current'])
                <p class="subtitle">Current: <strong>{{ $char['current'] }}</strong></p>
            @endif
            <br>
            <div class="content">
                <canvas id="{{ $key }}Chart" width="200" height="75"></canvas>
            </div>
        </div>
        @push('scripts')
            <script type="text/javascript" charset="utf-8">
            var ctx = document.getElementById("{{ $key }}Chart");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                labels: [{!! $char['labels'] !!}],
                    datasets: [{
                        fill: false,
                        borderColor: "rgba(75,192,192,1)",
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        spanGaps: false,
                        pointHoverRadius: 8,
                        pointHitRadius: 10,
                        label: 'Count',
                        tension: 0,
                        data: [{{ $char['data'] }}],
                    }]
                },
                options: {
                    legend: {
                        display: false,
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:false
                            }
                        }]
                    }
                }
            });
            </script>
        @endpush
    @endforeach
</section>
@endsection
