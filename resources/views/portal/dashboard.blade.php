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
                    <!-- <li class="breadcrumb-item"><a href="#">Projects</a></li> -->
                    <li class="breadcrumb-item active">Police Record</li>
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
           
            <!-- <select class="form-control d-inline-block col-1 mr-1" id="priorityFilter">
                <option value="">Priority</option>

                <option value="High">High</option>
                <option value="Medium">Medium</option>
            </select> -->

            <!-- <button type="button" class="btn btn-default  mr-1" id="daterange-btn">
                <i class="far fa-calendar-alt"></i>
                <span>Filters</span>
                <i class="fas fa-caret-down"></i>
            </button> -->

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
        <!-- <th> Project </th> -->
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


                   
                    @php
                    $serialNumber = 1;
                    @endphp
                    @foreach($tasks as $task)
                    <tr data-user-id="{{$task->task_creator->id}}">
                    <td>
                            <!-- Add actions/buttons for the new column -->

                          

                            <!-- <a href="{{ route('task.subtask_list', ['id' => $task->id]) }}" class="mr-1 text-blue" title="View"><i class="fa fa-eye"></i> </a> -->
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
        <!-- Role Grant Modal -->
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
        // let isValid  = $("#addTaskForm").attr("data-isValid");
        // //console.log(">>>>>>>>>>>>>", typeof(isValid))
        // if(isValid==1){
        //     //alert(isValid)
        //     $("#addTaskModal").modal("show");
        //     //$("#addTaskModal").css("display","block");

        // }
        // else {
        //     $("#addTaskModal").modal("hide");
        // }

        // let isValid= $("#addTaskBtn").on("click", function(){
            
        //     $("#addTaskForm").submit();
        // });



        // Add click event handler for delete buttons with class 'delete-task'
        $('.delete-task').on('click', function(event) {
            event.preventDefault(); // Prevent the default behavior (e.g., navigating to the delete URL)

            var taskId = $(this).data('task-id');

            // Show a confirmation alert
            if (confirm('Are you sure you want to delete this record?')) {
                // If user clicks 'OK', proceed with the deletion
                window.location.href = "{{ url('/admin/task/delete') }}/" + taskId;
            }
        });

        // Initialize DataTable

        // Initialize DataTable with column-specific options
        var table = $('#taskTable').DataTable({
            columnDefs: [{
                    targets: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                    orderable: false
                } // Set orderable to false for all columns except the 1st column
            ]
        });



    });
</script>



@endsection