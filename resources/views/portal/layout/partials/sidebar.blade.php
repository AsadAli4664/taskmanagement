<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
      <img src="{{url('/portal')}}/dist/img/psca-logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">PSCA Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{url('/portal')}}/dist/img/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <div style="color: #ccc;">{{auth()->user()->name}}</div>
        </div>
      </div>

     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt text-warning"></i>
              <p>Dashboard</p>
            </a>
          </li>
          @if(auth()->user()->hasPermissionTo('view_users'))
          <li class="nav-item">
            <a href="{{route('user.index')}}" class="nav-link">
              <i class="nav-icon far fa-user text-danger"></i>
              <p class="text">User</p>
            </a>
          </li>
          @endif
          
         
          <!--<li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-tag text-success"></i>
              <p>Category</p>
            </a>
          </li> -->
          @if(auth()->user()->hasPermissionTo('view_task'))
          <li class="nav-item">
            <a href="{{route('task.index')}}" class="nav-link">
              <i class="nav-icon fa fa-circle text-info"></i>
              <p>Tasks</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->hasPermissionTo('view_subtask'))
          <li class="nav-item">
            <a href="{{route('subtask.index')}}" class="nav-link">
              <i class="nav-icon fa fa-book text-orange"></i>
              <p>Activities</p>
            </a>
          </li>
          @endif
       
       <li class="nav-item">
            <a href="http://10.20.101.192:8083" target="_blank" class="nav-link">
            <i class="nav-icon fa fa-eye text-green"></i>
              <p>Central Dashboard</p>
            </a>
          </li>
       <!-- @if(auth()->user()->hasPermissionTo('manage_permission'))
          <li class="nav-item">
            <a href="{{route('permission.index')}}" class="nav-link">
              <i class="nav-icon fa fa-eye text-blue"></i>
              <p>Permissions</p>
            </a>
          </li>
        @endif -->
        
          

          <li class="nav-item">
            <a href="{{route('setting.index')}}" class="nav-link">
              <i class="nav-icon fa fa-cog text-purple"></i>
              <p>Reset Password</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>