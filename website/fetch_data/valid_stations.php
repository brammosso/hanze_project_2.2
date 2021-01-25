<?php
$stations = array();

$filepath="../testdata/valid_stations.txt";
$file = fopen($filepath,"r");

while(! feof($file))
    {
        $regel = fgets($file). "<br />";
        $data = explode(',',$regel);
        $stn = (int)$data[0];
        $name = $data[1];
        $country = $data[2];
        $latitude = (double)$data[3];
        $longitude = (double)$data[4];
        //echo $longitude;
        array_push($stations, array($stn,$name,$country,$latitude,$longitude));
    }

    fclose($file);
    echo json_encode($stations);

?>