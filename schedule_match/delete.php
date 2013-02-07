<?php
require_once('config.php');

if(isset($_POST['submit']))
{
    if(!is_numeric($_POST['id']))
        header('Location: index.php');
        
    $id = intval($_POST['id']);
    $password = mysql_real_escape_string($_POST['password']);
    
    $query = mysql_query("SELECT * FROM `submissions` WHERE id=$id") or die(mysql_error());
    
    if(mysql_num_rows($query) <= 0)
        die('Unknown error');
        
    $row = mysql_fetch_array($query);
        
    if($row['password'] != sha1(md5(sha1($password))))
        die('Invalid password.');
        
    mysql_query("DELETE FROM `classes` WHERE submission_id=$id");
    mysql_query("DELETE FROM `submissions` WHERE id=$id");
    
    header('Location: index.php');
}
else
{
    header('Location: index.php');
}
?>