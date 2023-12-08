<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="text-nowrap logo-img">
          <img src="{{ asset('/assets/images/logos/logo.jpeg') }}" width="180" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Dashboard</span>
          </li>
            @if(Auth::user()->hasRole('super-admin'))
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('updates.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-reload"></i>
                    </span>
                    <span class="hide-menu">Updates</span>
                    </a>
                </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-id-badge"></i>
                  </span>
                  <span class="hide-menu">Users</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('enrollments.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">Enrollment</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('students.index') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-users"></i>
                  </span>
                  <span class="hide-menu">Students</span>
                </a>
            </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('programs.index') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-layout-dashboard"></i>
                  </span>
                  <span class="hide-menu">Programs</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->hasRole('manager'))
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('updates.index') }}" aria-expanded="false">
                      <span>
                        <i class="ti ti-reload"></i>
                      </span>
                      <span class="hide-menu">Updates</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('programs.index') }}" aria-expanded="false">
                      <span>
                        <i class="ti ti-layout-dashboard"></i>
                      </span>
                      <span class="hide-menu">Programs</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasRole('manager|finance'))
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('enrollments.index') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-user-plus"></i>
                    </span>
                    <span class="hide-menu">Enrollment</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('students.index') }}" aria-expanded="false">
                      <span>
                        <i class="ti ti-users"></i>
                      </span>
                      <span class="hide-menu">Students</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasRole('manager|finance|admin'))
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('payments.index') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-file-description"></i>
                  </span>
                        <span class="hide-menu">Payments</span>
                    </a>
                </li>
            @endif
        </ul>
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </aside>
