<!--Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
<!--End Back To Top Button-->

<!--Start footer-->
<footer class="footer">
    <div class="container">
        <div class="text-center">
            Copyright 2020 by  <a href="{{url('/')}}" class="link">FiMi Hub</a>
        </div>
    </div>
</footer>
<!--End footer-->

</div>
<!--End wrapper-->

<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/js/popper.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/js/bootstrap.min.js')}}"></script>

<!-- simplebar js -->
<script src="{{asset('asset/admin/assets/plugins/simplebar/js/simplebar.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>
<!-- sidebar-menu js -->
{{-- <script src="{{asset('asset/admin/assets/js/sidebar-menu.js')}}"></script> --}}
<!-- Custom scripts -->
<script src="{{asset('asset/admin/assets/js/app-script.js')}}"></script>

<!-- Vector map JavaScript -->
<script src="{{asset('asset/admin/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- Chart js -->
<script src="{{asset('asset/admin/assets/plugins/Chart.js/Chart.min.js')}}"></script>
<!-- Index js -->
<script src="{{asset('asset/admin/assets/js/index.js')}}"></script>
@if (request()->segment(2) == 'trackOrder')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>


    <script type="text/javascript" src="{{asset('asset/customer/assets/scripts/mapInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/customer/assets/scripts/searchMap.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/customer/assets/scripts/mapDistance.js')}}"></script>
@endif
</body>

<!-- Mirrored from codervent.com/rocker/color-version/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Sep 2018 19:46:25 GMT -->

</html>
