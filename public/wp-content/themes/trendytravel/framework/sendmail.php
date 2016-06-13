<?php
if(!$_POST) exit;

    $to 	  = $_POST['hidadminemail'];
	$name	  = $_POST['cname'];
	$email    = $_POST['cemail'];
	$subject  = $_POST['csubject'];
    $comment  = $_POST['cmessage'];

	if(get_magic_quotes_gpc()) { $comment = stripslashes($comment); }

	 $e_subject = '[ValenciaFoodTourSpain] You\'ve been contacted';

	 $msg  = "You have been contacted by $name.\r\n\n";
     $msg .= "-------------------------------------------------------------------------------------------\r\n\n";
     $msg .= "Name: $name.\r\n\n";
	 $msg .= "Message: $comment.\r\n\n";
     $msg .= "Email: $email.\r\n\n";
	 $msg .= "-------------------------------------------------------------------------------------------\r\n";

	 if(@mail($to, $e_subject, $msg, "From: $email\r\nReturn-Path: $email\r\n"))
	 {
		 echo "<div class='dt-sc-success-box'>";
         echo $_POST['hidsuccess'];
         echo "<br><br>";
         echo $_POST['hidnamelabel']." ";
         echo $name;
         echo "<br><br>";
         echo $_POST['hidmessagelabel']." ";
         echo $comment;
         echo "<br><br>";
         echo $_POST['hidemaillabel']." ";
         echo $email;
         echo "<br><br>";
         echo "</div>";
	 }
	 else
	 {
		 echo "<div class='dt-sc-error-box'>".$_POST['hiderror']."</div>";
	 }
?>
