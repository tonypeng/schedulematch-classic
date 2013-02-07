<?php
date_default_timezone_set('America/Los_Angeles');

$conn = mysql_connect('localhost', 'root', '');
$db = mysql_select_db('schedule_match', $conn);
?>