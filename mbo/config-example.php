<?php
require 'src/MB_API.php';

$mb = new \DevinCrossman\Mindbody\MB_API(array(
	"SourceName"=>'Source Name', 
	"Password"=>'Don\'t Forget the Equal Sign', 
	"SiteIDs"=>array('site number')
));
?>