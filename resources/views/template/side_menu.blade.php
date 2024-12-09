<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
	<div class="main-menu-content">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
			<li class=" nav-item"><a href="{{ route('dashboard') }}"><i class="icon-home"></i><span class="menu-title" data-i18n="nav.dash.main">Dashboard</span></a>
			</li>

			<li class=" nav-item"><a href="{{ route('share.pendaftaran') }}"><i class="icon-note"></i><span class="menu-title" data-i18n="nav.templates.main">Pendaftaran</span></a>
				<ul class="menu-content">
					<li><a class="menu-item" href="{{ route('mhs.pendaftaran.add') }}" data-i18n="nav.templates.vert.main">Form Pendaftaran</a></li>
					<li><a class="menu-item" href="{{ route('share.pendaftaran.list') }}" data-i18n="nav.templates.vert.main">Daftar Usulan</a></li>
				</ul>
			</li>

            <li class=" nav-item">
                <a href="{{ route('admin.pembimbing.list') }}">
                    <i class="fa fa-street-view"></i>
                    <span class="menu-title" data-i18n="nav.category.general">Pembimbing</span>
                </a>
			</li>

            <li class=" nav-item">
                <a href="{{ route('admin.reviewer.list') }}">
                    <i class="fa fa-universal-access"></i>
                    <span class="menu-title" data-i18n="nav.category.general">Reviewer</span>
                </a>
			</li>

			<li class=" nav-item"><a href="{{ route('admin.users.list') }}"><i class="ft-users"></i><span class="menu-title" data-i18n="nav.category.general">User</span></a>
				<ul class="menu-content">
					<li><a class="menu-item" href="{{ route('admin.users.list') }}" data-i18n="nav.templates.vert.main">Manaj. User</a></li>
					<li><a class="menu-item" href="{{ route('admin.users.roles') }}" data-i18n="nav.templates.vert.roles">Manaj. Roles</a></li>
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
