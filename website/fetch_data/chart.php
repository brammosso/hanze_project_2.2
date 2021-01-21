<?php
$data = array();
$station = $_GET['station'];
$back = $_GET['back'];


$date = substr(date("d_m_Y",strtotime("-{$back} day")),0,6);
$date.= substr(date("d_m_Y",strtotime("-{$back} day")),8,2);

$filepath="../testdata/";
$filepath.= $date;
$filepath.= "/";
$filepath.=$station;
$filepath.="/data.txt";

if ( file_exists($filepath) ) {
    $file = fopen($filepath,"r");

    while(! feof($file))
    {
        $stringfrom = fgets($file). "<br />";
        $tijd = (int)substr($stringfrom,0,2);
        $neerslag = (int)substr($stringfrom,10,4);
        array_push($data, array($tijd,$neerslag));
    }

    fclose($file);
    echo json_encode($data);
}
else{

}

?>