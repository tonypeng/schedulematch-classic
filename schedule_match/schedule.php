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
if(isset($_GET['id']))
{
    require_once('config.php');
    
    $id = mysql_real_escape_string($_GET['id']);
    
    $sub_row = mysql_fetch_array(mysql_query("SELECT * FROM `submissions` WHERE id=$id"));
    $query = mysql_query("SELECT * FROM `classes` WHERE submission_id=$id ORDER BY period ASC");
?>
<div style="margin: 0 auto; width: 516px;">  
<div style="border:  1px solid #000; width: 514px;"><?php echo $sub_row['first'] . ' ' . $sub_row['last'] . ' - Grade ' . $sub_row['grade']; ?></div>
<table border="1">
<?php
    while($class = mysql_fetch_array($query))
    {
?>
        <tr style="width: 500px;"><td style="min-width: 100px;"><?php echo $class['period']; ?></td><td style="min-width: 400px;"><a href="lookupclass.php?teacher=<?php echo $class['teacher']; ?>&period=<?php echo $class['period']; ?>&submit"><?php echo $class['teacher']; ?></td></tr>
<?php
    }
?>
</table>
<div style="margin-top: 2px;">
<form method="POST" action="delete.php">
<input type="password" name="password" style="width: 80%;" /> <input type="submit" name="submit" value="Delete" style="width: 18%;" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</form>
</div>
</div>
<?php
}
else
{
    echo 'No schedule selected.';
}
?>
<br />
<div id="footer">Copyright &copy 2012 Tony Peng.  All rights are reserved.<br />For security and analytical purposes, your IP (<?php echo $_SERVER['REMOTE_ADDR']; ?>) is being logged.</div>
</div>
</body>
</html>
