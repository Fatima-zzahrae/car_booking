var map;
var autocomplete1;
var autocomplete2;

function clearAddressesAndMap() {
    document.getElementById('from').value = '';
    document.getElementById('to').value = '';

    autocomplete1.set('place', null);
    autocomplete2.set('place', null);

    clearMap();
}

function clearMap() {
    var directionsRenderer = new google.maps.DirectionsRenderer({
        map: map
    });
    directionsRenderer.set('directions', null);

    document.getElementById('distance').innerHTML = '';
}

function initMap() {
     // Check if geolocation is supported by the browser
     if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLatLng = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: userLatLng,
                zoom: 15
            });

            // Display the user's current location as a blue circle
            new google.maps.Marker({
                position: userLatLng,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 6, // size of the circle
                    fillColor: 'blue',
                    fillOpacity: 1,
                    strokeWeight: 1, 
                }
            });

            initAutocomplete(); // Initialize autocomplete after the map is created
        }, function() {
            // If geolocation fails, set a default location
            var defaultLatLng = { lat: -34.397, lng: 150.644 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLatLng,
                zoom: 8
            });

            initAutocomplete(); // Initialize autocomplete after the map is created
        }, { enableHighAccuracy: true }); 
    } else {
        // If geolocation is not supported, set a default location
        var defaultLatLng = { lat: -34.397, lng: 150.644 };

        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLatLng,
            zoom: 8
        });

        initAutocomplete(); // Initialize autocomplete after the map is created
    }
}

function initAutocomplete() {
    autocomplete1 = new google.maps.places.Autocomplete(document.getElementById('from'));
    autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('to'));
}

function calculateDistance() {
    var origin = document.getElementById('from').value;
    var destination = document.getElementById('to').value;
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer({
        map: map
    });
    var request = {
        origin: origin,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);
        }
    });

    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
        origins: [origin],
        destinations: [destination],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
    }, function(response, status) {
        if (status == 'OK') {
            var distance = response.rows[0].elements[0].distance.value/1000;
            var formattedDistance = distance.toFixed(2); // Format to 2 decimal places
            document.getElementById('distance').innerHTML = 'Distance: ' + formattedDistance + ' km';

            // Calculate cost based on the distance value
            var distanceValue = response.rows[0].elements[0].distance.value; // in meters
            var cost = calculateCost(distanceValue / 1000); // Convert to kilometers
            var formattedCost = cost.toFixed(2); // Format to 2 decimal places

            // Display the calculated cost
            document.getElementById('cost').innerHTML = 'Estimated cost: ' + formattedCost + ' $';
           
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText); 
                }
            };
            xhr.open("GET", "distance.php?origin=" + encodeURIComponent(origin) + "&destination=" + encodeURIComponent(destination), true);

            xhr.send();
        } else {
            alert('Error: ' + status);
        }
    });
}

function calculateCost(distance) {
    var baseFare = 1.0;
    var ratePerKilometer = 0.5;

    var cost = baseFare + (distance * ratePerKilometer);

    return cost;
}

function useCurrentLocation() {
    // Check if geolocation is supported by the browser
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLatLng = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: userLatLng }, function(results, status) {
                if (status === "OK" && results[0]) {
                    var userAddress = results[0].formatted_address;
                    document.getElementById('from').value = userAddress;
                }
            });
        }, function() {
            alert('Error getting current location.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function requestRide(passengerId) {
            var origin = document.getElementById('from').value;
            var destination = document.getElementById('to').value;

            // Calculate distance and cost
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [origin],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false, 
                avoidTolls: false
            }, function(response, status) {
                if (status == 'OK') {
                    var distanceValue = response.rows[0].elements[0].distance.value/1000; 
                    var cost = calculateCost(distanceValue); 


                    var formattedDistance = distanceValue.toFixed(2);
                    var formattedCost = cost.toFixed(2);
                    sendRideRequest(passengerId, origin, destination, formattedDistance, formattedCost);
                } else {
                    alert('Error calculating distance.');
                }
            });
        }



function sendRideRequest(passengerId, departure, arrival, distance, cost) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            window.location.href = "ride_history.php";
            document.getElementById('requestRideButton').disabled = true;
        }
    };
    xhr.open("POST", "save_ride_request.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(
        "passengerId=" + passengerId +
        "&from=" + encodeURIComponent(departure) +
        "&to=" + encodeURIComponent(arrival) +
        "&distance=" + distance +
        "&cost=" + cost
    );
}


