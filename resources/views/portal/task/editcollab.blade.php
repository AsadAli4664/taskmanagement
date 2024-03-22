@extends('portal.layout.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Collaborartor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('task.index')}}">Tasks</a></li>
                    <li class="breadcrumb-item active">Add Collaborartor</li>
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
            <form action="{{route('task.updatecollaborator', $task->id)}}" method="POST">
                {{csrf_field()}}
                <label class="form-label required">Task Name</label>
                <input type="text" class="form-control" id="name" name="title" value="{{old('name') ?? $task->title}}" required disabled />
                <label for="dueDateTime mt-2">Due Date</label>
                <input type="datetime-local" class="form-control" name="duedate" id="duedate" disabled value="<?= isset($task) ? date('Y-m-d\TH:i', strtotime($task->duedate)) : '' ?>">
                <label class="form-label required mt-2">Description </label>
                <textarea class="form-control" rows="3" name="description" disabled placeholder="Description">{{old('description') ?? $task->description}}</textarea>
                <div class="form-group mt-2">
                    <label>Priority</label>
                    <select name="priority" class="form-control" disabled>
                        <option value="{{old('priority') ?? $task->priority}}">{{old('priority') ?? $task->priority}}</option>
                        
                    </select>
                </div>
                <div class="form-group mt-2">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="collaboratorDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Collaborators
                        </button>
                        <div class="dropdown-menu" aria-labelledby="collaboratorDropdown">
                            @foreach(json_decode($taskAssignment->collaborators) ?? [] as $collaboratorId)
                            @php
                            $collaboratorUser = \App\Models\User::find($collaboratorId);
                            @endphp
                            <div class="dropdown-item">
                                <input type="checkbox" name="collaborators[]" value="{{$collaboratorUser->id}}" id="collaborator{{$collaboratorUser->id}}" checked disabled>
                                <label for="collaborator{{$collaboratorUser->id}}">{{$collaboratorUser->name}} | {{$collaboratorUser->designation}} | {{$collaboratorUser->employee_id}}</label>
                            </div>
                            @endforeach

                            @foreach($userassign as $userassigned)
                            <div class="dropdown-item">
                                <input type="checkbox" name="collaborators[]" value="{{$userassigned->id}}" id="collaborator{{$userassigned->id}}">
                                <label for="collaborator{{$userassigned->id}}">{{$userassigned->name}} | {{$userassigned->designation}} | {{$userassigned->employee_id}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                








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