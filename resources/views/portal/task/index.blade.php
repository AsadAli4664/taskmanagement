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
        <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Enter Record</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('task.store')}}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="crime_no" name="crime_no" dir="rtl" placeholder="00/00" {{ old('crime_no') }} required>
                            </div>
                            <label for="crime_no" class="col-sm-3 col-form-label required">مقدمہ معہ تاریخ</label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="crime_section" name="crime_section" placeholder="379 PPC" dir="rtl" required>
                            </div>
                            <label for="crime_section" class="col-sm-3 col-form-label required">جرم</label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <textarea class="form-control @error('criminal_address') is-invalid @enderror" dir="rtl" rows="3" name="criminal_address" id="txtBox2" placeholder="نام و پتہ ملزمان" required>{{ old('criminal_address') }}</textarea>
                            </div>
                            <label for="criminal_address" class="col-sm-3 col-form-label required">  نام و پتہ ملزمان</label>
                        </div>
                  
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="date" class="form-control" id="arrest_date"  name="arrest_date" dir="rtl" placeholder="date" required>
                            </div>
                            <label for="arrest_date" class="col-sm-3 col-form-label required">تاریخ گرفتاری  </label>
                        </div>
                        <div class="form-group row">
                        <div class="col-sm-5">
                            <select name="days" dir="rtl" class="form-control" required>
                              
                                <option value="دن">دن</option>
                                <option value="یوم">یوم</option>
                                <option value="ہفتہ">ہفتہ</option>
                                <option value="مہینہ">مہینہ</option>
                                <option value="سال">سال</option>
                               
                                </select>
                            </div>
                            <div class="col-sm-4">
                            <input type="text" class="form-control" id="remand" name="remand" placeholder="2" dir="rtl" required>
                            </div>
                          
                            <label for="remand" class="col-sm-3 col-form-label required">جسمانی ریمانڈ  </label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="arrest_status" name="arrest_status" placeholder="گرفتار" dir="rtl" required>
                            </div>
                            <label for="arrest_status" class="col-sm-3 col-form-label required">پوزیشن ملزمان</label>
                        </div>
                        <div class="form-group row">
                        <div class="col-sm-4">
                          
                            <select name="designation" class="form-control" required>

                                    <option value="">عہدہ</option>
                                    <option value="ASI">ASI</option>
                                    <option value="TASI">TASI</option>
                                    <option value="SI">SI</option>
                                    <option value="TSI">TSI</option>
                                    <option value="Inspector">Inspector</option>
                                    <option value="DSP">DSP</option>
                               
                                </select>
                            </div>
                            <div class="col-sm-5">
                            <input type="text" class="form-control " id="arrest_by" name="arrest_by" placeholder="آفیسر کا نام" dir="rtl" required>
                            </div>
                           
                            <label for="arrest_by" class="col-sm-3 col-form-label required">آفیسر گرفتار کنندہ </label>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="condition" name="condition" placeholder="کیفیت" dir="rtl" required>
                            </div>
                            <label for="condition" class="col-sm-3 col-form-label required"> کیفیت</label>
                        </div>
                     
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Add </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@section('script')

<script>

    window.onload = function() {
        MakeTextBoxUrduEnabled(document.getElementById('txtBox2'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('arrest_status'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('arrest_by'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('condition'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('inputBox'));//enable Urdu in input field
    };

</script>

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
