
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Robust admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template.">
    <meta name="keywords" content="admin template, robust admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>APLIKASI PKM</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/template/robust/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/template/robust/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/vendors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/icheck/icheck.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/icheck/custom.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/menu/menu-types/vertical-compact-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/pages/login-register.css') }}">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/assets/css/style.css') }}">
    <!-- END Custom CSS-->

    <style type="text/css">
        html body.bg-full-screen-image {
            background: url({{ asset('assets/imgs/img_big_login.jpg') }}) no-repeat center center fixed;
            webkit-background-size: cover; /** */
            background-size: cover;
            /** **/
        }
    </style>


  </head>
  <body class="vertical-layout vertical-compact-menu 1-column  bg-full-screen-image menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-compact-menu" data-col="1-column">
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-4 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                <div class="card-header border-0">
                    <div class="card-title text-center">
                        <img src="{{ asset('assets/imgs/logo_undip.png') }}" alt="logo undip" style="width: 100px">
                    </div>
                    <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-5 font-large-1"><span>APLIKASI PKM</span></h6>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <!-- <a href="register-with-bg-image.html" class="btn btn-outline-danger btn-block"><i class="ft-user"></i> Login via SSO</a> -->
                        @if(session()->has('message'))
                            <div class="alert alert-warning">
                                <b><i class="fa fa-info-circle"></i> {{ session()->get('message') }}</b>
                            </div>
                        @endif
                        <a href="{{ url('/sso/login') }}" class="btn btn-lg btn-block" style="border: 1px solid #CCC;background-color: #FFF;"><img src="{{ asset('assets/imgs/img-microsoft-365.png') }}" style="width: 50px; margin-right: 10px;"> Login via SSO</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        </div>
      </div>
    </div>

    @if(!empty(Request::get('u')))
    <div class="grid-form-dialog" title=":: Direct Login ::">
        <div class="container">
            <form>
                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="username" class="form-control" id="username" placeholder="Username" value="{{Request::get('u') }}" readonly="readonly">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="button" class="btn btn-primary" id="direct_submit">Sign in</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('assets/template/robust/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->

    <script src="{{ asset('assets/template/robust/app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}"></script>

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


    <script type="text/javascript">
        @if(!empty(Request::get('u')))
        $(document).ready(function(){

            $(".grid-form-dialog").dialog({
                autoOpen: true,
                width: 500,
                modal: true,
                draggable: false,
                resizable: false,
                // position: { my: "center top", at: "center top"}
            });
            // $( ".grid-form-dialog" ).dialog("open");

        });

        $(document).on('click','#direct_submit',function(){
            let csrf = $('meta[name=csrf-token]').attr("content");
            let password =$('#password').val();
            $.post('{{ route('login.ajax', ['method' => 'login_direct']) }}',{'_token' :csrf,'password':password,'user':'{{Request::get('u') }}'},function(res){
                if(res.status == 'ok'){
                    location.reload('{{ route('dashboard') }}');
                }
                alert(res.status);
            });
        })
        @endif
    </script>

  </body>
</html>
