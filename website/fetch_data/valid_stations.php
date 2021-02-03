<?php
// Starting a session if one has not yet been established
if (!isset($_SESSION)) {
    session_start();
}

// Checking if the logged_in session variable exists, this is created after logging in
if (!isset($_SESSION["logged_in"])) {
    header('Location: ../login.php');
}
$stations = array();
$filepath="../testdata/valid_stations.txt";
$file = fopen($filepath,"r");

while(! feof($file))
    {
        $regel = fgets($file). "<br />";
        $data = explode(';',$regel);
        $stn = (int)$data[0];
        $name = $data[1];
        $country = $data[2];
        $longitude = (double)$data[3];
        $latitude = (double)$data[4];
        array_push($stations, array("stn" => $stn,"name" => $name,"country" => $country,"longitude" => $longitude, "latitude" =>$latitude));
    }

fclose($file);
echo json_encode($stations);
?>