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
									<span class="font-w400 d-block">{{ session('bo_nama') }}</span>
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
					<li class="{{ in_array(Request::segment(2),['proyek','surat_penawaran','spk','invoice','kelengkapan_dokumen','dokumen_andalalin','sidang','revisi']) ? 'mm-active' : '' }}">
						<a class="has-arrow " href="javascript:void()">
							<i class="flaticon-381-notepad-1"></i>
							<span class="nav-text">Proyek</span>
						</a>
						<ul aria-expanded="false">
							<li class="{{ Request::segment(2) == 'proyek' ? 'mm-active' : '' }}"><a href="{{ url('proyek') }}">Daftar</a></li>
							<li class="{{ Request::segment(2) == 'surat_penawaran' ? 'mm-active' : '' }}"><a href="{{ url('surat_penawaran') }}">Surat Penawaran</a></li>
							<li class="{{ Request::segment(2) == 'spk' ? 'mm-active' : '' }}"><a href="{{ url('spk') }}">SPK</a></li>
							<li class="{{ Request::segment(2) == 'invoice' ? 'mm-active' : '' }}"><a href="{{ url('invoice') }}">Invoice</a></li>
							<li class="{{ in_array(Request::segment(2),['hasil_survei','item_survei','dokumentasi_survei']) ? 'mm-active' : '' }}"><a class="has-arrow" href="javascript:void()" aria-expanded="false">Surveyor</a>
                                <ul aria-expanded="false">
                                    <li class="{{ Request::segment(2) == 'hasil_survei' ? 'mm-active' : '' }}"><a href="{{ url('hasil_survei') }}">Hasil survei</a></li>
                                    <li class="{{ Request::segment(2) == 'item_survei' ? 'mm-active' : '' }}"><a href="{{ url('item_survei') }}">Item survei</a></li>
                                    <li class="{{ Request::segment(2) == 'dokumentasi_survei' ? 'mm-active' : '' }}"><a href="{{ url('dokumentasi_survei') }}">Dokumentasi survei</a></li>
                                </ul>
                            </li>
							<li class="{{ Request::segment(2) == 'kelengkapan_dokumen' ? 'mm-active' : '' }}"><a href="{{ url('kelengkapan_dokumen') }}">Kelengkapan Dok.</a></li>
							<li class="{{ Request::segment(2) == 'dokumen_andalalin' ? 'mm-active' : '' }}"><a href="{{ url('dokumen_andalalin') }}">Dok. Andalalin</a></li>
							<li class="{{ Request::segment(2) == 'sidang' ? 'mm-active' : '' }}"><a href="{{ url('sidang') }}">Sidang</a></li>
							<li class="{{ Request::segment(2) == 'revisi' ? 'mm-active' : '' }}"><a href="{{ url('revisi') }}">Revisi</a></li>
							<li class="{{ in_array(Request::segment(2),['laporan_pembayaran']) ? 'mm-active' : '' }}"><a class="has-arrow" href="javascript:void()" aria-expanded="false">Laporan</a>
                                <ul aria-expanded="false">
                                    <li class="{{ Request::segment(2) == 'laporan_pembayaran' ? 'mm-active' : '' }}"><a href="{{ url('laporan_pembayaran') }}">Pembayaran</a></li>
                                </ul>
                            </li>
						</ul>
					</li>
					<li class="{{ in_array(Request::segment(2),['payroll']) ? 'mm-active' : '' }}">
						<a class="has-arrow " href="javascript:void()">
							<i class="flaticon-381-news"></i>
							<span class="nav-text">HRD</span>
						</a>
						<ul aria-expanded="false">
							<li class="{{ Request::segment(2) == 'payroll' ? 'mm-active' : '' }}"><a href="{{ url('payroll') }}">Payroll</a></li>
						</ul>
					</li>
					<li class="{{ in_array(Request::segment(2),['customer','user']) ? 'mm-active' : '' }}">
						<a class="has-arrow " href="javascript:void()">
							<i class="flaticon-381-folder-19"></i>
							<span class="nav-text">Master Data</span>
						</a>
						<ul aria-expanded="false">
							<li class="{{ Request::segment(2) == 'customer' ? 'mm-active' : '' }}"><a href="{{ url('customer') }}">Customer</a></li>
							<li class="{{ Request::segment(2) == 'user' ? 'mm-active' : '' }}"><a href="{{ url('user') }}">User</a></li>
							<li class="{{ Request::segment(2) == 'peruntukan' ? 'mm-active' : '' }}"><a href="{{ url('peruntukan') }}">Peruntukan</a></li>
							<li class="{{ Request::segment(2) == 'jenis_proyek' ? 'mm-active' : '' }}"><a href="{{ url('jenis_proyek') }}">Jenis Proyek</a></li>
							<li class="{{ Request::segment(2) == 'rekening_bank' ? 'mm-active' : '' }}"><a href="{{ url('rekening_bank') }}">Rekening Bank</a></li>
						</ul>
					</li>
                </ul>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->