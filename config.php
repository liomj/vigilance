<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description: Simple PHP/MS Access/ODBC Attendance Report For Vigilance Fingerprint Time Clock VT300 Device (VAMS 3.7.1)


//Configuration
$starting_year = '2021'; // Starting Year in index.php dropdown
$system_dsn ='myattendance2'; // System DSN - Data Source Name MS Access Database
$access_dbuser=''; // MS Access Database User. Leave Blank if empty
$access_dbpassword=''; // MS Access Database Password

//ODBC Connection
$conn=odbc_connect($system_dsn,$access_dbuser,$access_dbpassword);
if (!$conn)
  {exit("Connection Failed: " . $conn);}

//End Configuration

?>