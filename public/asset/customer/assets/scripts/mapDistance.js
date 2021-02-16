function kilomiter(dakota, frick, service_data, total_amount) {
    // console.log(dakota, 'dakota');
    // console.log(frick, 'frick');
    var directionsData = {};
    if (isNaN(frick.lat)) {
        return false;
    }
    if (isNaN(frick.lng)) {
        return false;
    }
    if (isNaN(dakota.lat)) {
        return false;
    }
    if (isNaN(dakota.lng)) {
        return false;
    }
    const center = { lat: 18.4490849, lng: -77.2419522 };
    const options = { zoom: 15, scaleControl: true, center: center };
    map = new google.maps.Map(
        document.getElementById('map'), options);
    // get distance accouring to address
    // const dakota = {lat: 28.6623, lng: 77.1411};
    // const frick = {lat: 28.6280, lng: 77.3649};
    // The markers for The Dakota and The Frick Collection
    var mk1 = new google.maps.Marker({ position: dakota, map: map });
    var mk2 = new google.maps.Marker({ position: frick, map: map });
    // Draw a line showing the straight distance between the markers
    function haversine_distance(mk1, mk2) {
        var R = 3958.8; // Radius of the Earth in miles
        var rlat1 = mk1.position.lat() * (Math.PI / 180); // Convert degrees to radians
        var rlat2 = mk2.position.lat() * (Math.PI / 180); // Convert degrees to radians
        var difflat = rlat2 - rlat1; // Radian difference (latitudes)
        var difflon = (mk2.position.lng() - mk1.position.lng()) * (Math.PI / 180); // Radian difference (longitudes)
        var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
        return d;
    }
    // Calculate and display the distance between markers
    var distance = haversine_distance(mk1, mk2);
    // console.log(distance, 'distance');
    let directionsService = new google.maps.DirectionsService();
    let directionsRenderer = new google.maps.DirectionsRenderer();
    const route = {
        origin: dakota,
        destination: frick,
        travelMode: 'DRIVING'
    }
    directionsData = directionsService.route(route,
        function(response, status) { // anonymous function to capture directions
            if (status !== 'OK') {
                // window.alert('Directions request failed due to ' + status);
                // console.log('Directions request failed due to ' + status);
                $('#add_error').html('Address Is Not Valid !');

                return;
            } else {
                directionsRenderer.setDirections(response); // Add route to the map
                var directionsData = response.routes[0].legs[0]; // Get data about the mapped route
                if (!directionsData) {
                    $('#add_error').html('Address Is Not Valid !');

                    //   console.log('Directions request failed');
                    return;
                } else {
                    var delivery_crg = 0;
                    var str = directionsData.distance.text;
                    var diskm = str.replace("km", '');
                    var dis = parseFloat(diskm.replace(",", ''));
                    // console.log(dis, 'dis');
                    // console.log(directionsData.distance.text, 'directionsData.distance.text');
                    if (dis <= 10000) {
                        if (dis <= service_data.flat_km) {
                            // console.log(service_data.flat_rate, 'll');
                            var deliveryCharge = service_data.flat_rate;
                        } else if (dis > service_data.flat_km) {
                            var extra_km = dis - service_data.flat_km;
                            var deliveryCharge = service_data.flat_rate + extra_km * service_data.after_flat_rate;

                        } else {
                            $('#add_error').html('Address Is Not Valid !');

                        }
                    } else {
                        $('#add_error').html('No Nearby Restaurant Located !');

                    }
                    // var total_amount = total_amount.toFixed(2);

                    var total = deliveryCharge + total_amount;
                    var total = total.toFixed(2);
                    var deliveryCharge = deliveryCharge.toFixed(2);
                    // console.log(total_amount);
                    $('#delivery_charge').html(deliveryCharge);
                    $('#delivery_charge_input').val(deliveryCharge);
                    $("#total_amount").html(total);

                }
            }
        });

}