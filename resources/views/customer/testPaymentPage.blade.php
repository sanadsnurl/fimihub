@php
//     $json = ("http://maps.google.com/maps/nav?q=from:'Fern Court Apartment,Jamaica' to:'Jolly’s Restaurant, 11 W Kings House Rd, Kingston, Jamaica'");
//     $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $json);
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//         // Catch output (do NOT print!)
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


//         curl_exec($ch);
// dd($ch);

//         curl_close($ch);
$source_address = '18.4512188,-77.2441534';
$destination_address = '18.0210953,-76.7936676';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".str_replace(' ', '+', $source_address)."&destination=".str_replace(' ', '+', $destination_address)."&sensor=false&key=".Config('GOOGLE_MAPS_API_KEY');
           dd($url);
        $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_all = json_decode($response);
            $distance = $response_all->routes[0]->legs[0]->distance->text;
            dd($distance);


@endphp
