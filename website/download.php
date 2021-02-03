<?php
$archive_file_name = "data.zip";
$file_paths = array("fetch_data/humidity.xml", "fetch_data/rainfall_left.xml", "fetch_data/rainfall_right.xml");
$file_names = array("humidity.xml", "rainfall_left.xml", "rainfall_right.xml");


function zipFilesAndDownload($file_paths, $archive_file_name) {
    $zip = new ZipArchive();
    //create the file and throw the error if unsuccessful
    if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
        exit("cannot open <$archive_file_name>\n");
    }
    //add each files of $file_name array to archive
    $counter = 0;
    foreach($file_paths as $file)
    {
        $zip->addFile($file,$file_names[$counter]);
        $counter++;

    }
    $zip->close();
    //then send the headers to force download the zip file
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=$archive_file_name");
	header("Content-length: " . filesize($archive_file_name));
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	readfile("$archive_file_name");
    exit;
}

zipFilesAndDownload($file_paths, $archive_file_name);

?>