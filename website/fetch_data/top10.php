<?php
// Starting a session if one has not yet been established
if (!isset($_SESSION)) {
    session_start();
}

// Checking if the logged_in session variable exists, this is created after logging in
if (!isset($_SESSION["logged_in"])) {
    header('Location: ../login.php');
}

function generateXML($stations) {
    $title = "Humidity data";
    $datatypes = array("time", "date", "nr", "humidity", "name", "country", "latitude", "longitude");
    $xmlDoc = new DOMDocument();
    $root = $xmlDoc -> appendChild($xmlDoc -> 
                            createElement("data"));
    $tabHumiditys = $root -> appendChild($xmlDoc -> 
                            createElement('rows')); 
    foreach ($stations as $station) {
        if(!empty($station)) {
            $tabHumidity = $tabHumiditys -> appendChild($xmlDoc ->  
                              createElement('humidity')); 
            $counter = 0;
            foreach ($station as $data) {
                $tabHumidity -> appendChild($xmlDoc -> 
                                  createElement($datatypes[$counter], $data)); 
                $counter++;
            }
        }
    }
    header("Content-Type: text/plain"); 
    $xmlDoc -> formatOutput = true; 
    $file_name = str_replace(' ', '_', "humidity") . '.xml';
    $xmlDoc -> save($file_name); 
    return $file_name; 
}

$stations = array();
$filepath="../testdata/humidity_top10.txt";
$file = fopen($filepath,"r");

while(! feof($file))
{
    $regel = fgets($file). "<br />";
    if (strlen($regel > 0)){
    $data = explode(';',$regel);
    $dataweer = $data[0];
    $time = substr($dataweer,0,2);
    $day = substr($dataweer,2,2);
    $month = substr($dataweer,4,2);
    $year = substr($dataweer,6,2);
    $date = (string)$day ."-" .(string)$month . "-20" .(string)$year;
    $stn = substr($dataweer,8,6);
    $humidity = substr($dataweer,14,6);
    $name = $data[1];
    $country = $data[2];
    $latitude = (double)$data[3];
    $longitude = (double)$data[4];
    array_push($stations, array("time" => $time,"date" => $date, "stn" => $stn,"humidity" => $humidity, "name" => $name, "country" => $country, "latitude" => $latitude, "longitude" => $longitude));
}}

fclose($file);
echo json_encode($stations);
generateXML($stations);

?>