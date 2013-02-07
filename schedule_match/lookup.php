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
require_once('config.php');

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $query = mysql_query("SELECT * FROM `classes` WHERE submission_id=$id ORDER BY period ASC") or die(mysql_error());
    
    while($row = mysql_fetch_array($query))
    {
        $teacher = $row['teacher'];
        $period = $row['period'];
        
        $classmates = mysql_query("SELECT * FROM `classes` WHERE teacher='$teacher' AND period=$period");
?>
<div style="margin: 0 auto; width: 516px;">      
<div style="border:  1px solid #000; width: 514px;"><?php echo $teacher . " - Period " . $period; ?></div>
<table border="1">
<?php
        while($row2 = mysql_fetch_array($classmates))
        {
            $id = $row2['submission_id'];
            $student = mysql_query("SELECT * FROM `submissions` WHERE id=$id");
            $row3 = mysql_fetch_array($student);
            $name = $row3['first'] . " " . $row3['last'];
            $grade = $row3['grade'];
?>
            <tr style="width: 400px;"><td style="min-width: 400px;"><a href="schedule.php?id=<?php echo $row3['id'] ?>"><?php echo $name; ?></a></td><td style="min-width: 100px;"><?php echo $grade; ?></td></tr>
<?php
        }
?>
</table>
</div>
<br />
<?php
    }
}
else
{
    $query = mysql_query("SELECT * FROM `submissions` WHERE ip='".$_SERVER['REMOTE_ADDR']."' ORDER BY date DESC");
    
    $rows = mysql_num_rows($query);
    
    if($rows == 1)
    {
        $row = mysql_fetch_array($query);
        
        header('Location: ?id='.$row['id']);
    }
    else if ($rows > 1)
    {
?>
Your submitted schedules:<br />
<?php
        while($row = mysql_fetch_array($query))
        {
?>
            &middot; <a href="lookup.php?id=<?php echo $row['id']; ?>"><?php echo $row['first'] . ' ' . $row['last'] . ' - Submitted on ' . date('n.j.Y, g:i A', $row['date']); ?></a> &middot; <a href="schedule.php?id=<?php echo $row['id']; ?>">View Schedule</a><br />
<?php
        }
    }
    else
    {
        echo "You don't seem to have submitted a schedule yet.  You can submit one <a href=\"submit.php\">here</a>.";
    }
}
?>
<br />
<div id="footer">Copyright &copy 2012 Tony Peng.  All rights are reserved.<br />For security and analytical purposes, your IP (<?php echo $_SERVER['REMOTE_ADDR']; ?>) is being logged.</div>
</div>
</body>
</html>