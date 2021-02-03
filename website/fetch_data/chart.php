<?php

// Starting a session if one has not yet been established
if (!isset($_SESSION)) {
    session_start();
}

// Checking if the logged_in session variable exists, this is created after logging in
if (!isset($_SESSION["logged_in"])) {
    header('Location: ../login.php');
}

$data = array();
$station = sprintf("%06d", $_GET['station']);
$back = $_GET['back'];
$chart = $_GET['chart'];
$date = date("Y-m-d",strtotime("-{$back} day"));
$filepath="../testdata/rainfall/";
$filepath.= $date;
$filepath.= "/";
$filepath.=$station;
$filepath.=".txt";

if ( file_exists($filepath) ) {
    $file = fopen($filepath,"r");

    while(! feof($file))
    {
        $stringfrom = fgets($file). "<br />";
        if (strlen($stringfrom > 0)){
            $tijd = (int)substr($stringfrom,0,2);
            $neerslag = (int)((double)substr($stringfrom,2,6)*100);
            array_push($data,array("time" => $tijd, "rainfall" => $neerslag));
        }
    }

    fclose($file);
    echo json_encode($data);
}

function generateXML($data, $chart) {
    if ($chart == 0) {
        $title = "Rainfall left";
        $datatypes = array("hour", "rainfall_in_mm");
        $xmlDoc = new DOMDocument();
        $root = $xmlDoc -> appendChild($xmlDoc -> 
                                createElement("data"));
        $tabRainfalls = $root -> appendChild($xmlDoc -> 
                                createElement('rows')); 
        foreach ($data as $value) {
            if(!empty($value)) {
                $tabRainfall = $tabRainfalls -> appendChild($xmlDoc ->  
                                  createElement('rainfall')); 
                $counter = 0;
                foreach ($value as $key) {
                    $tabRainfall -> appendChild($xmlDoc -> 
                                      createElement($datatypes[$counter], $key)); 
                    $counter++;
                }
            }
        }
        header("Content-Type: text/plain"); 
        $xmlDoc -> formatOutput = true; 
        $file_name = str_replace(' ', '_', "rainfall_left") . '.xml';
        $xmlDoc -> save($file_name); 
        return $file_name; 
    } else {
        $title = "Rainfall right";
        $datatypes = array("hour", "rainfall_in_mm");
        $xmlDoc = new DOMDocument();
        $root = $xmlDoc -> appendChild($xmlDoc -> 
                                createElement("data"));
        $tabRainfalls = $root -> appendChild($xmlDoc -> 
                                createElement('rows')); 
        foreach ($data as $value) {
            if(!empty($value)) {
                $tabRainfall = $tabRainfalls -> appendChild($xmlDoc ->  
                                  createElement('rainfall')); 
                $counter = 0;
                foreach ($value as $key) {
                    $tabRainfall -> appendChild($xmlDoc -> 
                                      createElement($datatypes[$counter], $key)); 
                    $counter++;
                }
            }
        }
        header("Content-Type: text/plain"); 
        $xmlDoc -> formatOutput = true; 
        $file_name = str_replace(' ', '_', "rainfall_right") . '.xml';
        $xmlDoc -> save($file_name); 
        return $file_name;
    }
}

generateXML($data, $chart);
?>