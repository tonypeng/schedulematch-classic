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
<a href="submit.php">Submit a schedule</a><br />
<br />
Look up a class...<br />
<?php
require_once('config.php');

$ip = $_SERVER['REMOTE_ADDR'];
$res = mysql_query("SELECT id FROM `submissions` WHERE ip='$ip' ORDER BY id DESC") or die(mysql_error());
$count = mysql_num_rows($res);
$id = mysql_fetch_array($res);
$id = $id['id'];

if($count > 0)
{
?>
&middot; <a href="lookup.php">by an already submitted schedule</a><br />
<?php
}
?>
&middot; <a href="lookupclass.php">by teacher and period</a><br />
<br />
<a href="classes.php">Current classes</a><br />
<br />
<div id="footer">Copyright &copy 2012 Tony Peng.  All rights are reserved.<br />For security and analytical purposes, your IP (<?php echo $_SERVER['REMOTE_ADDR']; ?>) is being logged.</div>
</div>
</body>
</html>