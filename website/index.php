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
        google.charts.load('46', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart1);
        google.charts.setOnLoadCallback(drawChart2);

        function makeTable() {
            //make the top10 table
            var table = document.getElementById("top10Table");
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var myObj = JSON.parse(this.responseText);
                    i = 0;
                    while (i < myObj.length){
                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4);
                        var cell6 = row.insertCell(5);
                        var cell7 = row.insertCell(6);
                        cell1.innerHTML = 10 - i;
                        cell2.innerHTML = myObj[i].stn;
                        cell3.innerHTML = myObj[i].name;
                        cell4.innerHTML = myObj[i].country;
                        cell5.innerHTML = myObj[i].humidity + "%";
                        cell6.innerHTML = myObj[i].date;
                        cell7.innerHTML = myObj[i].time + ":00";
                        i++;
                    }
                }
            };
            xmlhttp.open("GET", "fetch_data/top10.php", true);
            xmlhttp.send();

        }

        function drawChart1() {
            //make the chart
            data = new google.visualization.DataTable(document.getElementById('line_top_x'));
            data.addColumn('number', 'Time in hours');
            data.addColumn('number', 'Weatherstation ....');
            data.addRows([[0,  0]]);

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
            chart.draw(data, google.charts.Line.convertOptions(options));
        }


        function drawChart2() {
            //make the chart right
            data2 = new google.visualization.DataTable(document.getElementById('line_top_x2'));
            data2.addColumn('number', 'Time in hours');
            data2.addColumn('number', 'Weatherstation ....');
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

            chart2 = new google.charts.Line(document.getElementById('line_top_x2'));
            chart2.draw(data2, google.charts.Line.convertOptions(options));
        }

        function initMap() {
            //make the maps
            var mapOptions = {
                zoom: 5,
                center: { lat: 53.1424984, lng: 7.0367877 },
                streetViewControl: false,
            }

            const map = new google.maps.Map(document.getElementById("map"), mapOptions)
            const map2 = new google.maps.Map(document.getElementById("map2"), mapOptions)

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var myObj = JSON.parse(this.responseText);
                    i = 0;
                    while (i < myObj.length) {
                        const locatie = new google.maps.Marker({
                            position: {lat: myObj[i].latitude, lng: myObj[i].longitude},
                            id: myObj[i].stn,
                            name: myObj[i].name,
                            country: myObj[i].country,
                            map: map,
                            title: myObj[i].name + " - " + myObj[i].country,
                        });
                        locatie.addListener("click", function () {
                            giveID(locatie, "")
                        });

                        const locatie2 = new google.maps.Marker({
                            position: {lat: myObj[i].latitude, lng: myObj[i].longitude},
                            id: myObj[i].stn,
                            name: myObj[i].name,
                            country: myObj[i].country,
                            map: map2,
                            title: myObj[i].name + " - " + myObj[i].country,
                        });
                        locatie2.addListener("click", function () {
                            giveID(locatie2, 2)
                        });
                        i++;
                    }
                }
            };
            xmlhttp.open("GET", "fetch_data/valid_stations.php", true);
            xmlhttp.send();

            function giveID(station, map) {
                //make the maps
                if (map === 2){
                    daysBackRight = 0;
                    data2.removeColumn(1);

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState === 4 && this.status === 200) {
                            var myObj = JSON.parse(this.responseText);
                            i = 0;
                            while (i < myObj.length){
                                data2.addRows([[myObj[i].time,  myObj[i].rainfall]]);
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
                        if (this.readyState === 4 && this.status === 200) {
                            var myObj = JSON.parse(this.responseText);
                            i = 0;
                            while (i < myObj.length){
                                data.addRows([[myObj[i].time,  myObj[i].rainfall]]);
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
                data.removeColumn(1);
                data.addColumn('number', name_selected + " - " + country_selected);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data.addRows([[myObj[i].time,  myObj[i].rainfall]]);
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
                data2.removeColumn(1);
                data2.addColumn('number', name_selected_right + " - " + country_selected_right);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data2.addRows([[myObj[i].time,  myObj[i].rainfall]]);
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
                data.removeColumn(1);
                data.addColumn('number', name_selected + " - " + country_selected);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data.addRows([[myObj[i].time,  myObj[i].rainfall]]);
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
                data2.removeColumn(1);
                data2.addColumn('number', name_selected_right + " - " + country_selected_right);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        var myObj = JSON.parse(this.responseText);
                        i = 0;
                        while (i < myObj.length){
                            data2.addRows([[myObj[i].time,  myObj[i].rainfall]]);
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
<body onload="makeTable()">
    <div id="wrapper">
        <!--Button for menu button-->
        <input type="checkbox" id="toggle">
        <!--Menu button-->
        <div id="header">
            <label for="toggle">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <!--Img in menu-->
            <img src="images/logo2.png" width="45" height="45">
        </div>

        <!--content (area with the graphs and table)-->
        <div id="content">
            <!--table with top 10 humidty country's-->
                <div class="top10">
                    <h1>Top 10 humidity &nbsp <i class="fas fa-tint" style="font-size: 28px"></i></h1>
                    <table id="top10Table">
                        <tr>
                            <th>Top</th>
                            <th>Nr</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Humidity</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </table>
                </div>

            <div class="grid">
                <!--Graph on the left side-->
                <div class="gl">
                    <h1>Graph rainfall #1 &nbsp <i class="fas fa-chart-line" style="font-size: 28px"></i></h1>
                    <div id="map"></div>
                    <div  id="line_top_x" style="width:100%"></div>
                    <button style="margin: 1em 1em 1em 2em" onclick="chartLeftBack();"><--</button>
                    <button style="margin: 1em 1em 1em 2em" onclick="chartLeftForward();">--></button>
                </div>
                <!--Graph on the right side-->
                <div class="gr">
                    <h1>Graph rainfall #2 &nbsp <i class="fas fa-chart-line" style="font-size: 28px"></i></h1>
                    <div id="map2"></div>
                    <div  id="line_top_x2" style="width:100%"></div>
                    <button style="margin: 1em 1em 1em 2em" onclick="chartRightBack();"><--</button>
                    <button style="margin: 1em 1em 1em 2em" onclick="chartRightForward();">--></button>
                </div>
            </div>
        </div>

        <!--Menu folded out-->
        <div id="menu">
            <!--List with menu items-->
            <ul>
                <li><img src="images/logo8.png" width="100%" height="100%"></li>
                    <li><a href="">Download Files &nbsp <i class="fas fa-file-download" style="font-size: 18px"></a></i></li>
                    <li><a href="login.php">Logout &nbsp <i class="fas fa-sign-out-alt" style="font-size: 18px"></i></a></li>
            </ul>
        </div>
    </div>
</body>
</html>
