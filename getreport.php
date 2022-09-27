<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description: Simple PHP/MS Access/ODBC Attendance Report For Vigilance Fingerprint Time Clock VT300 Device (VAMS 3.7.1)

include "config.php";
?>
<script>
window.history.forward(1);
</script>
<?php
$selectedmonth = intval($_POST['selectedmonth']);
$selectedyear = intval($_POST['selectedyear']);
$selecteduser = $_POST['selecteduser'];

$month = "$selectedmonth";
$year = "$selectedyear";
$start_date = "01-".$month."-".$year;
$start_time = strtotime($start_date);
$end_time = strtotime("+1 month", $start_time);

$nextmonth1=Date("m", strtotime("" . $selectedyear . "-" . $selectedmonth . "-01" . " +1 month"));
if ($nextmonth1=='01')
	{
	$nextmonth=12;
	}
else
	{
	$nextmonth=Date("m", strtotime("" . $selectedyear . "-" . $selectedmonth . "-01" . " +1 month"));
	}
  
$month = "$selectedmonth";
$year = "$selectedyear";
$monthNum = $month;
$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));

$currentdate=date("Y-m-d");

if ($selectedmonth=='1')
{$mymonth='01';}
else if ($selectedmonth=='2')
{$mymonth='02';}
else if ($selectedmonth=='3')
{$mymonth='03';}
else if ($selectedmonth=='4')
{$mymonth='04';}
else if ($selectedmonth=='5')
{$mymonth='05';}
else if ($selectedmonth=='6')
{$mymonth='06';}
else if ($selectedmonth=='7')
{$mymonth='07';}
else if ($selectedmonth=='8')
{$mymonth='08';}
else if ($selectedmonth=='9')
{$mymonth='09';}
else if ($selectedmonth=='10')
{$mymonth='10';}
else if ($selectedmonth=='11')
{$mymonth='11';}
else if($selectedmonth=='12')
{$mymonth='12';}
else{}	

$selecteddate= "$selectedyear-$mymonth-01";

if ($selecteddate > $currentdate)
{
echo "There is no data yet for the selected month";
}
else{  
	
echo "<span style='text-transform:uppercase'><b>Staff Attendance Report</b></span><br>";

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

echo "<b>Name:</b> $name &nbsp;&nbsp;<b>IC Number:</b> $icnumber &nbsp;&nbsp;<b>Department:</b> $deptname <br>";

}
echo "<b>Month:</b> $monthName $selectedyear</center><br /><br />";


$month = "$selectedmonth";
$year = "$selectedyear";
$start_date = "01-".$month."-".$year;
$start_time = strtotime($start_date);
$end_time = strtotime("+1 month", $start_time);

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

} 


?>