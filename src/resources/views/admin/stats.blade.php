@extends('layouts.admin')

@section('hero.icon', 'wrench')
@section('hero.title', 'Updates')
@section('hero.content', 'Update application')

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

    <div class="heading">
        <h2 class="title">Series</h2>
        <p class="subtitle">Current: <strong>{{ $serieCount }}</strong></p>
    </div>
    <br>
    <div class="content">
        <canvas id="serieChart" width="200" height="75"></canvas>
    </div>

    <div class="heading">
        <h2 class="title">Episodes</h2>
        <p class="subtitle">Current: <strong>{{ $episodeCount }}</strong></p>
    </div>
    <br>
    <div class="content">
        <canvas id="episodeChart" width="200" height="75"></canvas>
    </div>

    <div class="heading">
        <h2 class="title">Users</h2>
        <p class="subtitle">Current: <strong>{{ $userCount }}</strong></p>
    </div>
    <br>
    <div class="content">
        <canvas id="userChart" width="200" height="75"></canvas>
    </div>

    <div class="heading">
        <h2 class="title">Logins</h2>
    </div>
    <br>
    <div class="content">
        <canvas id="loginChart" width="200" height="75"></canvas>
    </div>

    <div class="heading">
        <h2 class="title">People</h2>
        <p class="subtitle">Current: <strong>{{ $peopleCount }}</strong></p>
    </div>
    <br>
    <div class="content">
        <canvas id="peopleChart" width="200" height="75"></canvas>
    </div>

    <div class="heading">
        <h2 class="title">Episodes watched</h2>
    </div>
    <br>
    <div class="content">
        <canvas id="episodeWatchedChart" width="200" height="75"></canvas>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.0/Chart.min.js"></script>
<script type="text/javascript" charset="utf-8">
var ctx = document.getElementById("serieChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [{!! $serieCountStats->implode('created_at', ',') !!}],
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
            data: [{{ $serieCountStats->implode('value', ',') }}],
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript" charset="utf-8">
var ctx = document.getElementById("episodeChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [{!! $episodeCountStats->implode('created_at', ',') !!}],
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
            data: [{{ $episodeCountStats->implode('value', ',') }}],
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript" charset="utf-8">
var ctx = document.getElementById("userChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [{!! $userCountStats->implode('created_at', ',') !!}],
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
            data: [{{ $userCountStats->implode('value', ',') }}],
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript" charset="utf-8">
var ctx = document.getElementById("loginChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [{!! $loginStats->implode('created_at', ',') !!}],
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
            data: [{{ $loginStats->implode('value', ',') }}],
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript" charset="utf-8">
var ctx = document.getElementById("peopleChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [{!! $peopleCountStats->implode('created_at', ',') !!}],
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
            data: [{{ $peopleCountStats->implode('value', ',') }}],
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript" charset="utf-8">
var ctx = document.getElementById("episodeWatchedChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [{!! $episodeWatchedStats->implode('created_at', ',') !!}],
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
            data: [{{ $episodeWatchedStats->implode('value', ',') }}],
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
@endsection
