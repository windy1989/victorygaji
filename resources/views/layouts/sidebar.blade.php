        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
				<div class="dropdown header-profile2 ">
					<a class="nav-link " href="javascript:void(0);"  role="button" data-bs-toggle="dropdown">
						<div class="header-info2 d-flex align-items-center">
							<img src="{{ url('assets/images/profile/pic1.jpg') }}" alt="">
							<div class="d-flex align-items-center sidebar-info">
								<div>
									<span class="font-w400 d-block">{{ session('bo_name') }}</span>
									<small class="text-end font-w400">Access : {{ ucfirst(session('bo_typename')) }}</small>
								</div>	
								<i class="fas fa-chevron-down"></i>
							</div>
							
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-end">
						{{-- <a href="{{ url('profile') }}" class="dropdown-item ai-icon ">
							<svg  xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							<span class="ms-2">Profile </span>
						</a> --}}
						<a href="{{ url('logout') }}" class="dropdown-item ai-icon">
							<svg  xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
							<span class="ms-2">Logout </span>
						</a>
					</div>
				</div>
				<ul class="metismenu" id="menu">
                    <li class="{{ Request::segment(2) == 'dashboard' ? 'mm-active' : '' }}">
                        <a href="{{ url('dashboard') }}" class="{{ Request::segment(2) == 'dashboard' ? 'mm-active' : '' }}" aria-expanded="false">
                            <i class="flaticon-025-dashboard"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
					
					@if(session('bo_type') == '1')
					<li class="{{ Request::segment(2) == 'project' ? 'mm-active' : '' }}">
                        <a href="{{ url('project') }}" class="{{ Request::segment(2) == 'project' ? 'mm-active' : '' }}" aria-expanded="false">
							<i class="flaticon-381-notepad-1"></i>
							<span class="nav-text">Proyek</span>
						</a>
					</li>
                    <li class="{{ Request::segment(2) == 'payroll' ? 'mm-active' : '' }}">
                        <a href="{{ url('payroll') }}" class="{{ Request::segment(2) == 'payroll' ? 'mm-active' : '' }}" aria-expanded="false">
							<i class="flaticon-381-file-1"></i>
							<span class="nav-text">Payroll</span>
						</a>
					</li>

					<li class="{{ Request::segment(2) == 'user' ? 'mm-active' : '' }}">
                        <a href="{{ url('user') }}" class="{{ Request::segment(2) == 'user' ? 'mm-active' : '' }}" aria-expanded="false">
							<i class="flaticon-381-user-9"></i>
							<span class="nav-text">User Manager</span>
						</a>
					</li>
					@endif
                </ul>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->