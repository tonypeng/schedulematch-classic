<?php
require_once('config.php');
 
// IP check
$query = mysql_query("SELECT * FROM `submissions` WHERE IP='".$_SERVER['REMOTE_ADDR']."'");
    
$ip_times = mysql_num_rows($query);
       
if(isset($_POST['submit']))
{   
    if($ip_times > 3)
    {
        header('Location: ?error=' . urlencode('Too many submissions from this IP!'));
        return;
    }
    
    if($ip_times > 1)
    {
        session_start();
        
        if(strtolower($_POST['captcha']) != $_SESSION['key'])
        {
            header('Location: ?error=' . urlencode('Incorrect captcha!'));
            return;
        }
    }
    
    // http://www.linuxjournal.com/article/9585?page=0,3
    function validEmail($email)
    {
       $isValid = true;
       $atIndex = strrpos($email, "@");
       if (is_bool($atIndex) && !$atIndex)
       {
          $isValid = false;
       }
       else
       {
          $domain = substr($email, $atIndex+1);
          $local = substr($email, 0, $atIndex);
          $localLen = strlen($local);
          $domainLen = strlen($domain);
          if ($localLen < 1 || $localLen > 64)
          {
             // local part length exceeded
             $isValid = false;
          }
          else if ($domainLen < 1 || $domainLen > 255)
          {
             // domain part length exceeded
             $isValid = false;
          }
          else if ($local[0] == '.' || $local[$localLen-1] == '.')
          {
             // local part starts or ends with '.'
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $local))
          {
             // local part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
          {
             // character not valid in domain part
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $domain))
          {
             // domain part has two consecutive dots
             $isValid = false;
          }
          else if
    (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                     str_replace("\\\\","",$local)))
          {
             // character not valid in local part unless 
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/',
                 str_replace("\\\\","",$local)))
             {
                $isValid = false;
             }
          }
          if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
          {
             // domain not found in DNS
             $isValid = false;
          }
       }
       return $isValid;
    }
    
    $first = mysql_real_escape_string($_POST['firstname']);
    $last = mysql_real_escape_string($_POST['lastname']);
    $email = mysql_real_escape_string($_POST['email']);

    // check email format
    if(strlen($email) > 0 && !validEmail($email))
    {
        header('Location: ?error=' . urlencode('Invalid email!'));
        return;
    } 
    
    if(strlen($first) == 0 || strlen($last) == 0)
    {
        header('Location: ?error=' . urlencode('Invalid name!'));
        return;
    }
    
    if(!is_numeric($_POST['class_count']))
        die('Hi, hacker! You just failed.  :)');
        
    $count = intval($_POST['class_count']);
    
    if($count <= 0)
    {
        header('Location: ?error=' . urlencode('Class count must be greater than 0!'));
        return;
    }
    
    if(!is_numeric($_POST['grade']))
        die('Hi, hacker! You just failed.  :)');
        
    $grade = intval($_POST['grade']);
        
    $del_pass = mysql_real_escape_string($_POST['delete_password']);
    
    if(strlen($del_pass) == 0)
    {
        header('Location: ?error=' . urlencode('Please provide a delete password!'));
        return;
    }
    
    $del_pass = sha1(md5(sha1($del_pass)));
        
    $arr = array();
    
    for($i = 0; $i < $count; $i++)
    {
        $period = mysql_real_escape_string($_POST['period_id_' . $i]);
        $teacher = strtolower(mysql_real_escape_string($_POST['teacher_' . $i]));
        
        if(!is_numeric($period))
            die('Hi, hacker! You just failed.  :)');
        
        if(array_key_exists($period, $arr))
        {
            header('Location: ?error=' . urlencode('Duplicate period ' . ($period) . '!'));
            break;
        }
            
        if(strlen($teacher) == 0)
        {
            header('Location: ?error=' . urlencode('No class supplied for period ' . $period. '!'));
            break;
        }
            
        $arr[$period] = $teacher;
    }
    
    $query = "INSERT INTO `submissions` (ip, password, first, last, email, grade, date) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "', '$del_pass', '$first', '$last', '$email', $grade, " . time() . ")";
    mysql_query($query) or die(mysql_error());   
    
    $id = mysql_insert_id();
    
    foreach($arr as $period => $teacher)
    {
        $query = "INSERT INTO `classes` (submission_id, teacher, period) VALUES ($id, '$teacher', $period)";
        mysql_query($query) or die(mysql_error());
    }
    
    header('Location: schedule.php?id='.$id);
}
else
{
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Schedule Match</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript">
// helper function for checking integers
function is_int(value){ 
    return (parseFloat(value) == parseInt(value)) && !isNaN(value);
}

function classCountUpdate()
{
    var class_count = document.getElementById('class_box').value;
    
    if(!is_int(class_count) || class_count <= 0)
    {
        alert('Please enter a valid number! (should be greater than 0 and less than 9)');
        return;
    }
    
    var i = parseInt(document.getElementById('class_count_hidden').value);
        
    if(class_count > i)
    {
        // because we don't want to clear any existing sections, we start from current count and loop to the desired count
        for(; i < class_count; i++)
        {
            var thenode = document.createElement('div');
            thenode.id = "class_"+i;
            var fieldset = document.createElement('fieldset');
            fieldset.id = "fieldset_"+i;
            fieldset.innerHTML = "<legend>Class " + (i+ 1) + "</legend>";
            fieldset.innerHTML += "Period <select id=\"class_selector_" + i + "\" name=\"period_id_" + i + "\"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option></select> Class name: <input type=\"text\" id=\"teacher_id_" + i + "\" name=\"teacher_" + i + "\" value=\"\" />";
            thenode.appendChild(fieldset);
            document.getElementById("container").appendChild(thenode);
            
            thenode = document.getElementById('class_selector_' + i);
            thenode.selectedIndex = i;
        }
    }
    else
    {
        var conf = confirm('You have set a value less than the current count.  The extra fields (along with their data) will be deleted.');
        
        if(!conf)
            return;
        
        // delete from the desired count until count-1
        for(var j = class_count; j < i; j++)
        {
            var thenode = document.getElementById("class_"+j);
            thenode.parentNode.removeChild(thenode);
        }
    }
    
    document.getElementById('class_count_hidden').value = class_count;
}

function toggleVisible(id)
{
    var fieldset = document.getElementById('fieldset_'+id);
    var div = document.getElementById('class_'+id);
    
    if(fieldset.style.display == 'none')
    {
        fieldset.style.display = 'inline-block';
    }
    else
    {
        fieldset.style.display = 'none';
    }
}

function checkRequired()
{
    
}
</script>
</head>
<body>
<div id="page">
<div id="banner"><a href="index.php" class="blacklink">Schedule Match</a></div>
<br />
<?php
if(isset($_GET['error']))
{
?>
<span style="color: red; font-weight: bold;"><?php echo urldecode($_GET['error']); ?></span><br />
<?php
}
?>
<em>Please note - all information you provide here will be publicly visible. All fields are required except email.</em><br />
<br />
<form method="POST" action="">
Classes (choose the amount of classes, not the greatest period number): <input type="text" name="classes" id="class_box" value="0" /> <input type="button" onclick="classCountUpdate();" value="Go" />
<br />
<input type="hidden" name="class_count" id="class_count_hidden" value="0" /><br />
First name: <input type="text" name="firstname" value="" /> Last name: <input type="text" name="lastname" value="" /><br />
Grade: <select name="grade"><option>9</option><option>10</option><option>11</option><option>12</option></select><br />
<br />
Email (optional): <input type="text" name="email" value="" /><br />
<br />
<strong>How to enter data:</strong>
<ul>
<li>Class names should be spelled exactly as on the schedule paper (case does not matter). </li>
<li>There should not be more than one of the same period.</li>
<li>Should you wish to remove your schedule from the website, the delete password will be used.</li>
</ul>
<div id="container">
</div>
<br />
Delete Password: <input type="text" name="delete_password" /><br />
<br />
<img src="captcha.php" border="1" /><br />
Enter the text above (alphanumeric, all lower-case): <input type="text" name="captcha" />
<br />
<input type="submit" value="Submit" name="submit" />
</form>
<br />
<div id="footer">Copyright &copy 2012 Tony Peng.  All rights are reserved.<br />For security and analytical purposes, your IP (<?php echo $_SERVER['REMOTE_ADDR']; ?>) is being logged.</div>
</div>
</body>
</html>
<?php
}
?>