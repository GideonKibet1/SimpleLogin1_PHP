<html>
<head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/login.css">
<link rel="stylesheet" href="css/font-awesome.css">
</head>

<?php

/*
Reg_No:ENE212-0090/2019
Name: GIDEON KIBET.
*/ 

global $id;
if(isset($_REQUEST['msg']))
{
	if($_REQUEST['msg']==1)
	{
		$msg="signup success";
	}
}
include_once("database_work.php");
global $fname,$lname,$sex,$msg,$db;
session_start();
function ini()
{
	
	if(!isset($_SESSION['login']))
	{
		$_SESSION['login']=null;
		$_SESSION['password']=null;
		$_SESSION['email']=null;
	}
	if(!isset($_SESSION['logout']))
	{
		$_SESSION['logout']=null;
	}
}
function login()
{
	     global $email,$pswd;
		$_SESSION['login']=true;
		$_SESSION['logout']=false;   
}
function logout()
{
	$_SESSION['logout']=true;
	$_SESSION['login']=false;
	session_destroy();
	unset($_SESSION['login']);
	unset($_SESSION['logout']);
}
function check($arg)
{
	ini();
	switch($arg)
	{
		case 'islogin' : if($_SESSION['login'])
		                 {
			                 return true;
						 }
        case 'islogout': if(!$_SESSION['logout'])
		                 {
                             return false;
						 }							 
	}
}
function work()
{
	 global $msg;
	if(isset($_REQUEST['sub']))
	{
		if(check('islogin')==true)
		{
			$msg= "your are alreadylogined in";
		}
		else if(pswdchk())
		{
			login();
		}
		else
		{
			$msg="user name or password was worng";
			return;
		}
	}
	if(isset($_REQUEST['logout']))
	{
		if(check('islogout'))
		{
			$msg= "you are already loged out";
		}
		else
		{
			logout();

			$msg= "You have been  Loged-Out";

		}
	}
}
function pswdchk()
{
	global $email,$pswd,$msg,$cn,$db;
	$db=new database_work('localhost','root','','test');
	$data=$db->select_rows('whole','email,password',null);
    for($i=0;$i<count($data);$i++)
    {
	 for($j=0;$j<count($data[$i]);$j++)
     {
      if($email==$data[$i][$j] && $pswd==$data[$i][$j+1])
	  {
		  $_SESSION['password']=$pswd;
		  $_SESSION['email']=$email;
	      return true;
	  }
     }
	}
 return false;
}
function validation()
{
	global $emailERR,$pswdERR,$email,$pswd;
	$isok=false;
	if(isset($_REQUEST['sub']))
    {
	 if($_REQUEST['email']=='')
	 {
	 	$emailERR='email required';
		$isok=true;
	 }
	 else $email=$_REQUEST['email'];
	 if($_REQUEST['pswd']=='')
	 {
		$pswdERR='password required';
		$isok=true;
	 }
	 else
	 {
	  $pswd=$_REQUEST['pswd'];
	 }
	 return $isok;
	}
}
function prg()
{
	global $emailERR,$pswdERR,$email,$pswd;
	$isok=false;
	if(isset($_REQUEST['sub']))
    {
	 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     $emailERR = "invalid email format";
	 $isok=true;
     }
	 
	}
	return $isok;	
}
//getuser //fatching user data
function getuser()
{
	global $email,$pswd,$msg,$cn,$lname,$fname,$sex,$db,$id;
	$email=$_SESSION['email'];
	$pswd=$_SESSION['password'];
	//$sql = "SELECT * FROM whole where email='$email' and password='$pswd'";
	$db=new database_work('localhost','root','','test');
	$data=$db->select_rows('whole',null,"email='$email' and password='$pswd'");
	 $sex=$data[0][5];
	 $fname=$data[0][1];
	 $lname=$data[0][2];
	 $id=$data[0][0];    
}
function show_user()
{
	global $email,$pswd,$msg,$cn,$lname,$fname,$sex,$db,$id;
	if(check('islogin')==true)
    {
	 $msg="your are Loged-In";
	 echo "<msg style='padding-left: 200px;'>$msg</msg>";
	 getuser();
	 echo"<div class=form >FIRST NAME:-$fname <br>
                               LAST	 NAME:-$lname<br>
						       SEX       :-$sex<br>					   
		<form action=Index.php method=post><input type=submit value=logout name=logout></form></div>";
		
		$folder = "uploads";
	    $results = scandir('uploads');
		for($i=0;$i<count($results);$i++)
		{
			$ext	  = strtolower(pathinfo($results[$i], PATHINFO_EXTENSION));
			if($results[$i]==$id.'.'.$ext)
			{
				$im=$folder.'/'.$id.'.'.$ext;
				echo"<img  src=$im height=100 width=100>";
				
			}
		}
    }
}
ini();
//connect();
if(!validation())
{
 if(!prg())
 {
	 work();
 }
}
if(check('islogin')==true)
{
	show_user();
}
else
{
	echo "<msg>$msg</msg>";
?>
<body> 
<div class=main>
<form action=index.php method=post>
<div class=form-input>
<input type=text name=email placeholder="enter user name" value="<?php if(isset($_REQUEST['user'])) echo $_REQUEST['user']; ?>">
</div>
<div class=error>
<?php echo "$emailERR";?>
</div>
<div class=form-input>
<input type=password name=pswd placeholder=password>
</div>
<div class=error><?php echo"$pswdERR";?></div>
<input type=submit name=sub class="button signup" id=mk value=Login>  <a href="signup.php"><input type=button class="button signup" value=Signup></a>
</form>

</div>
</body>
</html>
<?php
}
?>