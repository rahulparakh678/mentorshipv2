@extends('layouts.mentor')
@section('content')


<style type="text/css">
    .content {
        padding: 10px;
        float: right;
        width: 95%;
    }
    .module-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        height: 350px; /* Set a fixed height to ensure uniform size */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Keeps "Explore" button at the bottom */
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
    .module-title {
        color: #007bff;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .module-name {
        color: #555;
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .module-description {
        color: #555;
        font-size: 16px;
        flex-grow: 1; /* Allows the description area to grow within the card */
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 20px;
    }
    .module-progress {
        margin-bottom: 15px;
    }
    .progress {
        height: 10px;
        border-radius: 5px;
    }
    .progress-bar {
        background-color: #28a745;
    }
    .module-action {
        display: flex;
        justify-content: space-between; /* Ensures space between buttons */
        align-items: center;
        gap: 10px; /* Adds spacing between the buttons */
    }

    .action-btn {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none; /* Removes underline */

    }

    .action-btn:hover {
        background-color: #0056b3;
        color: #fff;
        text-decoration: none; /* Removes underline */

    }

    .completed-btn {
        background-color: #28a745; /* Green background for the Completed button */
        font-weight: bold;
    }

    .completed-btn:hover {
        background-color: #218838; /* Darker green on hover */
        color: #fff;
        text-decoration: none; /* Removes underline */

    }


</style>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<center><h1>Modules</h1></center>
<hr>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="content">
                <!-- Modules -->
                <div class="container mt-3">
                    <!-- <h1 class="page-title">Explore Modules</h1> -->
                    <!-- <br> -->
                    <div class="row">
                        @foreach($modules as $module)
                        <div class="col-md-4">
                            <div class="module-card">
                                <!-- Progress Section -->
                                {{--<div class="module-progress">
                                    <!-- <p><strong>Progress:</strong></p> -->
                                    <div class="progress">]
                                        <div class="progress-bar" role="progressbar" style="width: {{ $module->progress ?? 0 }}%;" aria-valuenow="{{ $module->progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>{{ $module->progress ?? 0 }}% completed</small>
                                </div>--}}

                                <h2 class="module-name">{{ $module->name ?? '' }}</h2>
                                <p class="module-description">{{ Str::limit($module->description, 50) ?? '' }}</p> <!-- Limits the text to 100 characters -->
                                
                                <!-- Displaying the objective -->
                                <p class="module-objective">
                                    <strong>Objective: </strong>{{ Str::limit($module->objective, 150) ?? '' }} <!-- Limits the objective to 100 characters -->
                                </p>

                                <div class="module-action">
                                    <a href="{{ route('showmentorchapters', ['module_id' => $module->id]) }}" class="action-btn" target="_blank">Explore</a>
                                    <!-- Completed Button
                                    <a href="#" class="action-btn completed-btn" target="_blank">Completed</a>

                                    {{--<a href="{{ route('markmodulecompleted', ['module_id' => $module->id]) }}" class="action-btn completed-btn" target="_blank">Completed</a>--}}
                                </div> -->

                                <form action="{{ route('modulecompletion') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mentee_id" value="{{ $mentee->id }}">
                                    <input type="hidden" name="module_id" value="{{ $module->id }}">
                                    <input type="hidden" name="mentor_id" value="{{ auth()->user()->id }}"> <!-- Logged-in mentor ID -->
                                    
                                    <button type="submit" class="action-btn">Completed</button>
                                </form>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
