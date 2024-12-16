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
        /* align-items: center; */
        margin-left: 50px;
    }

    .chartContainer {
        margin-top: 15px;
        width: 500px;
    }

    .tableContainer {
        margin-top: 30px;
        width: 1000px;
        margin-left: 50px;
        display: none; /* Hidden by default */
    }

    .panel-default {
        margin-top: 30px;
        width: 900px;
        margin-left: 200px;
        text-align: center;
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
                <button class="btn btn-primary" id="mentorViewBtn">Modulewise Completion report</button>
                <button class="btn btn-Danger" id="moduleViewBtn">Modulewise Progress Report</button> 
                <!-- <button class="btn btn-warning" id="mentorwiseBtn">Mentorwise Session Report</button> -->


            </div>

            <!-- Export Button - visible for all tables -->
            <div class="export-button-container">
                <form action="#" method="get">
                    @csrf
                    <button type="submit" class="btn btn-success">Export to Excel</button>
                </form>
            </div>
        </div>

    

        <!-- Table with "View" Button -->
        <div class="tableContainer" id="mentorOverviewTable">
    <h3>Modulewise Completion report</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Serial No.</th>
                <th>Mentee Name</th>
                <th>Mentor Name</th>
                <th>Module Name</th>
                <th>Completion Date</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>1</td>
                <td>Rahul</td>
                <td>Ranjini</td>
                <td>Problem Solving</td>
                <td>2024-12-8</td>
            </tr>
           {{-- @foreach($mentorOverviewData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td> <!-- Serial Number -->
                    <td>{{ $data->mentee_name }}</td> <!-- Mentee Name -->
                    <td>{{ $data->mentor_name }}</td> <!-- Mentor Name -->
                    <td>
                        <button class="btn btn-info view-details" data-mentee="{{ $data->mentee_name }}" data-mentor="{{ $data->mentor_name }}" data-index="{{ $index }}" data-id="{{ $data->id }}">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
            @endforeach--}}
        </tbody>
    </table>
</div>



        <!-- Module overview Table -->
        <div class="panel panel-default" id="moduleProgressContainer" style="display: none;">
            <div class="panel-heading">
                <h4>Modulewise Progress</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Module</th>
                            <th>Total Mentee Completed</th>
                            <th>Total Mentee Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-toggle="collapse" data-target=".demo1-1" class="accordion-toggle">
                            <td><button class="btn btn-default btn-xs" onclick="toggleCollapse('.demo1-1')"><span><i class="fa-solid fa-eye"></i></span></button></td>
                            <td>Module 1</td>
                            <td>5</td>
                            <td>0</td>
                            
                        </tr>
                        <tr>
                            <td colspan="5" class="hiddenRow">
                                <div class="accordian-body collapse demo1-1">
                                    <table class="table table-striped">
                                        <thead class="thead-light">
                                            <tr class="info">
                                                <th>Chapter</th>
                                            
                                                
                                                <th>Total Mentee Completed</th>
                                                <th>Total Mentee Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>chapter 1</td>
                                                
                                                <td>10</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 2</td>
                                                
                                                <td>9</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 3</td>
                                                
                                                <td>8</td>
                                                <td>2</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 4</td>
                                                
                                                <td>9</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 5</td>
                
                                                <td>7</td>
                                                <td>3</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr data-toggle="collapse" data-target=".demo1-2" class="accordion-toggle">
                            <td><button class="btn btn-default btn-xs" onclick="toggleCollapse('.demo1-2')"><span><i class="fa-solid fa-eye"></i></span></button></td>
                            <td>Module 2</td>
                            <td>4</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="hiddenRow">
                                <div class="accordian-body collapse demo1-2">
                                    <table class="table table-striped">
                                        <thead class="thead-light">
                                            <tr class="info">
                                                <th>Chapter</th>
                                                <th>Total Mentee Completed</th>
                                                <th>Total Mentee Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>chapter 1</td>
                                                
                                                <td>8</td>
                                                <td>2</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 2</td>
                                                
                                                <td>7</td>
                                                <td>3</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 3</td>
                                                
                                                <td>9</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 4</td>
                                                
                                                <td>6</td>
                                                <td>4</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 5</td>
                                                
                                                <td>0</td>
                                                <td>10</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mentorwise Session Report -->
    </div>
</div>

@endsection

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery version -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Initially, show the Module Progress table
        $('#moduleProgressTable').show();
        $('#chapterwiseProgressTable').hide();
        $('#mentorOverviewTable').hide();
        $('#studentDetailsModal').hide();
        $('#mentorwisesession').hide();




        // Button click events
        $('#overallmoduleBtn').click(function() {
            // Show Module Progress Table
            $('#moduleProgressTable').show();
            $('#chapterwiseProgressTable').hide();
            $('#mentorOverviewTable').hide();
            $('#studentDetailsModal').hide();
            $('#mentorwisesession').hide();


        });

        $('#tableViewBtn').click(function() {
            // Show Chapterwise Progress Table
            $('#chapterwiseProgressTable').show();
            $('#moduleProgressTable').hide();
            $('#mentorOverviewTable').hide();
            $('#moduleProgressContainer').hide();
            $('#mentorwisesession').hide();


        });

        $('#mentorViewBtn').click(function() {
            // Show Mentor Overview Table
            $('#mentorOverviewTable').show();
            $('#moduleProgressTable').hide();
            $('#chapterwiseProgressTable').hide();
            $('#moduleProgressContainer').hide();
            $('#mentorwisesession').hide();


        });

        $('#moduleViewBtn').click(function() {
            // Show Mentor Overview Table
            $('#moduleProgressContainer').show();
            $('#moduleProgressTable').hide();
            $('#chapterwiseProgressTable').hide();
            $('#mentorOverviewTable').hide();
            $('#mentorwisesession').hide();

        });
        $('#mentorwiseBtn').click(function() {
            // Show Mentor Overview Table
            $('#mentorwisesession').show();
            $('#moduleProgressContainer').hide();
            $('#moduleProgressTable').hide();
            $('#chapterwiseProgressTable').hide();
            $('#mentorOverviewTable').hide();
        });
       
    });

    // // Wait for the DOM to be ready
    // $(document).ready(function() {
    //     // Add click event to the "view" buttons
    //     $('.view-details').on('click', function() {
    //         // Get data from the button
    //         var menteeName = $(this).data('mentee');
    //         var mentorName = $(this).data('mentor');
    //         var index = $(this).data('index');
    //         var id = $(this).data('id');

    //         // Populate the modal with the fetched data
    //         $('#studentName').text(menteeName);
    //         $('#assignedMentor').text(mentorName);
    //         // Optionally, you can fetch other data like tasks, quizzes, etc., if necessary
    //         // For now, we just populate basic details
    //         $('#pendingTasks').text('Data pending for ' + menteeName);  // This can be dynamic based on your needs
    //         $('#completedTasks').text('Completed tasks for ' + menteeName); // Update this accordingly

    //         // Show the modal
    //         $('#studentDetailsModal').modal('show');
    //     });
    // });


</script>
