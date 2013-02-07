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
?>
Classes submitted as of <?php echo date('n.j.Y, g:i A'); ?>:<br />
<br />
<strong>Most Populous:</strong><br />
<?php
$query = mysql_query("SELECT teacher, period, COUNT(*) AS students FROM `classes` GROUP BY teacher, period ORDER BY students DESC, teacher DESC, period DESC LIMIT 5") or die(mysql_error());

while($row = mysql_fetch_array($query))
{
    echo '<a href="lookupclass.php?teacher='.$row['teacher'].'&period='.$row['period'].'&submit">' . $row['teacher'] . ' - Period ' . $row['period'] . '</a> - '.$row['students'].' student'.($row['students']==1?'':'s').'<br />';
}
?>
<br />
<strong>All Classes (a-z, 1-7):</strong><br />
<?php
$query = mysql_query("SELECT DISTINCT teacher, period FROM `classes` ORDER BY teacher ASC, period ASC");

while($row = mysql_fetch_array($query))
{
    echo '<a href="lookupclass.php?teacher='.$row['teacher'].'&period='.$row['period'].'&submit">' . $row['teacher'] . ' - Period ' . $row['period'] . '</a><br />';
}
?>
<br />
<div id="footer">Copyright &copy 2012 Tony Peng.  All rights are reserved.<br />For security and analytical purposes, your IP (<?php echo $_SERVER['REMOTE_ADDR']; ?>) is being logged.</div>
</div>
</body>
</html>