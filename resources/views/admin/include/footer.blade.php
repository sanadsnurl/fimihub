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
@if(Session::has('popup_delete'))
<div class="modal mt-5" id="modal-animation-8" style="display:block;">
    <div class="modal-dialog">
      <div class="modal-content animated swing">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-trash"></i> Are you Sure ?</h5>
          {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
        </div>
        <div class="modal-body">
          <p>Do you Really Want to Delete ?</p>
        </div>
        <div class="modal-footer">
            <a href="{{url()->previous()}}">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </a>
          <a href="{{Session::get('popup_delete')}}" class="btn btn-danger"><i class="fa fa-check-square-o"></i> Delete</a>
          {{-- <button type="button" class="btn btn-danger"><i class="fa fa-check-square-o"></i> Delete</button> --}}
        </div>
      </div>
    </div>
  </div>
</body>
@endif
<!-- Mirrored from codervent.com/rocker/color-version/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Sep 2018 19:46:25 GMT -->

</html>
