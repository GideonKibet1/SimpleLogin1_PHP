<?php

/*
Reg_No:ENE212-0090/2019
Name: GIDEON KIBET.
*/ 

function connect()
{
 global $cn; 
 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "test";
 $cn = mysqli_connect($servername, $username, $password, $dbname);
 if (!$cn) 
 {
     die("Connection failed: " . mysqli_connect_error());
 }
}
?>