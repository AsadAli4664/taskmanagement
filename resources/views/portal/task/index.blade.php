@extends('portal.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Police Record</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Police Record</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card card-info card-outline">
        <div class="card-header d-flex justify-content-end">
            <select class="form-control d-inline-block col-2 mr-1" id="crimeNoFilter">
                <option value="">Select crime_no</option>
                @foreach($tasksd as $task)
                    <option value="{{$task->crime_no}}">{{$task->crime_no}}</option>
                @endforeach
            </select>
            <div class="form-control d-inline-block col-3 mr-1" id="dateRangePicker">
                <i class="fa fa-calendar"></i>&nbsp;
                <span>Select Date Range</span> <i class="fa fa-caret-down"></i>
            </div>
            @if(auth()->user()->hasPermissionTo('add_criminal_record'))
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTaskModal">
                    Enter Record
                </button>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-bordered center-table" id="taskTable">
                <thead style="font-size: 18px;">
                    <tr>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;"> ایکشن </th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;">   کیفیت </th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;"> آفیسر گرفتار کنندہ </th>
                        <th colspan="3" style="text-align: center; vertical-align: middle;">  پوزیشن ملزمان </th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;"> نام و پتہ ملزمان </th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;"> جرم </th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;"> مقدمہ معہ تاریخ </th>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;"> نمبر شمار</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; vertical-align: middle;">  پوزیشن ملزمان </th>
                        <th style="text-align: center; vertical-align: middle;"> جسمانی ریمانڈ  </th>
                        <th style="text-align: center; vertical-align: middle;">  تاریخ  گرفتاری </th>
                    </tr>
                </thead>
                <tbody>
                    @php $serialNumber = 1; @endphp
                    @foreach($tasks as $task)
                        <tr data-user-id="{{$task->task_creator->id}}">
                            <td>
                                @can('edit_criminal_record', $task)
                                    <a href="{{ route('task.edit', ['id' => $task->id]) }}" class="mr-1 text-info" title="Edit"><i class="fa fa-edit"></i> </a>
                                @endcan

                                @can('delete_criminal_record', $task)
                                    <a href="{{ route('task.delete', ['id' => $task->id]) }}" class="mr-1 text-danger delete-task" title="Delete" data-task-id="{{ $task->id }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endcan
                            </td>
                            <td>{{$task->condition}}</td>
                            <td>{{$task->designation}} | {{$task->arrest_by}} </td>
                            <td>{{$task->arrest_status}}</td>
                            <td>{{$task->remand}} {{$task->title}}</td>
                            <td>{{$task->arrest_date}}</td>
                            <td>{{$task->criminal_address}}</td>
                            <td>{{$task->crime_section}}</td>
                            <td>{{$task->crime_no}}</td>
                            <td>{{$serialNumber++}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@section('script')

<script>
    
    $(document).ready(function() {
function fetchTasks() {
    var crimeNo = $('#crimeNoFilter').val();
    var dateRange = $('#dateRangePicker span').html();
    var startDate = null;
    var endDate = null;

    if (dateRange && dateRange !== 'Select Date Range') {
        var dates = dateRange.split(' - ');
        startDate = dates[0];
        endDate = dates[1];
    }

    $.ajax({
        url: "{{ route('search.tasks') }}",
        type: "GET",
        data: { 
            crime_no: crimeNo,
            start_date: startDate,
            end_date: endDate
        },
        success: function(data) {
            $('#taskTable tbody').empty();
            var serialNumber = 1;
            $.each(data, function(index, task) {
                var editUrl = '{{ route("task.edit", ":id") }}'.replace(':id', task.id);
                var deleteUrl = '{{ route("task.delete", ":id") }}'.replace(':id', task.id);

                $('#taskTable tbody').append(
                    `<tr>
                        <td>
                            @can('edit_criminal_record', $task)
                                <a href="` + editUrl + `" class="mr-1 text-info" title="Edit"><i class="fa fa-edit"></i> </a>
                            @endcan

                            @can('delete_criminal_record', $task)
                                <a href="` + deleteUrl + `" class="mr-1 text-danger delete-task" title="Delete" data-task-id="` + task.id + `">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endcan
                        </td>
                        <td>` + task.condition + `</td>
                        <td>` + task.designation + ` | ` + task.arrest_by + `</td>
                        <td>` + task.arrest_status + `</td>
                        <td>` + task.remand + ` ` + task.title + `</td>
                        <td>` + task.arrest_date + `</td>
                        <td>` + task.criminal_address + `</td>
                        <td>` + task.crime_section + `</td>
                        <td>` + task.crime_no + `</td>
                        <td>` + serialNumber++ + `</td>
                    </tr>`
                );
            });

            // Reinitialize DataTables
            $('#taskTable').DataTable();
        },
        error: function() {
            alert('Error fetching tasks. Please try again.');
        }
    });
}


        $('#crimeNoFilter').on('change', fetchTasks);

        $('#dateRangePicker').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: moment().subtract(6, 'days'),
            endDate: moment()
        }, function(start, end) {
            $('#dateRangePicker span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            fetchTasks();
        });

        // Initial fetch
        $('#dateRangePicker span').html(moment().subtract(6, 'days').format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'));
        fetchTasks();
    });
</script>
@endsection

@endsection
