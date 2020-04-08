<?php
/*
 *@category   XAMPP
 * @package   Virtual Host Manager For Xampp Server
 * @author    Yamikani Sita <sitayamikani@gmail.com>
 * @copyright 2020, Mzuzu University, ICT Department
 */
/*@return codes*/
define("NO_WRITE_PERMISSION_VHOST", 1);
define("NO_WRITE_PERMISSION_HOST", 2);
define("SUCCESS", 3);
define("PARAMETER_ERR", 4);
 class Domain{
	 private $domainName;
	 private $sdirectory;
	 function __construct($domainName, $sdirectory){
		 $this->domainName=$domainName;
		 $this->sdirectory=$sdirectory;
	 }
	 function addDomain(){
		 /*The first thing is to add domain to httpd-vhosts.conf**/
		 $file_dir="C:\\xampp\apache\conf\\extra\httpd-vhosts.conf";
		 $file=fopen($file_dir, 'a+');//write to end of file
		 if(is_writable($file_dir)){
			  $docRoot=$this->getRoot().$this->sdirectory;
			  $subDomain=$this->domainName;
			  $rd=$subDomain.'.localhost';
			  $content_to_write="\n<VirtualHost *:80>
					DocumentRoot $docRoot
					ServerName $rd
				</VirtualHost>";
				if(fwrite($file, $content_to_write)){
					/*Attempt to write to host file*/
					$dir_host="C:\Windows\System32\drivers\\etc\hosts";
					if(is_writable($dir_host)){
						$host_f=fopen($dir_host, 'a+');
						$con="127.0.0.1       ".$this->domainName."localhost";
						fwrite($host_f, $con);
						return SUCCESS;
						
					}
					else{
						return NO_WRITE_PERMISSION_HOST;
					}
				}
				else{
					return NO_WRITE_PERMISSION_VHOST;
				}
		 }
		 
		 else{
			return NO_WRITE_PERMISSION_VHOST;
		 }
	 }
	 function getRoot(){
		 return $_SERVER['DOCUMENT_ROOT'];
	 }
 }
if(isset($_GET['domName'])&&isset($_GET['dir'])){
	$domName=$_GET['domName'];
	$dir=$_GET['dir'];
	$d=new Domain($domName, $dir);
	$output=$d->addDomain();
	if($output==SUCCESS){
		echo SUCCESS;
	}
	else if($output==NO_WRITE_PERMISSION_HOST){
		echo NO_WRITE_PERMISSION_HOST;
	}
	else if($output==NO_WRITE_PERMISSION_VHOST){
		echo NO_WRITE_PERMISSION_VHOST;
	}
}
else{
	echo PARAMETER_ERR;
}
 ?>
