<?php
$origin = $_GET['origin']; // Origin address from the form
$destination = $_GET['destination']; // Destination address from the form

$apiKey = 'AIzaSyDBT6_1gj-zGPI85qsCu5nADAKSYA6eJ40';
$originEncoded = urlencode($origin);
$destinationEncoded = urlencode($destination);

$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$originEncoded&destinations=$destinationEncoded&key=$apiKey";

$response = file_get_contents($url);

if ($response === false) {
    echo "Error fetching data from API.";
} else {
    $data = json_decode($response, true);

    if ($data && isset($data['status']) && $data['status'] == 'OK') {
        $distance = $data['rows'][0]['elements'][0]['distance']['text'];
        echo "Distance: $distance";
    } else {
        echo "Error calculating distance.";
    }
}
?>

