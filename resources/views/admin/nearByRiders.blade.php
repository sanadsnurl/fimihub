
@include('admin.include.sideNav')
@include('admin.include.header')
<head>
    <script type="text/javascript">
        var locationsarray = '{!! $location !!}';
        locationsarray = JSON.parse(locationsarray);
        // var riderList = JSON.parse(locations);

        // for
        // [
        //     ['Raj Ghat', 28.648608, 77.250925, 1],
        //     ['Purana Qila', 28.618174, 77.242686, 2],
        //     ['Red Fort', 28.663973, 77.241656, 3],
        //     ['India Gate', 28.620585, 77.228609, 4],
        //     ['Jantar Mantar', 28.636219, 77.213846, 5],
        //     ['Akshardham', 28.622658, 77.277704, 6]
        // ];

        var locationsadata = [];

        for(var i = 0; i < locationsarray.length; i++) {
            locationsadata.push(locationsarray[i])
        }
        locations = locationsadata;


        function InitMap() {

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: new google.maps.LatLng(18.4490849, -77.2419522),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }
    </script>
</head>

<!--Data Tables -->
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css')}}"
    rel="stylesheet" type="text/css">
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
    type="text/css">


<div class="clearfix"></div>
{{-- <div id="gmaps"></div> --}}
<div class="content-wrapper">
    <div class="container-fluid">
     <!-- Breadcrumb-->

    <!-- End Breadcrumb-->
      <div class="row" onload="InitMaps();">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-uppercase">Simple Basic Map

                    <a href="{{url()->previous()}}" style="float: right" >
                        <span class="btn btn-danger">Back</span>
                    </a>
                </div>
                <body onload="InitMap();">
                    <div id="map" style="height: 500px; width: auto;">
                    </div>
                </body>
             </div>

        </div>
      </div><!--End Row-->

    </div>
    <!-- End container-fluid-->

    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->


<!-- End container-fluid-->

<!--End content-wrapper-->
{{-- @include('admin.include.footer') --}}
<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>
<!-- google maps api -->
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKXKdHQdtqgPVl2HI2RnUa_1bjCxRCQo4&callback=initMap"
async defer></script> --}}
<script src="{{asset('asset/admin/assets/plugins/gmaps/map-custom-script.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{config('GOOGLE_MAPS_API_KEY')}}&callback=initMaps&libraries=&v=weekly"
    async defer></script>
{{-- <script>
   var locations = [
            ['Raj Ghat', 28.648608, 77.250925, 1],
            ['Purana Qila', 28.618174, 77.242686, 2],
            ['Red Fort', 28.663973, 77.241656, 3],
            ['India Gate', 28.620585, 77.228609, 4],
            ['Jantar Mantar', 28.636219, 77.213846, 5],
            ['Akshardham', 28.622658, 77.277704, 6]
        ];

        function InitMaps() {

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: new google.maps.LatLng(28.614884, 77.208917),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }
</script>

 --}}
