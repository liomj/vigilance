<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description: Simple PHP/ODBC Attendance Report For Vigilance Fingerprint Time Clock VT300 Device (VAMS 3.7.1)

echo "<html><head>";
echo "<title>Staff Attendance Report</title>";
echo "<meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' 
crossorigin='anonymous'>";

echo "</head><body>";


//Configuration
$selectedmonth = '06';
$selectedyear = '2022';
$selecteduser = ''; // Staff IC Number 
$systemdsn =''; // System DSN - Data Source Name
$accessdbpassword=''; // MS Access Database Password 
//End Configuration

$monthName = date("F", mktime(0, 0, 0, $selectedmonth, 10));
echo "<div class='container'>";
echo "<span style='text-transform:uppercase'><b>Staff Attendance Report (Vigilance Fingerprint Time Clock VT300 Device - VAMS 3.7.1)</b></span>";
echo "<br /><b>Month:</b> $monthName $selectedyear</center><br />";

//ODBC Connection
$conn=odbc_connect($systemdsn,'',$accessdbpassword);
if (!$conn)
  {exit("Connection Failed: " . $conn);}

// User Info Query
$userquery="SELECT userinfo.USERID,userinfo.Name,userinfo.DEFAULTDEPTID,userinfo.SSN,department.DEPTNAME,department.DEPTID FROM USERINFO AS userinfo INNER JOIN DEPARTMENTS AS department ON userinfo.DEFAULTDEPTID=department.DEPTID WHERE userinfo.SSN='$selecteduser'";

$rs=odbc_exec($conn,$userquery);
if (!$rs)
  {exit("Error in SQL");}

while (odbc_fetch_row($rs))
{
  $userid=odbc_result($rs,"USERID");
  $name=odbc_result($rs,"Name");
  $userdeptid=odbc_result($rs,"DEFAULTDEPTID");
  $icnumber=odbc_result($rs,"SSN");
  $deptname=odbc_result($rs,"DEPTNAME");

echo "<b>Name:</b> $name &nbsp;&nbsp;<b>IC Number:</b> $icnumber &nbsp;&nbsp;<b>Department:</b> $deptname <br><br>";

}
echo "</div>";

$month = "$selectedmonth";
$year = "$selectedyear";
$start_date = "01-".$month."-".$year;
$start_time = strtotime($start_date);
$end_time = strtotime("+1 month", $start_time);

echo "<div class='container'>";
echo "<table class='table table-hover table-bordered table-striped'>";
echo "<thead><tr>";
echo "<th width='150'><b>Date</b></th>";
echo "<th><b>Attendance Log </b></th>";

echo "</tr></thead><tbody>";

//start for loop
for($i=$start_time; $i<=$end_time; $i+=86400)
{
$monthlydate = date('d M Y D', $i);
echo "<tr><td>";
echo $monthlydate;
echo "</td>";

// Check Time Query
$checktimequery="SELECT CHECKTIME,USERID FROM CHECKINOUT WHERE USERID=$userid AND YEAR(CHECKTIME) = '$selectedyear' ORDER BY CHECKTIME ASC";
$rs2=odbc_exec($conn,$checktimequery);
if (!$rs2)
  {exit("Error in SQL");} 

echo "<td>";
 while (odbc_fetch_row($rs2))
{ 

$monthlydate2=date('j.n.Y',$i);
$checktime=odbc_result($rs2,"checktime");
$checktime_format = date('j.n.Y', strtotime($checktime)); 
$time = date('g:ia', strtotime($checktime)); 

if ($monthlydate2==$checktime_format)
{
  echo "$time&nbsp;&nbsp;";
}

}
echo "</td>";
echo "</tr>";

} // end for Loop 

echo "</tbody></table>";

echo "</div>";
echo "</body></html>"; 

?>


