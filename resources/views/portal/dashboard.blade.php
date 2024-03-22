@extends('portal.layout.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
                       

            </div><!-- /.col -->
             
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        @if(auth()->user()->hasPermissionTo('view_alldashboardstats'))
                        <h3>{{ \App\Models\Task::count() }}</h3>
                        @else
                        <h3>{{ $alltaskstats->count() }}</h3>
                        @endif
                       
                        <p>All Tasks</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{route('task.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <!-- ./col -->
             <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                    @if(auth()->user()->hasPermissionTo('view_alldashboardstats'))
                        <h3>{{ $alltask->count() }}</h3>
                        @else
                        <h3>{{ $alltask->count() }}</h3>
                        @endif
                        <p>Assigned Tasks to You</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cube"></i>
                    </div>
                    @if($alltask->count()==0)
                    <span class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></span>
                    @else
                    <a href="{{route('task.assignedtask')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    @endif
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        @if(auth()->user()->hasPermissionTo('view_alldashboardstats'))
                        <h3>{{ \App\Models\Task::where('progress', 100)->count() }}</h3>
                        @else
                        <h3>{{ $alltask->where('progress', 100)->count() }}</h3>
                        @endif
                        <p>Completed Tasks</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('task.completed')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
           
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        @if(auth()->user()->hasPermissionTo('view_alldashboardstats'))
                        <h3>{{ \App\Models\Task::where('progress', '<', 100)->count() }}</h3>
                        @else
                        <h3>{{ $alltask->where('progress', '<', 100)->count() }}</h3>
                        @endif
                        <p>In Progress Tasks</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="{{route('task.incompleted')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex">
                        <h3 class="card-title nowrap">
                            <i class="far fa-chart-bar"></i>
                           
                            Tasks Progress &nbsp;<small id="chart-1-display-dates" style="color: red;"></small>
                       
                        </h3>
                        <div class="input-group justify-content-end">
                            <button id="chart-1-spinner" class="btn btn-info mr-1" style="display: none;">
                                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-default float-right" id="chart-1-daterange-btn">
                                <i class="far fa-calendar-alt"></i> Date Range
                                <i class="fas fa-caret-down"></i>
                            </button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <!-- <canvas id="bar-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                            <div id="projectsChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

    </div><!-- /.container-fluid -->

    <div class="card card-info card-outline">
         <!---total duration black box---->
         <div class="row justify-content-center">
            <div class="col-12">
                <h3 class=text-center style="padding-top: 15px;;">Total Duration</h3>
            </div>
            <div class="col-md-8 col-xl-6 col-xxl-5" style="max-width: 33%;">
                <div class="clock-container justify-content-center bg-info">
                    <!-- <div class="clock-col">
                            <p class="clock-day clock-timer">
                            </p>
                            <p class="clock-label">
                            Days
                            </p>
                        </div> -->
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
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h4>All Activities</h4>
                </div>

                <div class="col-6 text-right">

                    <!-- <label for="userFilter">Filter by User:</label> -->
                    @if(auth()->user()->hasPermissionTo('view_allactivities'))
            <select class="form-control d-inline-block col-2 mr-1" id="userFilter">
                <option value="">All Users</option>
                @foreach($users as $user)
                <option value="{{ $user->name }} | {{ $user->designation }} ">{{ $user->name }} | {{ $user->designation }} </option>
                @endforeach
            </select>
            <!--for admin to show all tasks in filter--->
            <select class="form-control d-inline-block col-2 mr-1" id="taskFilter">
                <option value="">All Tasks</option>
                @foreach($tasks as $task)
                <option value="{{ $task->title }}">{{ $task->title }}</option>
                @endforeach
            </select>
            <!---end---->
            @else
            <!--for specific user to show his task and collaborator task in filter--->
            <select class="form-control d-inline-block col-2 mr-1" id="userFilter">
                <option value="">Users</option>
                @foreach($alltask->unique('task_manager.id') as $innerTask)
                <option value="{{$innerTask->task_manager->name}} | {{$innerTask->task_manager->designation}} | {{$innerTask->task_manager->employee_id}}">
                    {{$innerTask->task_manager->name}} | {{$innerTask->task_manager->designation}} | {{$innerTask->task_manager->employee_id}}
                </option>
                @endforeach
            </select>
            <select class="form-control d-inline-block col-2 mr-1" id="taskFilter">
                <option value="">All Tasks</option>
                @foreach($alltask as $task)
                <option value="{{ $task->title }}">{{ $task->title }}</option>
                @endforeach
            </select>
            <!---end---->
            @endif
           
                    <button type="button" class="btn btn-default" id="daterange-btn">
                        <i class="far fa-calendar-alt"></i>
                        <span>Filters</span>
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <!-- <label for="userSearch">Search by User:</label>
                    <input type="text" class="form-control" id="userSearch" placeholder="Enter user name"> -->
                </div>
            </div>
            <!-- Add these input fields within the Add Activity Modal in your view -->
        </div>
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
                            <th>Duration<small>(MINUTES)</small></th>
                            <th>Activity Created At</th>
                            <th>Activity Performed by</th>
                          
                        </tr>
                    </thead>
                    <tbody>

                        <!-- //It is for Admin -->
                        @if(auth()->user()->hasPermissionTo('view_allactivities'))
                        @php
                        $serialNumber = count(collect($tasks)->flatMap(function ($task) {
                        return $task->subtasks;
                        }));
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
                          

                        </tr>

                        @endforeach
                        @endforeach
                        @else
                        @php
                        $serialNumber = count(collect($alltask)->flatMap(function ($task) {
                        return $task->subtasks;
                        }));
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
                                {{$innerSubtask->sub_task_creator->designation}} |
                            </td>
                          
                        </tr>
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add this modal at the end of your Blade view -->
    


</section>
<!-- /.content -->
@endsection
@section('script')
<!-- Ensure to include necessary libraries for DataTables, Date Picker, and Slider -->
<script src="{{ url('/portal') }}/plugins/chart.js/Chart.min.js"></script>
<!-- Added for Notification -->
<script>
    // Add a click event listener to the notification icon
    $(document).ready(function() {
        $('#notificationIcon').click(function() {
            // Open the notification modal
            $('#notificationModal').modal('show');
        });
    });
</script>
<!-- Added for Notification -->



<script>
    //-------------
    //- BAR CHART -
    //-------------
    Highcharts.chart('projectsChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: '',
            align: 'left'
        },
        subtitle: {
            text: '',
            align: 'left'
        },
        xAxis: {
            type: 'category',
            title: {
                text: null
            },
            gridLineWidth: 1,
            lineWidth: 0,
            labels: {
                rotation: 0,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            },
            gridLineWidth: 0
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<b>{point.name}: {point.y:,.0f}%</b>',
        },
        plotOptions: {
            bar: {
                borderRadius: '50%',
                dataLabels: {
                    enabled: true
                },
                groupPadding: 0.1
            }
        },
        legend: {
            enabled: false,
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Projects',
            colorByPoint: true,
            data: [
                <?php
                if (auth()->user()->hasPermissionTo('view_alldashboardstats')) {
                    foreach ($taskschartlimit as $task) { 
                        ?> {
                            name: '{{$task->title}}',
                            y: {{$task->progress}},
                            url: '{{route('task.subtask_list', ['id' => $task->id])}}', // Add the URL you want to link to
                        },
                    <?php 
                    }
                } else {
                    foreach ($chartsforspecificuser as $task) { 
                        ?> {
                            name: '{{$task->title}}',
                            y: {{$task->progress}},
                            url: '{{route('task.subtask_list', ['id' => $task->id])}}', // Add the URL you want to link to
                        },
                <?php
                 }
                }
                ?>
            ],
            point: {
                events: {
                    click: function () {
                        // Redirect to the URL when a bar is clicked
                        window.location.href = this.url;
                    }
                }
            }
        }]
    });
</script>
<script>
    $(document).ready(function() {


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
                "orderable": false, // Make the column not sortable
                "render": function(data, type, row) {
                    // Customize the content of the Actions column
                    var subtaskId = row[0]; // Assuming the subtask ID is in the first column
                    return data;
                    // Add more buttons or HTML content as needed
                }
            }],
            "order": [
                [6, 'desc'] // Set the default order of the first column (change [0, 'asc'] to the desired column index)
            ]
        });
        // Initial update
        updateTotalDuration();



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

        // Slider
        $('#progressSlider').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });
    });
</script>
@endsection