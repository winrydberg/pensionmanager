  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-1">
    <!-- Brand Logo -->
    <a href="{{url('/dashboard')}}" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-0" style="opacity: .8">
      <span class="brand-text font-weight-light">Pension CMP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('dist/img/avatar.png')}}" class="img-circle elevation-0" alt="User Image">
        </div>
        <div class="info">
          @if(auth()->check())
          <a href="#" class="d-block">{{auth()->user()->firstname.' '.auth()->user()->lastname}}</a>
          @endif
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{url('/dashboard')}}" class="nav-link {{request()->path() == 'dashboard' ? 'active': ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          

          @hasanyrole('system-admin|claim-entry|front-desk')
          <li class="nav-header">CLAIM</li>
              <li class="nav-item">
                <a href="{{url('new-claim')}}" class="nav-link {{request()->path() == 'new-claim' ? 'active': ''}}">
                  <i class="fa fa-plus-circle nav-icon"></i>
                  <p>New Claim
                    <span class="right badge badge-danger">New</span></p>
                </a>
              </li>
          

              <li class="nav-item">
                <a href="{{url('unprocessed-claims')}}" class="nav-link {{request()->path() == 'unprocessed-claims' ? 'active': ''}}">
                  <i class="far fa-folder nav-icon "></i>
                  <p>Not Processed</p>
                </a>
              </li>
          @endhasanyrole
          <li class="nav-item">
                <a href="{{url('invalid-claims')}}" class="nav-link {{request()->path() == 'invalid-claims' ? 'active': ''}}">
                  <i class="fa fa-times nav-icon "></i>
                  <p>Invalid Claims</p>
                </a>
          </li>

          @hasanyrole('system-admin|audit')
          <li class="nav-header">AUDIT</li>


              <li class="nav-item">
                <a href="{{url('claims')}}" class="nav-link {{request()->path() == 'claims' ? 'active': ''}}">
                  <i class="far fa-folder-open nav-icon "></i>
                  <p>Pending : Audit</p>
                </a>
              </li>
            @endrole

               <li class="nav-item">
                <a href="{{url('claim-with-issues')}}" class="nav-link {{request()->path() == 'claim-with-issues' ? 'active': ''}}">
                  <i class="far fa-question-circle nav-icon "></i>
                  <p>Issue</p>
                  <span class="right badge badge-danger">{{$issueCount}}</span>
                  
                </a>
              </li>
          



          @hasanyrole('system-admin|accounting')
          <li class="nav-header">SCHEME</li>

             
              <li class="nav-item">
                <a href="{{url('/schemes')}}" class="nav-link {{request()->path() == 'schemes' ? 'active': ''}}">
                    <i class="nav-icon fa fa-newspaper "></i>
                  <p>Pending : Scheme</p>
                </a>
              </li>

               <li class="nav-item">
                <a href="{{url('scheme-claims')}}" class="nav-link {{request()->path() == 'scheme-claims' ? 'active': ''}}">
                  <i class="far fa-folder nav-icon "></i>
                  <p>Received : Scheme</p>
                </a>
              </li>

              
               @endrole

               <li class="nav-item">
                <a href="{{url('/notifications')}}" class="nav-link {{request()->path() == 'notifications' ? 'active': ''}}">
                  <i class="fa fa-bell nav-icon"></i>
                  <p>Notifications</p>
                  <span class="right badge badge-danger">{{$notifCount}}</span></p>
                </a>
              </li>

             

             {{-- 
              <li class="nav-item">
                <a href="{{url('audited-claims')}}" class="nav-link">
                  <i class="far fa-folder-open nav-icon text-success"></i>
                  <p>Audited Claims</p>
                </a>
              </li> --}}

              <li class="nav-header">SEARCH</li>

              <li class="nav-item">
                <a href="{{url('search-company')}}" class="nav-link {{request()->path() == 'search-company' ? 'active': ''}}">
                    <i class="nav-icon fa fa-search-plus "></i>
                  <p>Search Claim</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{url('search-claim')}}" class="nav-link {{request()->path() == 'search-claim' ? 'active': ''}}">
                  <i class="fa fa-search-location nav-icon"></i>
                  <p>Search Employee</p>
                </a>
              </li>


              

          

          {{-- @role('system-admin')
         <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Departments
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="#" class="nav-link">
                   <i class="nav-icon far fa-circle text-info"></i>
                  <p>New Department</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                    <i class="nav-icon far fa-circle text-info"></i>
                  <p>Departments</p>
                </a>
              </li>
            </ul>
          </li>
          @endrole --}}
            @role('system-admin')
             <li class="nav-header">NEW COMPANY/SCHEME</li>
            
              <li class="nav-item">
                <a href="{{url('/new-scheme')}}" class="nav-link {{request()->path() == 'new-scheme' ? 'active': ''}}">
                   <i class="nav-icon fa fa-folder-plus "></i>
                  <p>New Scheme</p>
                </a>
              </li>
             
                <li class="nav-item">
                  <a href="{{url('new-company')}}" class="nav-link {{request()->path() == 'new-company' ? 'active': ''}}">
                    <i class="nav-icon fa fa-business-time "></i>
                    <p>New Company</p>
                  </a>
              </li>
            @endrole
         
          <li class="nav-header">REPORTS</li>
              <li class="nav-item">
                <a href="{{url('/company-reports')}}" class="nav-link {{request()->path() == 'company-reports' ? 'active': ''}}">
                   <i class="nav-icon fa fa-chart-area "></i>
                  <p>Company Reports</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{url('scheme-reports')}}" class="nav-link {{request()->path() == 'scheme-reports' ? 'active': ''}}">
                   <i class="nav-icon fa fa-chart-bar "></i>
                  <p>Scheme Reports</p>
                </a>
              </li> 
              
              @role('system-admin')
              <li class="nav-header">ACCOUNTS</li>

              
              
              <li class="nav-item">
                <a href="{{url('/new-staff')}}" class="nav-link {{request()->path() == 'new-staff' ? 'active': ''}}">
                  <i class="fa fa-user-plus nav-icon"></i>
                  <p>New Staff</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{url('/all-staffs')}}" class="nav-link {{request()->path() == 'all-staffs' ? 'active': ''}}">
                  <i class="fa fa-users nav-icon"></i>
                  <p>All Staff</p>
                </a>
              </li>
              @endrole

              <li class="nav-item">
                <a href="{{url('/logout')}}" class="nav-link">
                  <i class="nav-icon fa fa-reply text-danger"></i>
                  <p>Logout</p>
                </a>
              </li>
              
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>