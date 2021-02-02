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
?>