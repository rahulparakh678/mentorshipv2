@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{{--
<div class="container-fluid">
    <div class="row">
        
    
    <div class="col-md-6">
        <div class="card">
            <h1>Hello</h1>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <h1>Hello</h1>
        </div>
        
    </div>
    </div>
</div>
--}}
{{--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Mentorship Meetings</div>
                <div class="card-body">
                   <table class="table table-striped ">
                       <thead>
                           <th scope="col">Module Name</th>
                           <th scope="col">Status</th>
                           <th scope="col">Date</th>
                           <th scope="col">Meeting Link</th>
                           <th>Recording</th>
                           <th>Action</th>
                       </thead>
                       <tbody>
                        @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->name }}</td>
                            <td>
                                @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                    @foreach($sessions[$module->id] as $session)
                                        @if(!empty($session->sessiondatetime))
                                            <span class="badge badge-primary">Scheduled</span>
                                        @else
                                            <span class="badge badge-dark">Not Scheduled</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge badge-dark">Not Scheduled</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                        @foreach($sessions[$module->id] as $session)
                                            {{ $session->sessiondatetime }}<br>
                                         @endforeach
                                    @else
                                    
                                @endif
                            </td>
                            <td>
                                                                
                                    @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                        @foreach($sessions[$module->id] as $session)
                                            <a href="{{ $session->sessionlink }}" target="_blank" class="btn btn-xs btn-primary">Meeting Link</a><br>
                                        @endforeach
                                    @endif

                                
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                <a href="{{ route('mentor.markChapterCompletion', $module->id) }}" class="btn btn-xs btn-primary">Mark Chapter Completions</a>
                                
                            </td>
                        </tr>
                        @endforeach
                       
                       </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
--}}

<div class="main">
    <div class="academic-record">
            <h4>Mentee Module Progress</h4>
            <canvas id="progressChart" class="chart-size"></canvas>
    </div>
</div>
    <style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

    * {
        font-family: "Ubuntu", sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --blue: #2a2185;
        --white: #fff;
        --gray: #f5f5f5;
        --black1: #222;
        --black2: #999;
    }

    .container {
        position: relative;
        width: 100%;
    }

    .navigation {
        position: fixed;
        width: 270px;
        height: 100%;
        background: var(--black1);
        transition: 0.5s;
        overflow: hidden;
    }
    .navigation.active {
        width: 80px;
    }

    .navigation ul {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    .navigation ul li {
        position: relative;
        width: 100%;
        list-style: none;
    }

    .navigation ul li a {
        position: relative;
        display: block;
        width: 100%;
        display: flex;
        text-decoration: none;
        color: var(--white);
        padding: 10px 20px;
        transition: background-color 0.3s, color 0.3s;
    }

    .navigation ul li a:hover {
        color: var(--black1);
        background-color: #ffffff;
    }

    .navigation ul li a .icon {
        display: block;
        min-width: 60px;
        height: 60px;
        line-height: 60px;
        text-align: center;
    }

    .navigation ul li a .title {
        display: block;
        padding: 0 10px;
        height: 60px;
        line-height: 60px;
        text-align: start;
        white-space: nowrap;
    }

    .main {
        margin-left: 270px;
        transition: 0.5s;
    }

    .main.active {
        margin-left: 80px;
    }

    .topbar {
        width: 100%;
        height: 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        background: var(--white);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .toggle {
        font-size: 1.5rem;
        cursor: pointer;
    }

    .search {
        width: 400px;
        position: relative;
    }

    .search input {
        width: 100%;
        height: 40px;
        border-radius: 20px;
        padding: 0 20px;
        padding-left: 40px;
        font-size: 16px;
        border: 1px solid var(--black2);
        outline: none;
    }

    .search ion-icon {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 1.2rem;
    }

    .user {
        width: 40px;
        height: 40px;
        cursor: pointer;
    }

    .user img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .academic-record {
        display:flex;
        margin-top: 15px;
        margin-left: 15PX;
        align-content: center;

    }

    .chart-size {
        width: 100% !important;
        height: 400px !important;
    }
    .academic-record {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    .academic-record h4 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }


    @media (max-width: 768px) {
        .navigation {
            left: -300px;
        }

        .navigation.active {
            left: 0;
        }

        .main {
            margin-left: 0;
        }

        .main.active {
            margin-left: 300px;
        }
    }

    @media (max-width: 480px) {
        .navigation.active {
            width: 100%;
            left: 0;
        }

        .main.active .toggle {
            color: #fff;
            position: fixed;
            right: 0;
            left: initial;
        }
    }
</style>
<script>
    // Menu toggle
    const menuToggle = document.querySelector('.toggle');
    const navigation = document.querySelector('.navigation');
    const main = document.querySelector('.main');

    menuToggle.addEventListener('click', () => {
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('progressChart').getContext('2d');
        var progressData = @json($progressData);
        var moduleNames = progressData.map(data => data.module_name);
        var completionPercentages = progressData.map(data => data.completion_percentage);

        var progressChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: moduleNames,
                datasets: [{
                    data: completionPercentages,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',  // bg-success
                        'rgba(23, 162, 184, 0.7)',  // bg-info
                        'rgba(255, 193, 7, 0.7)',   // bg-warning
                        'rgba(220, 53, 69, 0.7)',   // bg-danger
                        'rgba(102, 16, 242, 0.7)'   // bg-purple
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(102, 16, 242, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Modules'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Progress (%)'
                        }
                    }
                }
            }
        });
    });
</script>

@endsection

@push('scripts')

@endpush