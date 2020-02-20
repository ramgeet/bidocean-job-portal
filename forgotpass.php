<?php
/**
 * Online-Job-Portal - A web application built on PHP HTML & javascript
Copyright (C) 2016 Sreelal C

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */
session_start();
include_once('config.php'); //connects to the database
if (isset($_POST['email'])){
    $email = $_POST['email'];
    $query="select * from login where email='$email'";
    $result   = mysqli_query($db1,$query);
    $count=mysqli_num_rows($result);
    // If the count is equal to one, we will send message other wise display an error message.
    $sentmail=0;

    if($count==1)
    {   $rows=mysqli_fetch_array($result);
        $to = $rows['email'];
        $query="select * from jobseeker INNER JOIN login on jobseeker.log_id=login.log_id where login.email=$to";
        $result   = mysqli_query($db1,$query);
        $rows=mysqli_fetch_array($result);
        $pass  =  md5($rows['phone']);//FETCHING PASS
        $log_id = $rows['log_id'];
        $query="UPDATE login set password=$pass where log_id=$log_id";
        $result   = mysqli_query($db1,$query);
        //echo "your pass is ::".($pass)."";
       
        //echo "your email is ::".$email;
        //Details for send
        //ing E-mail
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
   else  
                $url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url.= $_SERVER['HTTP_HOST'];   
        
        // Append the requested resource location to the URL   
        $url.= $_SERVER['REQUEST_URI'];    
            
        $hostname=$url;
        $url = $hostname;
        $body  =  "Your Password Recovery
		-----------------------------------------------
		Url : $url;
		email Details is : $to;
		Here is your password  : $pass;
		Sincerely,
        Bidocean Team ";
      
$subject = "Password Recovery - Job Portal";


$email_to = $to;
$fromserver = "bidocean.jobportal@gmail.com"; 
require("PHPMailer/PHPMailerAutoload.php");
$mail = new PHPMailer();

$mail->IsSMTP();
//$mail->SMTPSecure = 'ssl';
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com"; // Enter your host here
$mail->SMTPAuth = true;
$mail->Username = "bidocean.jobportal@gmail.com"; // Enter your email here
$mail->Password = "Expand&Bido2020"; //Enter your password here
$mail->Port       = 587;
$mail->IsHTML(true);
$mail->From = "bidocean.jobportal@gmail.com";
$mail->FromName = "Bidocean Jobportal Team";
$mail->Sender = $fromserver; // indicates ReturnPath header
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress($email_to);
if(!$mail->Send()){
    $sentmail==0;
    if($_POST['email']!="")
            echo "<span style='color: #ff0000;'> Cannot send password to your e-mail address.Problem with sending mail..d.$mail->ErrorInfo</span>";    
}else{
    $sentmail==1;
    echo "<span style='color: #ff0000;'> Your Password Has Been Sent To Your Email Address.</span>";
}
    } else {
        if ($_POST ['email'] != "") {
            echo '<span style="color: #ff0000;"> Not found your email in our database</span>';
		}
    }
}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forgot Password</title>
</head>
<body>
<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
    <label> Enter your User ID : </label>
    <input id="username" type="text" name="email" />
    <input id="button" type="submit" name="button" value="Sent My Password" />
</form>
</body>
</html>