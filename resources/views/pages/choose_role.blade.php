
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Robust admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template.">
    <meta name="keywords" content="admin template, robust admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>Unlock User - Robust - Responsive Bootstrap 4 Admin Dashboard Template for Web Application</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/template/robust/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/template/robust/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/vendors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/icheck/icheck.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/icheck/custom.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/colors/palette-gradient.css') }}">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/assets/css/style.css') }}">
    <!-- END Custom CSS-->
  </head>
  <body class="vertical-layout vertical-menu 1-column   menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-4 col-10 box-shadow-2 p-0">
			<div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                <h3 class="text-center">Pilih Role Anda</h3>
				<p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2"></p>
				<div class="card-content">
					<div class="card-body">
						<!-- <form class="form-horizontal" action="login-simple.html" novalidate> -->
						<!-- 	<fieldset class="form-group position-relative has-icon-left">
								<input type="password" class="form-control form-control-lg input-lg" id="password" placeholder="Enter Password" required>
								<div class="form-control-position">
								    <i class="fa fa-key"></i>
								</div>
							</fieldset>
							<div class="form-group row">
	                            <div class="col-md-6 col-12 text-center text-sm-left">
	                                <fieldset>
	                                    <input type="checkbox" id="remember-me" class="chk-remember">
	                                    <label for="remember-me"> Remember Me</label>
	                                </fieldset>
	                            </div>
	                            <div class="col-md-6 col-12 float-sm-left text-center text-sm-right"><a href="recover-password.html" class="card-link"><i class="ft-unlock"></i> Forgot Password?</a></div>
	                        </div> -->
                            @forelse($roles_avail as $role_avail)
							              <a href="{{ route('admin.choose_role') }}?choose={{ $role_avail->id }}" class="btn btn-outline-primary btn-lg btn-block">{{ $role_avail->keterangan }}</a>
                            @empty
                            <div class="alert alert-info"><b>Perhatian :</b> Anda hanya tidak memiliki role</div>
                            <a href="{{ route('home') }}" class="btn btn-outline-warning btn-lg btn-block">Kembali</a>
                            @endforelse
						<!-- </form> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
        </div>
      </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('assets/template/robust/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('assets/template/robust/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('assets/template/robust/app-assets/vendors/js/forms/icheck/icheck.min.js') }}"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <script src="{{ asset('assets/template/robust/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('assets/template/robust/app-assets/js/core/app.js') }}"></script>
    <!-- END ROBUST JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="{{ asset('assets/template/robust/app-assets/js/scripts/forms/form-login-register.js') }}"></script>
    <!-- END PAGE LEVEL JS-->
  </body>
</html>
