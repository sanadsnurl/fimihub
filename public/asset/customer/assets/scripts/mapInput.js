function initialize() {

    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    const locationInputs = document.getElementsByClassName("map-input");
    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        // const fieldKey = input.id.replace("-input", "");
        const fieldKey = input.getAttribute("data-id").replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || 18.4490849;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || -77.2419522;

        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: { lat: latitude, lng: longitude },
            zoom: 13
        });
        const marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: { lat: latitude, lng: longitude },
            travelMode: 'DRIVING'

        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.key = fieldKey;
        autocompletes.push({ input: input, map: map, marker: marker, autocomplete: autocomplete });

        // get lat and lng on marker drag
        google.maps.event.addListener(marker, 'dragend', function(evt) {
            $('#address-latitude').val(evt.latLng.lat());
            $('#address-longitude').val(evt.latLng.lng());
        });
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            geocoder.geocode({ 'placeId': place.place_id }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    setLocationCoordinates(autocomplete.key, lat, lng);
                }
            });

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

        });
    }

}

function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}



// jfldsk===


function showPosition() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            var loc = geocodeLatLng(latitude, longitude)
        });
    } else {
        let str = 'location not found';
        document.getElementById('result').setAttribute('title', str);
    }
}

let latVal;
let longVal;

function geocodeLatLng(latitude, longitude) {
    const geocoder = new google.maps.Geocoder();
    const infowindow = new google.maps.InfoWindow();
    const latlng = {
        lat: latitude,
        lng: longitude,
    };

    latVal = latlng.lat;
    longVal = latlng.lng;

    geocoder.geocode({ location: latlng }, (results, status) => {
        if (status === "OK") {
            if (results[0]) {
                infowindow.setContent(results[0].formatted_address);
                let str = results[0].formatted_address;
                let strRes = str.slice(0, 22) + '...';
                document.cookie = "lat =" + latitude;
                document.cookie = "long =" + longitude;
                document.getElementById('result').innerHTML = strRes;
                document.getElementById('result').setAttribute('title', str);
            } else {
                let str = 'location not found';
                document.getElementById('result').setAttribute('title', str);
            }
        } else {
            let str = 'location not found';
            document.getElementById('result').setAttribute('title', str);
        }
    });
}

$('.show_address').click(function() {
    let res = $('#result').attr('title');
    if (latVal && longVal && res) {
        $('.sidebar_addrss_box .map-input').val(res);
        $('#address-latitude').val(latVal);
        $('#address-longitude').val(longVal);
        initialize();
    } else {
        alert('Location not find');
    }
})

var searchInput = $(".banner .search-bar .location-selector input#location-input");

$(".banner .save_adrs").on("submit", function() {
    let langInput = $(".banner .search-bar  #location-longitude");
    let latInput = $(".banner .search-bar #location-latitude");
    if ((langInput.val() == 0 && latInput.val() == 0) && (searchInput.val().length !== 0)) {
        $(".banner .address_box_dyn").addClass("invalid");
        return false
    } else {
        $(".banner .address_box_dyn").removeClass("invalid");
    }
})