@extends('portal.layout.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Projects</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Projects</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addProjectModal">
                  Add Project
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="table1" width="100%">
                    <thead>
                        <tr>
                            <th> ID </th>
                            <th> Name </th>
                            <th> Location </th>
                            <th> Start Date </th>
                            <th> Due Date </th>
                            <th> Manager </th>
                            <th> category </th>
                            <th> status </th>
                            <th> created_by </th>
                            
                            <th> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
               </table>
           </div>
           <!-- Role Grant Modal -->
           <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalLabel">Add Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('project.store')}}"  method="POST">
                    @csrf
                    <div class="form-group">
                      <label for="Name">Name</label>
                      <input type="text" class="form-control" name="name" id="Name" placeholder="Name">
                    </div>
                    <div class="form-group">
                      <label for="Location">Location</label>
                      <select class="form-control" id="Location" name="location">
                        <option >PSCA LAHORE</option>
                        <option >PPIC3 Kasur</option>
                        <option >PPIC3 Nankana</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="startDate">Start Date</label>
                      <input type="text" class="form-control" name="start_date" id="startDate" placeholder="Start Date">
                    </div>
                    <div class="form-group">
                      <label for="dueDate">Due Date</label>
                      <input type="text" class="form-control" id="dueDate" name="due_date" placeholder="Due Date">
                    </div>
                    <div class="form-group">
                        <label>Manager</label>
                        <select class="form-control" name="manager">
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}} | {{$user->designation}} | {{$user->employee_id}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="category">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            @foreach($status as $status)
                            <option value="{{$status->id}}">{{$status->name}}</option>
                            @endforeach
                        </select>
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
<script type="text/javascript">
    $(function () {

        $('#table1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('project.list')}}",
           responsive:true,
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'location' },
                { data: 'start_date' },
                { data: 'due_date' },
                
                { data: 'manager' },
                { data: 'category' },
                { data: 'status' },
                { data: 'created_by' },
                {
                    targets: -1,
                    data: 'action',
                },
            ],
            createdRow: function (row, data, dataIndex) {
            // Get the due date from the row's data
            var dueDate = moment(data.due_date);

            // Current date
            var today = moment();

            // Calculate the difference in days
            var daysDifference = dueDate.diff(today, 'days');

            // Apply background color based on conditions
            if (dueDate.isSame(today, 'day')) {
                // Due date is today
                $(row).css('background-color', 'brown');
            } else if (dueDate.isBefore(today, 'day')) {
                // Due date is in the past
                $(row).css('background-color', 'red');
            } else if (daysDifference >= 5) {
                // At least 5 days remaining
                $(row).css('background-color', 'green');
            }
        },
            columnDefs: [ {
                'targets': [3], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
                
            }],
            order: [[0, 'desc']],
        });
    });
</script>
<script>
$(function() {
    $('#startDate').daterangepicker({
        singleDatePicker: true,
    });
    $('#dueDate').daterangepicker({
        singleDatePicker: true,
    });
});
</script>

@endsection