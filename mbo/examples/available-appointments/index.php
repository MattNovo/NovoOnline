<?php
require '../../src/MB_API.php';
require_once('../../lib/helpers.php');

$mb = new \DevinCrossman\Mindbody\MB_API(array(
	"SourceName"=>'NovoCounselling', 
	"Password"=>'seOAc4JVBf429rWmHn7hS6lrz6E=', 
	"SiteIDs"=>array('175244')
));

// Mistake was had been requesting 'StartDateTime' and 'EndDateTime'
$data = $mb->GetBookableItems(array( 'SessionTypeIDs'=> array(13, 16),
								'StartDate'=>date('Y-m-d'), 
								'EndDate'=>date('Y-m-d', strtotime('today + 1 month'))));

// Uncommend following line to debug request.								
//$mb->debug();

if(!empty($data['GetBookableItemsResult']['ScheduleItems']['ScheduleItem'])) {
	foreach ($data['GetBookableItemsResult']['ScheduleItems']['ScheduleItem'] as $item){
		mz_pr($item);
	}
} else {
	if(!empty($data['GetBookableItemsResult']['Message'])) {
		echo $data['GetBookableItemsResult']['Message'];
	} else {
		echo "Error getting bookable items<br />";
		echo '<pre>'.print_r($data,1).'</pre>';
	}
}




// Uncommend me to look at the data returned:
// mz_pr($data);

?>