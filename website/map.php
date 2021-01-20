<!DOCTYPE html>
<html>
<head>
    <title>Test map</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
    <script type="text/javascript">
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            data = new google.visualization.DataTable();
            data.addColumn('number', 'Time in hours');
            data.addColumn('number', 'Weatherstation ....');

            data.addRows([
                [0,  37.8],
                [1,  30.9],
                [2,  30.9],
                [3,  25.4],
                [4,  11.7],
                [5,  11.9],
                [6,   8.8],
                [7,   7.6],
                [8,  12.3],
                [9,  16.9],
                [10, 12.8],
                [11,  5.3],
                [12,  6.6],
                [13,  4.8],
                [14,  4.2]
            ]);

            options = {
                chart: {
                    title: 'Neerslag',
                    subtitle: 'in mm)'
                },
                width: 900,
                height: 500,
                axes: {
                    x: {
                        0: {side: 'bottum'}
                    }
                }
            };
            chart = new google.charts.Line(document.getElementById('line_top_x'));
            chart.draw(data, google.charts.Line.convertOptions(options));
        }

        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: { lat: 53.1424984, lng: 7.0367877 },
            });
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                var myObj = JSON.parse(this.responseText);
                i = 0;
                while (i < myObj.weatherstations.length){
                    const locatie = new google.maps.Marker({
                        position: { lat: myObj.weatherstations[i].latitude, lng: myObj.weatherstations[i].longitude },
                        id: myObj.weatherstations[i].stn,
                        name: myObj.weatherstations[i].name,
                        country: myObj.weatherstations[i].country,
                        map,
                        title: "Weather station!",
                    });
                    locatie.addListener("click", function () {giveID(locatie)});
                    i++;
                }
            };
            xmlhttp.open("GET", "data.json", true);
            xmlhttp.send();

            function giveID(station) {
                document.getElementById("selected").innerHTML = station.id;
                data.removeColumn(0);
                data.addColumn('number', station.name + " - " +station.country);
                chart = new google.charts.Line(document.getElementById('line_top_x'));
                chart.draw(data, google.charts.Line.convertOptions(options));
            }
        }
    </script>
</head>
<body>
<p id="selected"></p>
<div id="map"></div>
<div id="tabel">
    <h1>Tabel</h1>
    <div id="line_top_x"></div>

</div>
</body>
</html>