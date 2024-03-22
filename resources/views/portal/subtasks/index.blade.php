@extends('portal.layout.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Activities</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Activity</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card card-info card-outline">

        <!---total duration black box---->
        <div class="row justify-content-center">
            <div class="col-12">
                <h3 class=text-center style="padding-top: 15px;;">Total Duration</h3>
            </div>
            <div class="col-md-8 col-xl-6 col-xxl-5" style="max-width: 33%;">
                <div class="clock-container justify-content-center bg-info">
                    <div class="clock-col">
                        <p class="clock-hours clock-timer">
                        </p>
                        <p class="clock-label">
                            Hours
                        </p>
                    </div>
                    <div class="clock-col">
                        <p class="clock-minutes clock-timer">
                        </p>
                        <p class="clock-label">
                            Minutes
                        </p>
                    </div>
                    <div class="clock-col">
                        <p class="clock-seconds clock-timer">
                        </p>
                        <p class="clock-label">
                            Seconds
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!---end total duration---->


        <div class="card-header d-flex justify-content-end">



            <!-- <label for="userFilter">Filter by User:</label> -->
            <!-- Total Duration div -->

            @if(auth()->user()->hasPermissionTo('view_allactivities'))
            <select class="form-control d-inline-block col-1 mr-1" id="userFilter">
                <option value="">All Users</option>
                @foreach($users as $user)
                <option value="{{ $user->name }} | {{ $user->designation }}">{{ $user->name }} | {{ $user->designation }}</option>
                @endforeach
            </select>
            <!--for admin to show all tasks in filter--->
            <select class="form-control d-inline-block col-1 mr-1" id="taskFilter">
                <option value="">All Tasks</option>
                @foreach($tasks as $task)
                <option value="{{ $task->title }}">{{ $task->title }}</option>
                @endforeach
            </select>
            <!---end---->
            @else
            <!--for specific user to show his task and collaborator task in filter--->
            <select class="form-control d-inline-block col-1 mr-1" id="userFilter">
                <option value="">Users</option>
                @php $displayedNames = []; @endphp

                @foreach($alltask->unique('task_manager.id') as $innerTask)
                    @foreach($innerTask->subtasks as $subtask)
                        @if(!in_array($subtask->user->name, $displayedNames))
                            <option value="{{$subtask->user->name}} | {{$subtask->user->designation}}">
                                {{$subtask->user->name}} | {{$subtask->user->designation}}
                            </option>
                            @php $displayedNames[] = $subtask->user->name; @endphp
                        @endif
                    @endforeach
                @endforeach


            </select>
            <select class="form-control d-inline-block col-1 mr-1" id="taskFilter">
                <option value="">All Tasks</option>
                @foreach($alltask as $task)
                <option value="{{ $task->title }}">{{ $task->title }}</option>
                @endforeach
            </select>
            <!---end---->
            @endif
            <button type="button" class="btn btn-default mr-1" id="daterange-btn">
                <i class="far fa-calendar-alt"></i>
                <span>Filters</span>
                <i class="fas fa-caret-down"></i>
            </button>
            @if(auth()->user()->hasPermissionTo('create_subtask'))
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addActivityModal">
                Add Activity
            </button>
            @endif
            <!-- <label for="userSearch">Search by User:</label>
                    <input type="text" class="form-control" id="userSearch" placeholder="Enter user name"> -->
        </div>

        <!-- Add these input fields within the Add Activity Modal in your view -->


        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" id="subtaskTable">
                    <thead>
                        <tr>
                            <th>Task Title</th>
                            <th>Activity Name</th>
                            <th>Due Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Duration minutes</th>
                            <th>Activity Created At</th>
                            <th>Activity Performed by</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- //It is for Admin -->
                        @if(auth()->user()->hasPermissionTo('view_allactivities'))
                        @php
                        $serialNumber = 1;
                        @endphp
                        @foreach($tasks as $task)
                        @foreach($task->subtasks as $subtask)
                        <tr data-user-id="{{$subtask->sub_task_creator->id}}">
                            <td>{{$task->title}}</td>
                            <td>{{$subtask->name}}</td>
                            <td>{{$task->duedate}}</td>
                            <td>{{$subtask->start_datetime}}</td>
                            <td>{{$subtask->end_datetime}}</td>
                            <td>{{$subtask->duration}}</td>
                            <td>{{$subtask->created_at}}</td>
                            <td>
                                {{$subtask->sub_task_creator->name}} | {{$subtask->sub_task_creator->designation}}
                            </td>
                            <td>
                                <!-- Add actions/buttons for the new column -->
                                <a href="{{ route('task.activity', ['id' => $subtask->id]) }}" class="mr-1 text-blue" title="View"><i class="fa fa-eye"></i> </a> 
                                @can('edit_subtask', $subtask)
                                <a href="{{ route('subtask.edit', ['id' => $subtask->id]) }}" class="mr-1 text-info" title="Edit"><i class="fa fa-edit"></i> </a>
                                @endcan
                                @can('delete_subtask', $subtask)
                                <a href="#" class="mr-1 text-danger delete-subtask" data-subtask-id="{{ $subtask->id }}"> <i class="fa fa-trash"></i> </a>

                                </a>
                                @endcan
                            </td>

                        </tr>

                        @endforeach
                        @endforeach
                        @else
                        @php
                        $serialNumber = 1;
                        @endphp
                        <!-- Additional loop for $alltask  -->
                        @foreach($alltask as $innerTask)
                        @foreach($innerTask->subtasks as $innerSubtask)
                        <tr data-user-id="{{$innerSubtask->sub_task_creator->id}}">
                            <td>{{$innerTask->title}}</td>
                            <td>{{$innerSubtask->name}}</td>
                            <td>{{$innerTask->duedate}}</td>
                            <td>{{$innerSubtask->start_datetime}}</td>
                            <td>{{$innerSubtask->end_datetime}}</td>
                            <td>{{$innerSubtask->duration}}</td>
                            <td>{{$innerSubtask->created_at}}</td>
                            <td>
                                {{$innerSubtask->sub_task_creator->name}} |
                                {{$innerSubtask->sub_task_creator->designation}}

                            </td>
                            <td>
                                <!-- Add actions/buttons for the new column -->
                                <a href="{{ route('task.activity', ['id' => $innerSubtask->id]) }}" class="mr-1 text-blue" title="View"><i class="fa fa-eye"></i> </a> 
                                @can('edit_subtask', $innerSubtask)
                                <!-- Activity creator can edit his activity not moderator activity -->
                                @if(auth()->user()->id==$innerSubtask->sub_task_creator->id)
                                <a href="{{ route('subtask.edit', ['id' => $innerSubtask->id]) }}" class="mr-1 text-info" title="Edit"><i class="fa fa-edit"></i> </a>
                                @endif
                                <!----end----->
                                @endcan
                                @can('delete_subtask', $innerSubtask)
                                <!-- Activity creator can delete his activity not moderator activity -->
                                @if(auth()->user()->id==$innerSubtask->sub_task_creator->id)
                                <a href="#" class="delete-subtask mr-1 text-danger" data-subtask-id="{{ $innerSubtask->id }}"> <i class="fa fa-trash"></i> </a>
                                @endif
                                <!----end----->
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Modal -->
        <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="addActivityModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addActivityModalLabel">Add Activity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('subtask.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="Name">Task *</label>
                                <!---show task which is assigned or collabolator task--->
                                <select name="task" class="form-control" id="taskSelect">
                                    <option value="" disabled selected>Please select task</option>
                                    @foreach($alltask as $task)
                                    <option value="{{$task->id}}">{{$task->title}}</option>
                                    @endforeach
                                </select>
                                <!-----end---->
                            </div>
                            <div class="form-group">
                                <label for="Name">Activity Name *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="Name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="dueDateTime">Start Time/Date *</label>
                                <input type="datetime-local" class="form-control" name="start_datetime" id="start_datetime" value="{{ now()->setTimezone('Asia/Karachi')->format('Y-m-d\TH:i') }}" placeholder="Start Time/Date">
                            </div>
                            <div class="form-group">
                                <label for="dueDateTime">End Time/Date *</label>
                                <input type="datetime-local" class="form-control" name="end_datetime" id="end_datetime" value="{{ now()->setTimezone('Asia/Karachi')->format('Y-m-d\TH:i') }}" placeholder="Start Time/Date">
                            </div>
                            <div class="form-group">
                                <label>Activity Description *</label>
                                <textarea class="form-control" rows="3" name="description" placeholder="Description">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Task Progress *</label>
                                <div class="slider-red">
                                    <input id="progressSliderModal" type="text" value="0" name="progress" class="slider form-control" data-slider-min="0" data-slider-max="100" data-slider-step="5" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show">
                                </div>
                            </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <div class="d-flex justify-content-center">
            </div>
        </div>
    </div>
    <!-- /.card -->

</section>

@endsection

@section('script')
<!-- Ensure to include necessary libraries for DataTables, Date Picker, and Slider -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {

        // Add click event handler for delete buttons with class 'delete-subtask'
        $('#subtaskTable').on('click', '.delete-subtask', function(event) {
            event.preventDefault(); // Prevent the default behavior (e.g., navigating to the delete URL)

            var subtaskId = $(this).data('subtask-id');

            // Show a confirmation alert
            if (confirm('Are you sure you want to delete this task?')) {
                // If the user clicks 'OK', proceed with the deletion
                window.location.href = "{{ url('/admin/subtask/delete') }}/" + subtaskId;
            }
        });

        // Calculate and update the sum of the "Duration" column
        function updateTotalDuration() {
            var totalDurationMinutes = 0;

            $('#subtaskTable tbody tr').each(function() {
                var duration = parseFloat($(this).find('td').eq(5).text()) || 0; // Assuming "Duration" is in the 7th column
                totalDurationMinutes += duration;
            });

            var totalDurationHours = Math.floor(totalDurationMinutes / 60);
            var remainingMinutes = totalDurationMinutes % 60;
            var seconds = 0;

            // Update the total duration in the new div
            document.documentElement.style.setProperty('--timer-hours', `"${totalDurationHours}"`);
            document.documentElement.style.setProperty('--timer-minutes', `"${remainingMinutes}"`);
            document.documentElement.style.setProperty('--timer-seconds', `"${seconds}"`);

        }

        // Initialize DataTables
        var table = $('#subtaskTable').DataTable({

            "columnDefs": [{
                "targets": -1, // Targets the last column (Actions)
              //  "orderable": false, // Make the column not sortable
                "render": function(data, type, row) {
                    // Customize the content of the Actions column
                    var subtaskId = row[0]; // Assuming the subtask ID is in the first column
                    return '<a href="{{ route("subtask.edit", ["id" => "/"]) }}/' + subtaskId + '">' + data + '</a>';
                    // Add more buttons or HTML content as needed
                }
            }],
            "order": [
                [6, 'desc'] // Set the default order of the first column (change [0, 'asc'] to the desired column index)
            ]
        });
        // Initial update
        updateTotalDuration();

        function loadProgressForFirstTask() {
            // Fetch progress value for the first task from the server
            var firstTaskId = $('#taskSelect option:first').val();

            $.ajax({
                url: '{{ route("subtask.getTaskProgress") }}',
                method: 'GET',
                data: {
                    taskId: firstTaskId
                },
                success: function(response) {
                    // Update the progress slider with the fetched progress value
                    $('#progressSliderModal').slider('setValue', response.progress);
                },
                error: function(error) {
                    console.error('Error fetching task progress:', error);
                }
            });
        }

        var currentProgress = 0; // Initialize with the default value

        // Event listener for task select dropdown
        $('#taskSelect').on('change', function() {
            var taskId = $(this).val();

            // Fetch progress value based on the selected task from the server
            $.ajax({
                url: '{{ route("subtask.getTaskProgress") }}',
                method: 'GET',
                data: {
                    taskId: taskId
                },
                success: function(response) {
                    // Store the current progress value
                    currentProgress = response.progress;

                    // Update the progress slider with the fetched progress value
                    $('#progressSliderModal').slider('setValue', currentProgress);
                },
                error: function(error) {
                    console.error('Error fetching task progress:', error);
                }
            });
        });

        // Event listener for progress slider
        $('#progressSliderModal').on('slideStop', function(slideEvt) {
            var newProgress = slideEvt.value;

            // Check if the new progress is greater than or equal to the current progress
            if (newProgress >= currentProgress) {
                // Update the current progress value
                newProgress = newProgress;
            } else {
                // If the new progress is less than the current progress, revert to the current progress
                $('#progressSliderModal').slider('setValue', currentProgress);
            }
        });

        // Add this part to trigger loading progress for the first task when the modal is shown
        $('#addActivityModal').on('shown.bs.modal', function(e) {
            loadProgressForFirstTask();
        });

        // User filter change event handler
        $('#userFilter').on('change', function() {
            var selectedUserId = $(this).val();
            // alert(table.columns(8));

            // Show all rows if no user is selected
            if (selectedUserId === '') {
                table.columns(7).search('').draw();
            } else {
                // Filter the table based on the selected user's ID
                table.columns(7).search(selectedUserId).draw();
            }
            // Update total duration
            updateTotalDuration();
        });

        // Task filter change event handler
        $('#taskFilter').on('change', function() {
            var selectedUserId = $(this).val();

            // Show all rows if no user is selected
            if (selectedUserId === '') {
                table.columns(0).search('').draw();
            } else {
                // Filter the table based on the selected user's ID
                table.columns(0).search(selectedUserId).draw();
            }
            // Update total duration
            updateTotalDuration();
        });

        // DataTables date range filtering function
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex, startDatetime, endDatetime) {
                startDatetime = $('#daterange-btn').data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss');
                endDatetime = $('#daterange-btn').data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss');
                var activityCreatedAt = data[3]; // Assuming Activity Created At is in the 8th column

                if ((startDatetime === '' && endDatetime === '') ||
                    (startDatetime === '' && activityCreatedAt <= endDatetime) ||
                    (startDatetime <= activityCreatedAt && endDatetime === '') ||
                    (startDatetime <= activityCreatedAt && activityCreatedAt <= endDatetime)) {
                    return true;
                }
                return false;
            }
        );

        // Initialize date pickers
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment().format('DD-MM-YYYY'), moment()],
                    'Yesterday': [moment().subtract(1, 'days').format('DD-MM-YYYY'), moment().subtract(1, 'days').endOf('day')],
                    'Last 7 Days': [moment().subtract(6, 'days').format('DD-MM-YYYY'), moment().endOf('day')],
                    'Last 30 Days': [moment().subtract(29, 'days').format('DD-MM-YYYY'), moment().endOf('day')],
                    'This Month': [moment().startOf('month').format('DD-MM-YYYY'), moment()],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'All the Time': [moment('01-01-2024').format('DD-MM-YYYY'), moment()]
                },
                startDate: moment().subtract(1, 'years').startOf('year'),
                endDate: moment(),
                locale: {
                    format: 'DD-MM-YYYY hh:mm A'
                },
                showDropdowns: true,
                timePicker: true,
            },
            function(start, end) {
                $("#daterange-btn span").html(start.format('DD-MM-YYYY hh:mm:ss A') + " to " + end.format('DD-MM-YYYY hh:mm:ss A'))
                table.draw();
                // Update total duration
                updateTotalDuration();
            });

        // Initialize progress slider for the modal
        $('#progressSliderModal').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });
    });
</script>


@endsection