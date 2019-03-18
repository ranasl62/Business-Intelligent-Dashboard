<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>P-BI</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Power BI</b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">                
                  <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
                </ul>
            </div>
            
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                <img src="{{asset('dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                <p>{{auth()->user()->name}}</p>
                <a href="user/{{auth()->user()->id}}/edit"><i class="fa fa-circle text-success"></i> Edit Profile</a>
                </div>
            </div>
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
        @permission('DashBoard-Permission')
            <li id="dashboard" class="treeview {{ Request::is('dashboard') ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}">
                  <i class="fa fa-dashboard"></i>
                  <span>Dashboard</span>
                </a>
            </li>
        @endpermission
                <!-- <li class="header">MAIN NAVIGATION</li> -->
        @permission(['VR-Chart','SMS-Chart','PGW-Chart'])
        <li class="treeview {{ Request::is('vr/chart') || Request::is('sms/chart') || Request::is('pgw/chart')? 'active' : '' }}">
            <a href="#">
              <i class="fa fa-pie-chart"></i>
              <span>Charts</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
          <ul class="treeview-menu">
            @permission('VR-Chart')
            <li class="{{ Request::is('vr/chart') ? 'active' : '' }}" ><a href="{{ url('/vr/chart') }}"><i class="glyphicon glyphicon-headphones"></i>VR Transaction</a></li>
            @endpermission
            @permission('SMS-Chart')
              <li class="{{ Request::is('sms/chart') ? 'active' : '' }}" ><a href="{{ url('/sms/chart') }}"><i class="glyphicon glyphicon-envelope"></i>SMS Transaction</a></li>
            @endpermission
            @permission('PGW-Chart')
              <li class="{{ Request::is('pgw/chart') ? 'active' : '' }}" ><a href="{{ url('/pgw/chart') }}"><i class="fa fa-credit-card"></i>PGW Transaction</a></li>
            @endpermission
          </ul>
        </li>
        @endpermission
        @permission(['VR-Report','SMS-Report','PGW-Report'])
        <li id="report" class="treeview {{ Request::is('vr/report') || Request::is('sms/report') || Request::is('pgw/report')? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-clipboard"></i>
            <span>Report</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
              @permission('VR-Report')
              <li class="{{ Request::is('vr/report') ? 'active' : '' }}" ><a href="{{ url('/vr/report') }}"><i class="fa fa-list-alt"></i>VR Report</a></li>
              @endpermission
              @permission('SMS-Report')
              <li class="{{ Request::is('sms/report') ? 'active' : '' }}" ><a href="{{ url('/sms/report') }}"><i class="fa fa-list-alt"></i>SMS Report</a></li>
              @endpermission 
              @permission('PGW-Report')
              <li class="{{ Request::is('pgw/report') ? 'active' : '' }}" ><a href="{{ url('/pgw/report') }}"><i class="fa fa-list-alt"></i>PGW Report</a></li>
              @endpermission
          </ul>
        </li>
        @endpermission
        @permission(['VR-Invoice','VR-Invoice-Payment','SMS-Invoice','PGW-Invoice'])
        <li id="invoice" class="treeview {{ Request::is('vr/invoice') || Request::is('sms/invoice') || Request::is('pgw/invoice')? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-fa fa-file"></i>
            <span>Invoice</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            @permission(['VR-Invoice','VR-Invoice-Payment'])
              <li class="{{ Request::is('vr/invoice') ? 'active' : '' }}" ><a href="{{ url('/vr/invoice') }}"><i class="fa fa-list-alt"></i>VR Invoice</a></li>
            @endpermission
            @permission('SMS-Invoice')
              <li class="{{ Request::is('sms/invoice') ? 'active' : '' }}" ><a href="{{ url('/sms/invoice') }}"><i class="fa fa-list-alt"></i>SMS Invoice</a></li>
            @endpermission
            @permission('PGW-Invoice')
              <li class="{{ Request::is('pgw/invoice') ? 'active' : '' }}" ><a href="{{ url('/pgw/invoice') }}"><i class="fa fa-list-alt"></i>PGW Invoice</a></li>
            @endpermission
          </ul>
        </li>
        @endpermission


        @permission('File-Upload')
        <li id="report" class="treeview {{ Request::is('file/upload')? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-clipboard"></i>
            <span>File</span>
            <i class="fa fa-fiel-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
              <li class="{{Request::is('/file/')}} ? 'active' : '' }}" ><a href="{{ url('/file/') }}"><i class="fa fa-upload"></i>Upload Files</a></li>
          </ul>
        </li>
        @endpermission
        @permission('Admin-Permission') 
          <li class="treeview {{ Request::is('user') || Request::is('role')? 'active' : '' }}">
            <a href="#">
              <i class="glyphicon glyphicon-user"></i>
              <span>Role & Users</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="{{ Request::is('user') ? 'active' : '' }}"><a href="{{route('user.index')}}"><i class="glyphicon glyphicon-list-alt"></i>Users</a></li>
              <li class="{{ Request::is('role') ? 'active' : '' }}"><a href="{{route('role.index')}}"><i class="glyphicon glyphicon-registration-mark"></i>User Roles</a></li>
            </ul>
          </li>
        @endpermission
    </ul>
</section>
<!-- /.sidebar -->
</aside>
</div><!-- ./wrapper -->
<script>
  
</script>