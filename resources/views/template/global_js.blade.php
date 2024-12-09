<!-- BEGIN VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN VENDOR JS-->

<!-- BEGIN PAGE VENDOR JS (GLOBAL)-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/forms/icheck/icheck.min.js') }}"></script>
<!-- END PAGE VENDOR JS (GLOBAL)-->

<!-- BEGIN PAGE VENDOR JS (PAGE LEVEL)-->
@stack('page_vendor_level_js')
<!-- END PAGE VENDOR JS (PAGE LEVEL)-->

<!-- BEGIN ROBUST JS-->
<script src="{{ asset('assets/template/robust/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('assets/template/robust/app-assets/js/core/app.js') }}"></script>
<!-- END ROBUST JS-->

<!-- BEGIN PAGE LEVEL JS (PAGE LEVEL)-->
@stack('page_level_js')
<!-- END PAGE LEVEL JS (PAGE LEVEL)-->
