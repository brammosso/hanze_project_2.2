<!DOCTYPE html>
<html>
<head>
    <title>Test map</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPvkYxdvvVADFAfljO3DY6cwn9nbA4G8Q&callback=initMap&libraries=&v=weekly" defer></script>
    <style type="text/css">
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <script>
        function initMap() {
            const weatherstation1 = { lat: 53.1424984, lng: 7.0367877 };
            const weatherstation2 = { lat: 52.992753, lng: 6.5642284 };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: weatherstation1,
            });
            test1 = new google.maps.Marker({
                position: weatherstation1,
                id: 8888,
                map,
                title: "Weather station1!",
            });
            test1.addListener("click", function () {giveID(test1)});
            test = new google.maps.Marker({
                position: weatherstation2,
                id: 1920,
                map,
                title: "Weather station2!",
            });
            test.addListener("click", function () {giveID(test)});

            var i = 0;
            while (i < 10) {
                a = 53.1763506 + i;
                b = i;
                const locatie = new google.maps.Marker({
                    position: { lat: a, lng: 6.9723944 },
                    id: i,
                    map,
                    title: "Weather station!",
                });
                locatie.addListener("click", function () {giveID(locatie)});
                i++;
            }

            function giveID(station) {
                document.getElementById("selected").innerHTML = station.id;
            }
        }
    </script>
</head>
<body>
<p id="selected"></p>
<div id="map"></div>
</body>
</html>