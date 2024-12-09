<!-- BEGIN VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/vendors.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/icheck/icheck.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/icheck/custom.css') }}">
<!-- END VENDOR CSS-->

<!-- BEGIN ROBUST CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/app.css') }}">
<!-- END ROBUST CSS-->

<!-- BEGIN PAGE LEVEL CSS (GLOBAL)-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/menu/menu-types/vertical-compact-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/colors/palette-gradient.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/core/colors/palette-climacon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/pages/users.css') }}">
<!-- END PAGE LEVEL (GLOBAL)-->

<!-- BEGIN PAGE LEVEL CSS (PAGE LEVEL)-->
@stack('page_level_css')
<!-- END PAGE LEVEL CSS (PAGE LEVEL)-->

<!-- BEGIN CUSTOM CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/assets/css/style.css') }}">
<!-- END CUSTOM CSS-->

<!-- BEGIN PAGE CUSTOM CSS-->
@stack('page_custom_css')
<!-- END PAGE CUSTOM CSS-->
