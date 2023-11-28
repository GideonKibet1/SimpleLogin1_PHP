<html>
<head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/signup.css">
<link rel="stylesheet" href="css/font-awesome.css">
</head>
</head>
<?php

/*
Reg_No:ENE212-0090/2019
Name: GIDEON KIBET.
*/ 

$fnameERR='';$lnameERR='';$emailERR='';$pswdERR='';$pswd2ERR='';$sexERR='';$subERR='';
$fname=null;$lname=null;$email=null;$pswd=null;$sex=null;$cn;$response='';$id='';
//---------------------start----------------------------
//---------------------connection-----------------------
include_once("database_work.php");

function connect()
{
	 global $fname,$lname,$email,$pswd,$sex,$emailERR,$cn,$id,$db;
 if(isset($_REQUEST['sub']))
 {  
   $db=new database_work('localhost','root','','test');
   $data=$db->select_rows('whole','email',null);
   for($i=0;$i<count($data);$i++)
   {
	  for($j=0;$j<count($data[$i]);$j++)
       if($_REQUEST['email']==$data[$i][$j])
	   {
		   $emailERR="this email in use";
		    echo $data[$i][$j];

		   return false;
	   }
   }
     //extracting id from table
   $data=$db->select_rows('whole','id',null);
   for($i=0;$i<count($data);$i++)
   {
	for($j=0;$j<count($data[$i]);$j++)
	{
      $id=$data[$i][$j]+1;
    }
   } 
	 //$sql = "INSERT INTO whole values($id,'$fname','$lname','$email','$pswd','$sex')";
	 if($db->insert_data("whole",null,"'$id','$fname','$lname','$email','$pswd','$sex'"))
	{
		return true;
		
	}
	else
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($this->$cn);
	  return false;
	}
 }
}

function query()
{
	
}

function validation()
{
	global $fnameERR,$lnameERR,$emailERR,$pswdERR,$pswd2ERR,$sexERR,$subERR,$fname,$lname,$email,$pswd,$pswd2,$sex;
	$isok=false;
	if(isset($_REQUEST['sub']))
    {
	 if($_REQUEST['fname']=='')
	 {
	 	$fnameERR='first name required';
		$isok=true;
	 }
	 else $fname=$_REQUEST['fname'];
	 if($_REQUEST['lname']=='')
	 {
		$lnameERR='last name required';
		$isok=true;
	 }
	 else $lname=$_REQUEST['lname'];
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
	 if($_REQUEST['pswd2']=='')
	 {
		$pswd2ERR='conform password required';
		$isok=true;
	 }
	 else if($_REQUEST['pswd']==$_REQUEST['pswd2'])
	 {
	  $pswd=$_REQUEST['pswd'];
	 }
	 else {
		 $isok=true;
	  $pswdERR="password not match";
	  }
	 if(empty($_REQUEST['sex']))
	 {
	 	$sexERR='gender required';
		$isok=true;
	 }
	 else $sex=$_REQUEST['sex'];
	 return $isok;
    }
	return $isok;
}
//----------------validations end-------------------------------
//----------------primary validation start----------------------
function check()
{
	global $fnameERR,$lnameERR,$emailERR,$pswdERR,$pswd2ERR,$sexERR,$subERR,$fname,$lname,$email,$pswd,$sex;
	$isok=false;
	if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
    $fnameERR = "Only letters and white space allowed";
	$isok=true;
    }
	if (!preg_match("/^[a-zA-Z ]*$/",$lname)) {
    $lnameERR = "Only letters and white space allowed";
	$isok=true;
    }
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailERR = "invalid email format";
	$isok=true;
    }
	if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{2,30}$/', $pswd)) 
	{
     $pswdERR= "there should be at least one number,one letter,a number and  8-30 characters ";
	 $isok=true;
    }
	return $isok;
	
}
//----------------end of primary validation check---------------
//----------------check connection state------------------------
//upload picture
function upload()
{
	
 error_reporting(E_ALL);
 global $id,$response,$db;
 $db=new database_work('localhost','root','','test');
 $me=false;
   $data=$db->select_rows('whole','id',null);
   for($i=0;$i<count($data);$i++)
   {
	for($j=0;$j<count($data[$i]);$j++)
	{
      $id=$data[$i][$j]+1;
    }
   } 
   if($id<=0)
   {
	   $id=1;
   }
  ini_set('display_errors', 1);
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$name     = $_FILES['file']['name'];
	$tmpName  = $_FILES['file']['tmp_name'];
	$error    = $_FILES['file']['error'];
	$size     = $_FILES['file']['size'];
    $ext	  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
	switch ($error) {
		case UPLOAD_ERR_OK:
			$valid = true;
			//validate file extensions
			if ( !in_array($ext, array('jpg','jpeg','png','gif')) ) {
				$valid = false;
				$response = 'Invalid file extension.';
			}
			//validate file size
			if ( $size/1024/1024 > 2 ) {
				$valid = false;
				$response = 'File size is exceeding maximum allowed size.';
			}
			//upload file
			if ($valid) {
				$targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads' . DIRECTORY_SEPARATOR. $id.'.'.$ext;
				move_uploaded_file($tmpName,$targetPath); 
				$me=true;
				return $me;
			}
			break;
		case UPLOAD_ERR_INI_SIZE:
			$response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
			break;
		case UPLOAD_ERR_PARTIAL:
			$response = 'The uploaded file was only partially uploaded.';
			break;
		case UPLOAD_ERR_NO_FILE:
			$response = 'No file was uploaded.';
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			$response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
			break;
		case UPLOAD_ERR_CANT_WRITE:
			$response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
			break;
		default:
			$response = 'Unknown error';
		break;
	}
	return $me;
}	
}
if(isset($_REQUEST['sub']))
{
 if(validation()==false)
 {
  	if(!check())
	{
	  if(upload())
	  {
	   if(connect())
	   { 
		echo"<msg>signup success thank you :)</msg>";
		$fnameERR='';$lnameERR='';$emailERR='';$pswdERR='';$pswd2ERR='';$sexERR='';$subERR='';
        $fname=null;$lname=null;$email=null;$pswd=null;$sex=null;
		header( 'Location: index.php?msg=1' ) ;
       }
	  }
	}
 }	
}
?>
<body>
 <div class=main>
 <form action=signup.php method=post enctype="multipart/form-data">
	<div class="form-input">
		<input type=text name=fname placeholder="ENTER FIRST NAME"  value=<?php if(isset($_REQUEST['fname']))echo $_REQUEST['fname'];?> >
		<div class=error><?php echo"$fnameERR";?></div>
	</div>
	<div class="form-input">
	<input type=text name=lname placeholder="ENTER LAST  NAME" value=<?php if(isset($_REQUEST['lname']))echo $_REQUEST['lname'];?>>
		<div class=error><?php echo"$lnameERR";?></div>
	</div>
	<div class="form-input">
		<input type=text name=email placeholder="EXAMLE@EXAMLE.COM" value=<?php if(isset($_REQUEST['email']))echo $_REQUEST['email'];?>>
		<div class=error><?php echo"$emailERR";?></div>
	</div>
	<div class="form-input">
		<input type=password name=pswd placeholder="password" value=<?php if(isset($_REQUEST['pswd']))echo $_REQUEST['pswd'];?>>
		<div class=error><?php echo"$pswdERR";?></div>
	</div>
	<div class="form-input">
		<input type=password name=pswd2 placeholder="password">
		<div class=error><?php echo"$pswd2ERR";?></div>
	</div>
	<div class="form-input">
		<input type=radio name=sex value=male checked=checked>
	</div>
	<div class="form-input">
		<input type=radio name=sex value=female>
		
	</div>
	<div class="from-input">
		<input type=file name=file class="button upload" ><div class=error><?php echo"$response";?></div>
	</div>
	<input type='submit' name='sub' value='signup' class="button signup" ><a href="index.php"><input type=button class="button signup" value=Login></a>
 </form>
</div>
</body>
</html>
