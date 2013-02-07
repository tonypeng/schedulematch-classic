<!DOCTYPE HTML>
<html>
<head>
<title>Schedule Match</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div id="page">
<div id="banner"><a href="index.php" class="blacklink">Schedule Match</a></div>
<br />
<?php
if(isset($_GET['submit']))
{
    require_once('config.php');
    
    $teacher = strtolower(mysql_real_escape_string($_GET['teacher']));
    
    if(!is_numeric($_GET['period']))
        die('Period needs to be a number!');
    
    $period = mysql_real_escape_string($_GET['period']);
    
    $query = mysql_query("SELECT * FROM `classes` WHERE teacher='$teacher' AND period=$period") or die(mysql_error());
?>
<div style="margin: 0 auto; width: 516px;">  
<div style="border:  1px solid #000; width: 514px;"><?php echo $teacher . " - Period " . $period; ?></div>
<table border="1">
<?php
    while($row = mysql_fetch_array($query))
    {
        $row2 = mysql_fetch_array(mysql_query("SELECT * FROM `submissions` WHERE id=".$row['submission_id']));
        $name = $row2['first'] . ' ' . $row2['last'];
        $grade = $row2['grade'];
?>
        <tr style="width: 400px;"><td style="min-width: 400px;"><a href="schedule.php?id=<?php echo $row2['id'] ?>"><?php echo $name; ?></a></td><td style="min-width: 100px;"><?php echo $grade; ?></td></tr>
<?php
    }
?>
</table>
</div>
<?php
}
else
{
?>
<em>Teacher names should be spelled exactly as on the schedule paper (case does not matter). Please leave out any salutations such as "Mr", "Mrs", or "Ms".</em><br />
<form method="GET" action="">
Teacher name: <input type="text" name="teacher" value="" /><br />
Period: <input type="text" name="period" value="" /><br />
<br />
<input type="submit" name="submit" value="Submit" />
</form>
<?php
}
?>
<br />
<div id="footer">Copyright &copy 2012 Tony Peng.  All rights are reserved.<br />For security and analytical purposes, your IP (<?php echo $_SERVER['REMOTE_ADDR']; ?>) is being logged.</div>
</div>
</body>
</html>