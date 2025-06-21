<!-- partial:partials/_sidebar.html -->
<nav class="sidebar" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.feestructure.form') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Fee Structure</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.exam.form') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Exam</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" data-target="#test" href="#test" aria-expanded="false" aria-controls="error">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Question</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="test">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.test.form') }}"> Add </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.test.list') }}"> List </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" data-target="#student" href="#student" aria-expanded="false" aria-controls="error">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Students</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="student">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.student.form') }}"> Add </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" data-target="#report" href="#report" aria-expanded="false" aria-controls="error">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Reports</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="report">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.reports.students') }}"> Students</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.reports.exam.list') }}"> Test list</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.reports.studenttestattempt') }}"> Test Attempts</a></li>
        </ul>
      </div>
    </li>
  </ul>
</nav>
<!-- partial -->