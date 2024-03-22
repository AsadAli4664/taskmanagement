@extends('portal.layout.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Edit Your Activity</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('subtask.index')}}">Activity</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-info card-outline">
            {{-- <div class="card-header">
               <h4>General Information</h4>
            </div> --}}
            <div class="card-body">
            <form action="{{route('subtask.update', $subtask->id)}}" method="POST">
                    {{csrf_field()}}
                    <label  class="form-label required">Activity Name</label>
                    <input type="name" class="form-control" id="name" name="name" value="{{old('name') ?? $subtask->name}}" required/>
                    <label for="dueDateTime">Start Time/Date</label>
                    <input type="datetime-local" class="form-control" name="start_datetime" id="start_datetime" value="<?= isset($subtask) ? date('Y-m-d\TH:i', strtotime($subtask->start_datetime)) : '' ?>">
                    <label for="dueDateTime">End Time/Date</label> 
                    <input type="datetime-local" class="form-control" name="end_datetime" id="end_datetime" value="<?= isset($subtask) ? date('Y-m-d\TH:i', strtotime($subtask->end_datetime)) : '' ?>">
                    <label  class="form-label required mt-2">Activity Description </label>     
                    <textarea class="form-control" id="description"  rows="3" name="description" placeholder="Description">{{old('description') ?? $subtask->description}}</textarea>
                    <button type="submit" class="btn btn-primary my-2 float-right">Submit</button>
                </form>
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



