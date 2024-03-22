@extends('portal.layout.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h4><b>

            <h4><b>Task Detail</b></h4>

          </b></h4>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('task.index')}}">Tasks</a></li>
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
    @foreach($tasks as $task)
    <div class="card-header">
      <div class="row">
        <div class="col-8">
          <div class="d-flex flex-column">
            <h3 class="card-title">
              <i class="fas fa-chart-pie mr-1"></i>
              <b>{{$task->title}}</b>
            </h3>
            <div class="text-muted">
    <span> Assigned by: <b> {{$task->task_creator->name ?? ''}} </b>| </span>
    <span>Assigned to: <b> {{$task->task_manager->name ?? ''}} </b> | </span>
    <span class="time"><i class="fas fa-clock"></i> Due Date: <b> {{!empty($task->duedate) ? $task->duedate : ''}} </b></span> <br>
    <?php $assignedtasks = json_decode($task->task_assign->collaborators); ?>
    @if(is_array($assignedtasks))
        <span>Collaborators: 
            @foreach($assignedtasks as $collaboratorId)
                <?php $collaboratorName = \App\Models\User::find($collaboratorId)->name; ?>
                <b>{{$collaboratorName ? $collaboratorName : ''}}</b>
                @if(!$loop->last), @endif
            @endforeach
        </span> 
    @endif
</div>


            <!-- <div class="my-2">
                            <div class="progress" style="width: 180px;">
                                <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$task->progress}}%">
                                    <span class="sr-only"> {{$task->progress}}</span> {{$task->progress}}%
                                </div>
                            </div>
                        </div> -->
            <div class="pt-2">
              {{$task->description}}
            </div>

          </div>
        </div>
        <div class="col-4 text-center">
          <input type="text" class="knob" value="{{$task->progress}}" data-thickness="0.3" data-width="100" data-height="100" data-fgColor="" data-angleOffset="0" data-linecap="round" data-readOnly="true">

          <div class="knob-label">Progress</div>
        </div>
      </div>
    </div>
    @endforeach
    <!-- <div class="card-header">
            <div class="row">
                <div class="col-6">
                @foreach($tasks as $task)
                    <h5>Task Assigned By:"{{$task->task_creator->name}}"</h5>
                    <h5>Task Assigned To:"{{$task->task_manager->name}}"</h5>
                    <h5>Task Due Date:"{{$task->duedate}}"</h5>
                    <h5>Task Description:"{{$task->description}}"</h5>
                @endforeach
                </div>
                <div class="col-6">
                    <span><b> Task Progress: </b></span>
                    <div class="progress" style="width: 180px;">
                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$task->progress}}%">
                            <span class="sr-only"> {{$task->progress}}</span> {{$task->progress}}%
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <!-- The time line -->
          <div class="timeline">
            <!-- timeline time label -->
            @foreach($tasks as $task)
            @php
            $serialNumber = 1
            @endphp
            @foreach($task->subtasks as $subtask)
            <div class="time-label">
              <span class="bg-red">{{$subtask->created_at}}</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->

            <div>
              <span class="counter bg-blue"> {{$serialNumber}}</span>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-user"></i> {{$subtask->sub_task_creator->name}} | {{$subtask->sub_task_creator->designation}}</span>
                <h3 class="timeline-header"><a href="#">{{$subtask->name}}</a> </h3>

                <div class="timeline-body">
                  {{$subtask->description}}
                </div>
              </div>
            </div>
            @php
            $serialNumber++
            @endphp
            @endforeach
            @endforeach
            <div>
              @foreach($tasks as $task)
              @if($task->progress != 0)
              <i class="fas fa-clock bg-gray"></i>
              @else
              <h3 class="card-title">
              <i class="fas fa-edit mr-1"></i>
              <b style="color:red">No activity Performed yet!</b>
            </h3>
              @endif
              @endforeach

            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </div>
    <!-- /.card -->
  </div>
</section>
@endsection



@section('script')
<script>
  // Date Picker
  $(function() {
    $('#dueDate').daterangepicker({
      singleDatePicker: true,
    });
  });

  // Slider
  $('#progressSlider').slider({
    formatter: function(value) {
      return 'Current value: ' + value;
    }
  });
  $(function() {
    $('.knob').knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function() {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv) // Angle
            ,
            sa = this.startAngle // Previous start angle
            ,
            sat = this.startAngle // Start angle
            ,
            ea // Previous end angle
            ,
            eat = sat + a // End angle
            ,
            r = true

          this.g.lineWidth = this.lineWidth

          this.o.cursor &&
            (sat = eat - 0.3) &&
            (eat = eat + 0.3)

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value)
            this.o.cursor &&
              (sa = ea - 0.3) &&
              (ea = ea + 0.3)
            this.g.beginPath()
            this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke()
          }

          this.g.beginPath()
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
          this.g.stroke()

          this.g.lineWidth = 2
          this.g.beginPath()
          this.g.strokeStyle = this.o.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
          this.g.stroke()

          return false
        }
      }
    })
    /* END JQUERY KNOB */
  })
</script>

@endsection