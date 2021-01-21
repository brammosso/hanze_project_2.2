<?php
/**
 * @file index.php
 *
 * @brief This is the main page of the website.
 */

// Starting a session if one has not yet been established
if (!isset($_SESSION)) {
    session_start();
}

// Checking if the logged_in session variable exists, this is created after logging in
if (!isset($_SESSION["logged_in"])) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Osaka Univsersity Dashboard</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPvkYxdvvVADFAfljO3DY6cwn9nbA4G8Q&callback=initMap&libraries=&v=weekly" defer></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript">
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            data = new google.visualization.DataTable(document.getElementById('test'));
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
<div id="wrapper">
    <!--knop voor menu button-->
    <input type="checkbox" id="toggle">
    <!--menu button-->
    <div id="header">
        <label for="toggle">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <!--img in menu balk-->
        <img src="images/logo2.png" width="45" height="45">
    </div>

    <!--content (wit)-->
    <div id="content">
        <div class="grid">
            <div class="top10">
                <h1>Top 10 humidity</h1>
                "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
            </div>
            <div class="weatherinfo">
                <h1>Weather info table</h1>
                "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
            </div>
            <div class="gl">
                <h1>Graph LO</h1>
                <p id="selected"></p>
                <div id="map"></div>
                <div id= "test" style="width:100%;"><div id="line_top_x"></div></div>
            </div>
            <div class="gr">
                <h1>Graph RO</h1>
                "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
            </div>
        </div>
    </div>
    <!--menu uitgeklapt-->
    <div id="menu">
        <!--lijst met menu items-->
        <ul>
            <li><img src="images/logo8.png" width="100%" height="100%"></li>
            <li><a href="">Download Files</a></i></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </div>
</div>
</body>
</html>
