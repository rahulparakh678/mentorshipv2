@extends('layouts.admin')
@section('content')

<style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .academic-record {
        margin-left: 50px;
    }

    .tableContainer {
        margin-top: 30px;
        width: 1000px;
        margin-left: 50px;
        display: none; /* Hidden by default */
    }

    .export-button-container {
        margin-top: 15px;
        text-align: right;
    }
</style>

<div class="card">
    <div class="card-header">
        Module Progress
    </div>

    <div class="card-body">
        <div class="academic-record">
            <h4>Overall Module Progress</h4>
            <div class="toggle-buttons">
                <button class="btn btn-secondary" id="overallmoduleBtn">Chapterwise completion report</button>
                <button class="btn btn-success" id="tableViewBtn">Chapterwise progress report</button>
            </div>

            <!-- Export Button - visible for all tables -->
            <div class="export-button-container">
                <form action="#" method="get">
                    @csrf
                    <button type="submit" class="btn btn-success">Export to Excel</button>
                </form>
            </div>
        </div>

        <!-- Default Table (Module Progress) -->
        <div class="tableContainer" id="moduleProgressTable">
            <h3>Chapterwise Completion Report</h3>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Mentee Name</th>
                        <th>Mentor Name</th>
                        <th>Module Name</th>
                        <th>Chapter Name</th>
                        <th>Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($progressData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->mentee_name }}</td>
                            <td>{{ $data->mentor_name }}</td>
                            <td>{{ $data->module_name }}</td>
                            <td>{{ $data->chapter_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->completed_at)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Chapterwise Progress Table (Hidden by default) -->
        <div class="tableContainer" id="chapterwiseProgressTable">
            <h5>Chapterwise Progress Report</h5>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Mentee</th>
                        <th>Mentor</th>
                        <th>Module Name</th>
                        <th>Total Chapters Completed</th>
                        <th>Total Chapters Pending</th>
                        <th>Completed Chapters Name</th>
                        <th>Pending Chapters Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Rahul</td>
                        <td>Ranjini</td>
                        <td>Problem solving</td>
                        <td>3</td>
                        <td>2</td>
                        <td>Chapter 1, Chapter 2, Chapter 3</td>
                        <td>Chapter 4, Chapter 5</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initially, show the Module Progress table
        $('#moduleProgressTable').show();
        $('#chapterwiseProgressTable').hide();

        // Button click events
        $('#overallmoduleBtn').click(function() {
            $('#moduleProgressTable').show();
            $('#chapterwiseProgressTable').hide();
        });

        $('#tableViewBtn').click(function() {
            $('#chapterwiseProgressTable').show();
            $('#moduleProgressTable').hide();
        });
    });
</script>
