@extends('portal.layout.app')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>InCompleted Tasks</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <!-- <li class="breadcrumb-item"><a href="#">Projects</a></li> -->
                    <li class="breadcrumb-item active">Tasks</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->

    <div class="card card-info card-outline">

        <div class="card-header d-flex justify-content-end">
            <!-- <label for="userFilter">Filter by User:</label> -->
       
            <select class="form-control d-inline-block col-1 mr-1" id="priorityFilter">
                <option value="">Priority</option>
                
                <option value="High">High</option>
                <option value="Medium">Medium</option>
            </select>
           
            <button style="display: none;" type="button" class="btn btn-default  mr-1" id="daterange-btn" >
                <i class="far fa-calendar-alt"></i>
                <span >Filters</span>
                <i class="fas fa-caret-down"></i>
            </button>

       


        </div>
        <div class="card-body">
            <table class="table table-bordered" id="taskTable">
                <thead>
                    <tr>
                        <th> Sr. </th>
                        <th> Title </th>
                        <th> Due Date </th>
                        <!-- <th> Description </th> -->
                        <th> Priority </th>
                        <!-- <th> Project </th> -->
                        <th> Progress </th>
                        <th> Created At </th>
                        <th> Assigned to </th>
                        <th> Assigned by </th>
                    </tr>
                </thead>
                <tbody>
                   

                    @if(auth()->user()->hasPermissionTo('view_alltasks'))
                    @php
                        $serialNumber = 1;
                    @endphp
                    @foreach($tasks as $task)
                        @if($task->progress < 100)
                    <tr data-user-id="{{$task->task_creator->id}}">
                        <td>{{$serialNumber++}}</td>
                        <td> <a href="{{ route('task.subtask_list', ['id' => $task->id]) }}"> {{$task->title}} </a></td>
                        <td>{{$task->duedate}}</td>
                        <!-- <td>{{$task->description}}</td> -->
                        <td>{{$task->priority}}</td>
                        <td>{{$task->progress}}</td>
                        <td>{{$task->created_at}}</td>
                        <td>{{$task->task_manager->name}}</td>
                        <td>
                            {{$task->task_creator->name}} | {{$task->task_creator->designation}} 
                        </td>
                        
                    </tr>
                    @endif
                    @endforeach
                    @else
                    @php
                        $serialNumber = 1;
                    @endphp
                    @foreach($usertask as $task)
                         @if($task->progress < 100)

                    <tr data-user-id="{{$task->task_creator->id}}">
                        <td>{{$serialNumber++}}</td>
                        <td> <a href="{{ route('task.subtask_list', ['id' => $task->id]) }}"> {{$task->title}} </a></td>
                        <td>{{$task->duedate}}</td>
                        <td>{{$task->description}}</td>
                        <td>{{$task->priority}}</td>
                        <td>{{$task->progress}}</td>
                        <td>{{$task->created_at}}</td>
                        <td>{{$task->task_manager->name}}</td>
                        <td>
                            {{$task->task_creator->name}} | {{$task->task_creator->designation}} 
                        </td>
                        
                    </tr>
                    @endif

                    @endforeach
                    @endif

                </tbody>
            </table>
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



        // Initialize DataTable
        var table = $('#taskTable').DataTable();

        // Apply filter when user selects a value from the userFilter dropdown
        $('#userFilter').on('change', function() {
            var filterValue = $(this).val();
            table.column(7).search(filterValue).draw();
        });

        // DataTables date range filtering function
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex, startDatetime, endDatetime) {
                startDatetime = $('#daterange-btn').data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss');
                endDatetime = $('#daterange-btn').data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss');
                var activityCreatedAt = data[6]; // Assuming Activity Created At is in the 8th column

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
                    'All the Time': [moment().startOf('year'), moment()]
                },
                startDate: moment().startOf('year'),
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
            });
        // Apply filter when user selects a value from the taskFilter dropdown
      
        $('#priorityFilter').on('change', function() {
            var filterValue = $(this).val();
            table.column(4).search(filterValue).draw();
        });
    });
</script>



@endsection