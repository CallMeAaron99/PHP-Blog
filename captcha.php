<?php
	session_start();
	include_once("./phptextClass.php");	
	
	/*create class object*/
	$phptextObj = new phptextClass();	
	/*phptext function to genrate image with text*/
	header('Content-type:image/jpg');
	$phptextObj->phpcaptcha('#FFA500','#fff',120,40,10,25);	
 ?>