@extends('layouts.auth.app')
@section('content')
<div class="card-body login-card-body">
    <p class="login-box-msg">Enter email to reset password</p>

    <form action="{{url('/portal')}}/index3.html" method="post">
        <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
       
        <div class="row">
         
            <!-- /.col -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

     
      
      
</div>
    <!-- /.login-card-body -->
    @endsection