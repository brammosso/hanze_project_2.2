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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">

    <script type="text/javascript">
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            data = new google.visualization.DataTable(document.getElementById('line_top_x'));
            data2 = new google.visualization.DataTable(document.getElementById('line_top_x2'));
            data.addColumn('number', 'Time in hours');
            data2.addColumn('number', 'Time in hours');
            data.addColumn('number', 'Weatherstation ....');
            data2.addColumn('number', 'Weatherstation ....');
            data.addRows([[0,  0]]);
            data2.addRows([[0,  0]]);

            options = {
                chart: {
                    title: 'Rainfall',
                    subtitle: 'Rainfall in mm',
                },
                height: 500,
                axes: {
                    x: {
                        0: {side: 'bottom'}
                    }
                }
            };
            chart = new google.charts.Line(document.getElementById('line_top_x'));
            chart2 = new google.charts.Line(document.getElementById('line_top_x2'));
            chart.draw(data, google.charts.Line.convertOptions(options));
            chart2.draw(data2, google.charts.Line.convertOptions(options));
        }

        function initMap() {
            var mapOptions = {
                zoom: 5,
                center: { lat: 53.1424984, lng: 7.0367877 },
                streetViewControl: false,
            }

            const map = new google.maps.Map(document.getElementById("map"), mapOptions)
            const map2 = new google.maps.Map(document.getElementById("map2"), mapOptions)

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                var myObj = JSON.parse(this.responseText);
                i = 0;
                while (i < myObj.length){
                    const locatie = new google.maps.Marker({
                        position: { lat: myObj[i][3], lng: myObj[i][4] },
                        id: myObj[i][0],
                        name: myObj[i][1],
                        country: myObj[i][2],
                        map: map,
                        title: myObj[i][1] + " - " + myObj[i][2],
                    });
                    locatie.addListener("click", function () {giveID(locatie, "")});

                    const locatie2 = new google.maps.Marker({
                        position: { lat: myObj[i][3], lng: myObj[i][4] },
                        id: myObj[i][0],
                        name: myObj[i][1],
                        country: myObj[i][2],
                        map: map2,
                        title: myObj[i][1] + " - " + myObj[i][2],
                    });
                    locatie2.addListener("click", function () {giveID(locatie2, 2)});
                    i++;
                }
            };
            xmlhttp.open("GET", "fetch_data/valid_stations.php", true);
            xmlhttp.send();

            function giveID(station, map) {
                if (map == 2){
                    daysBackRight = 0;
                    data2.removeColumn(1);
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var myObj = JSON.parse(this.responseText);
                            i = 0;
                            while (i < myObj.length){
                                data2.addRows([[myObj[i][0],  myObj[i][1]]]);
                                i++;
                            }
                        }
                    };
                    xmlhttp.open("GET", "fetch_data/chart.php?station="+station.id+"&back=0", true);
                    xmlhttp.send();
                    id_selected_right = station.id;
                    name_selected_right = station.name;
                    country_selected_right = station.country;
                    data2.addColumn('number', station.name + " - " +station.country);
                    chart2 = new google.charts.Line(document.getElementById('line_top_x2'));
                    chart2.draw(data2, google.charts.Line.convertOptions(options));
                }
                else{
                    daysBackLeft = 0;
                    data.removeColumn(1);
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var myObj = JSON.parse(this.responseText);
                            i = 0;
                            while (i < myObj.length){
                                data.addRows([[myObj[i][0],  myObj[i][1]]]);
                                i++;
                            }
                        }
                    };
                    xmlhttp.open("GET", "fetch_data/chart.php?station="+station.id+"&back=0", true);
                    xmlhttp.send();
                    id_selected = station.id;
                    name_selected = station.name;
                    country_selected = station.country;
                    data.addColumn('number', station.name + " - " +station.country);

                    chart = new google.charts.Line(document.getElementById('line_top_x'));
                    chart.draw(data, google.charts.Line.convertOptions(options));
                }
            }


        }
        function chartLeftBack(){
            if (daysBackLeft > 1){
                alert("You can't go back any further");
            }
            else{
                daysBackLeft ++;
                data = new google.visualization.DataTable(document.getElementById('line_top_x'));
                data.addColumn('number', 'Time in hours');
                data.addColumn('number', name_selected + " - " + country_selected);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data.addRows([[myObj[i][0],  myObj[i][1]]]);
                            i++;
                        }
                    }
                };
                xmlhttp.open("GET", "fetch_data/chart.php?station="+id_selected+"&back=" +daysBackLeft, true);
                xmlhttp.send();

                chart = new google.charts.Line(document.getElementById('line_top_x'));
                chart.draw(data, google.charts.Line.convertOptions(options));
            }

        }

        function chartRightBack(){
            if (daysBackRight > 1){
                alert("You can't go back any further");
            }
            else{
                daysBackRight ++;
                data2 = new google.visualization.DataTable(document.getElementById('line_top_x2'));
                data2.addColumn('number', 'Time in hours');
                data2.addColumn('number', name_selected_right + " - " + country_selected_right);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data2.addRows([[myObj[i][0],  myObj[i][1]]]);
                            i++;
                        }
                    }
                };
                xmlhttp.open("GET", "fetch_data/chart.php?station="+id_selected_right+"&back=" +daysBackRight, true);
                xmlhttp.send();

                chart2 = new google.charts.Line(document.getElementById('line_top_x2'));
                chart2.draw(data2, google.charts.Line.convertOptions(options));
            }
        }

        function chartLeftForward(){
            if (daysBackLeft < 1){
                alert("You can't days forward");
            }
            else{
                daysBackLeft--;
                data = new google.visualization.DataTable(document.getElementById('line_top_x'));
                data.addColumn('number', 'Time in hours');
                data.addColumn('number', name_selected + " - " + country_selected);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data.addRows([[myObj[i][0],  myObj[i][1]]]);
                            i++;
                        }
                    }
                };
                xmlhttp.open("GET", "fetch_data/chart.php?station="+id_selected+"&back=" +daysBackLeft, true);
                xmlhttp.send();

                chart = new google.charts.Line(document.getElementById('line_top_x'));
                chart.draw(data, google.charts.Line.convertOptions(options));
            }
        }

        function chartRightForward(){
            if (daysBackRight < 1){
                alert("You can't days forward");
            }
            else{
                daysBackRight--;
                data2 = new google.visualization.DataTable(document.getElementById('line_top_x2'));
                data2.addColumn('number', 'Time in hours');
                data2.addColumn('number', name_selected_right + " - " + country_selected_right);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data2.addRows([[myObj[i][0],  myObj[i][1]]]);
                            i++;
                        }
                    }
                };
                xmlhttp.open("GET", "fetch_data/chart.php?station="+id_selected_right+"&back=" +daysBackRight, true);
                xmlhttp.send();

                chart2 = new google.charts.Line(document.getElementById('line_top_x2'));
                chart2.draw(data2, google.charts.Line.convertOptions(options));
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

            <div class="top10">
                <h1>Top 10 humidity &nbsp <i class="fas fa-tint" style="font-size: 28px"></i></h1>
                <table>
                    <tr>
                        <th>Top</th>
                        <th>Nr</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Humidity</th>
                        <th>Datum</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>62800</td>
                        <td>Groningen AP Eelde</td>
                        <td>Netherlands</td>
                        <td>90%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>62700</td>
                        <td>Leeuwarden</td>
                        <td>Netherlands</td>
                        <td>80%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>10260</td>
                        <td>TROMSO/LANGNES</td>
                        <td>NORWAY</td>
                        <td>70%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>22820</td>
                        <td>PETISTRASK</td>
                        <td>SWEDEN</td>
                        <td>60%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>100330</td>
                        <td>GLUECKSBURG/MEIERWI</td>
                        <td>GERMANY</td>
                        <td>50%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>995930</td>
                        <td>ENVIRONM BUOY 62104</td>
                        <td>UNITED KINGDOM</td>
                        <td>40%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>160610</td>
                        <td>TORINO/BRIC DELLA C</td>
                        <td>ITALY</td>
                        <td>30%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>143140</td>
                        <td>MALI LOSINJ</td>
                        <td>CROATIA</td>
                        <td>20%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>130240</td>
                        <td>LISCA</td>
                        <td>SLOVENIA</td>
                        <td>10%</td>
                        <td>26-01-2021</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>75490</td>
                        <td>AURILLAC</td>
                        <td>FRANCE</td>
                        <td>5%</td>
                        <td>26-01-2021</td>
                    </tr>
                </table>
            </div>

        <div class="grid">
            <div class="gl">
                <h1>Graph LO &nbsp <i class="fas fa-chart-line" style="font-size: 28px"></i></h1>
                <div id="map"></div>
                <div  id="line_top_x" style="width:100%"></div>
                <button style="margin: 1em 1em 1em 2em" onclick="chartLeftBack();"><--</button>
                <button style="margin: 1em 1em 1em 2em" onclick="chartLeftForward();">--></button>
            </div>
            <div class="gr">
                <h1>Graph RO &nbsp <i class="fas fa-chart-line" style="font-size: 28px"></i></h1>
                <div id="map2"></div>
                <div  id="line_top_x2" style="width:100%"></div>
                <button style="margin: 1em 1em 1em 2em" onclick="chartRightBack();"><--</button>
                <button style="margin: 1em 1em 1em 2em" onclick="chartRightForward();">--></button>
            </div>
        </div>
    </div>
    <!--menu uitgeklapt-->
    <div id="menu">
        <!--lijst met menu items-->
        <ul>
            <li><img src="images/logo8.png" width="100%" height="100%"></li>
                <li><a href="">Download Files &nbsp <i class="fas fa-file-download" style="font-size: 18px"></a></i></li>
                <li><a href="login.php">Logout &nbsp <i class="fas fa-sign-out-alt" style="font-size: 18px"></i></a></li>

        </ul>
    </div>
</div>
</body>
</html>
