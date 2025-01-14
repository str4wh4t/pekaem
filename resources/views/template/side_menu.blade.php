<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
	<div class="main-menu-content">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
			<li class=" nav-item small">
				<a class="p-0 mt-1" href="{{ route('dashboard') }}"><i class="icon-home"></i><span class="menu-title" data-i18n="nav.dash.main">Dashboard</span></a>
			</li>

			@if(UserHelp::is_mhs())
			<li class=" nav-item small">
				<a class="p-0 mt-1" href="{{ route('share.pendaftaran') }}"><i class="icon-note"></i><span class="menu-title" data-i18n="nav.templates.main">Pendaftaran</span></a>
				<ul class="menu-content">
					<li><a class="menu-item" href="{{ route('mhs.pendaftaran.add') }}" data-i18n="nav.templates.vert.main">Form Pendaftaran</a></li>
					<li><a class="menu-item" href="{{ route('share.pendaftaran.list') }}" data-i18n="nav.templates.vert.main">Daftar Usulan</a></li>
				</ul>
			</li>
			@endif

			@if(UserHelp::get_selected_role() == 'ADMINFAKULTAS' || UserHelp::get_selected_role() == 'ADMIN' || UserHelp::get_selected_role() == 'WD1' || UserHelp::get_selected_role() == 'SUPER')
			<li class=" nav-item small">
				<a class="p-0 mt-1" href="{{ route('share.pendaftaran') }}"><i class="icon-note"></i><span class="menu-title" data-i18n="nav.templates.main">Pendaftaran</span></a>
				<ul class="menu-content">
					<li><a class="menu-item" href="{{ route('share.pendaftaran.list') }}" data-i18n="nav.templates.vert.main">Daftar Usulan</a></li>
				</ul>
			</li>
			@endif


			@if(!UserHelp::is_mhs())

			@if(UserHelp::get_selected_role() == 'ADMIN' || UserHelp::get_selected_role() == 'SUPER')
            <li class=" nav-item small">
                <a class="p-0 mt-1" href="{{ route('admin.pembimbing.list') }}">
                    <i class="fa fa-street-view"></i>
                    <span class="menu-title" data-i18n="nav.category.general">Pendamping</span>
                </a>
			</li>
			@endif
			@if(UserHelp::get_selected_role() == 'PEMBIMBING')
			@php
				$pegawai = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			@endphp
				<li class=" nav-item small">
					<a class="p-0 mt-1" href="{{ route('share.pendaftaran.list', ['jenis' => 'pembimbing','pegawai_id' => $pegawai->id]) }}">
						<i class="fa fa-street-view"></i>
						<span class="menu-title" data-i18n="nav.category.general">Pendampgn</span>
					</a>
				</li>
			@endif

			@if(UserHelp::get_selected_role() == 'ADMIN' || UserHelp::get_selected_role() == 'SUPER')
            <li class=" nav-item small">
                <a class="p-0 mt-1" href="{{ route('admin.reviewer.list') }}">
                    <i class="fa fa-universal-access"></i>
                    <span class="menu-title" data-i18n="nav.category.general">Reviewer</span>
                </a>
			</li>
			@endif

			@if(UserHelp::get_selected_role() == 'REVIEWER')
			@php
				$pegawai = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			@endphp
			<li class=" nav-item small">
                <a class="p-0 mt-1" href="{{ route('share.pendaftaran.list', ['jenis' => 'reviewer','pegawai_id' => $pegawai->id]) }}">
                    <i class="fa fa-universal-access"></i>
                    <span class="menu-title" data-i18n="nav.category.general">Reviewer</span>
                </a>
			</li>
			@endif


				@if(UserHelp::get_selected_role() == 'ADMIN' || UserHelp::get_selected_role() == 'SUPER')
				<li class=" nav-item small">
					<a class="p-0 mt-1" href="{{ route('kategori-kriteria.index') }}">
						<i class="fa fa-clone"></i>
						<span class="menu-title" data-i18n="nav.category.general">Kriteria</span>
					</a>
				</li>

				<li class=" nav-item small">
					<a class="p-0 mt-1" href="{{ route('kategori-kegiatan.index') }}">
						<i class="fa fa-cubes"></i>
						<span class="menu-title" data-i18n="nav.category.general">Kegiatan</span>
					</a>
				</li>

				<li class=" nav-item small">
					<a class="p-0 mt-1" href="#">
						<i class="fa fa-pencil-square"></i>
						<span class="menu-title" data-i18n="nav.category.general">Penilaian</span>
					</a>
					<ul class="menu-content" style="">
						@foreach (\App\KategoriKegiatan::all() as $kategori_kegiatan)
						<li class="has-sub">
							<a class="menu-item" href="#" data-i18n="nav.navbars.nav_hide_on_scroll.main">{{ $kategori_kegiatan->nama_kategori_kegiatan }}</a>
							<ul class="menu-content" style="">
								@foreach ($kategori_kegiatan->jenis_pkm as $jenis_pkm)
								<li style="background-color: #ffeecc;">
									<a class="menu-item" href="{{ route('jenis-pkm.daftar-penilaian', ['kategori_kegiatan' => $kategori_kegiatan, 'jenis_pkm' => $jenis_pkm]) }}" data-i18n="nav.navbars.nav_hide_on_scroll.nav_hide_on_scroll_top">{{ $jenis_pkm->nama_pkm }}</a>
								</li>
								@endforeach
							</ul>
						</li>
						@endforeach
					</ul>
				</li>

				<li class=" nav-item small">
					<a class="p-0 mt-1" href="{{ route('admin.users.list') }}"><i class="ft-users"></i><span class="menu-title" data-i18n="nav.category.general">User</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" href="{{ route('admin.users.list') }}" data-i18n="nav.templates.vert.main">Manaj. User</a></li>
						<li><a class="menu-item" href="{{ route('admin.users.roles') }}" data-i18n="nav.templates.vert.roles">Manaj. Roles</a></li>
					</ul>
				</li>
				@endif
			@endif
				<li class=" nav-item small">
					<a class="p-0 mt-1" href="#"><i class="fa fa-whatsapp"></i><span class="menu-title" data-i18n="nav.category.general">Bantuan</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" target="_blank" href="https://wa.me/6282226235238" data-i18n="nav.templates.vert.main">SPV Prestasi Mhs</a></li>
					</ul>
				</li>

			{{-- <li class=" nav-item"><a href="{{ route('admin.pendaftaran.ploting.list') }}"><i class="fa fa-user-secret"></i><span class="menu-title" data-i18n="nav.category.general">Ploting</span></a>
				<ul class="menu-content">
					<li><a class="menu-item" href="{{ route('admin.pendaftaran.ploting.list') }}" data-i18n="nav.templates.vert.main">Ploting Reviewer</a></li>
				</ul>
			</li> --}}
		</ul>
	</div>
</div>
