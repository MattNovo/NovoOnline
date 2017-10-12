<?php
require_once('../../lib/helpers.php');
require_once('../../config.php');

// Mistake was had been requesting 'StartDateTime' and 'EndDateTime'
$data = $mb->GetServices(array('LocationID' => 1,
								'HideRelatedPrograms' => 0));

// Uncommend following line to debug request.								
//$mb->debug();

// Uncommend me to look at the data returned:
mz_pr($data);

die();




?>