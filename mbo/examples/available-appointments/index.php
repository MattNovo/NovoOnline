<?php
require_once('../../lib/helpers.php');
require_once('../../config.php');

// Mistake was had been requesting 'StartDateTime' and 'EndDateTime'
$data = $mb->GetBookableItems(array( 'SessionTypeIDs'=> array(13), // 13, 16 ProgramIDs from GetService: 2, 9, 11, 15, 27
								'StartDate'=>date('Y-m-d'), 
								'EndDate'=>date('Y-m-d', strtotime('today + 1 week'))));

// Uncommend following line to debug request.								
//$mb->debug();

// Uncommend me to look at the data returned:
//mz_pr($data['GetBookableItemsResult']['ScheduleItems']);

//die();

if(!empty($data['GetBookableItemsResult']['ScheduleItems']['ScheduleItem'])) {
	$bookable = $mb->makeNumericArray($data['GetBookableItemsResult']['ScheduleItems']['ScheduleItem']);
	$bookable = sortBookableByDate($bookable);
	foreach ($bookable as $serviceDate => $services){
		echo date("F j, Y, g:i a", strtotime($serviceDate)).'<br />';
		foreach($services as $service) {
			echo date('H:i', strtotime($service['StartDateTime'])) . ', ' . $service['Staff']['Name'] . ', ' . $service['SessionType']['Name'] . "<hr />";
		}
	}
} else {
	if(!empty($data['GetBookableItemsResult']['Message'])) {
		echo $data['GetBookableItemsResult']['Message'];
	} else {
		echo "Error getting bookable items<br />";
		echo '<pre>'.print_r($data,1).'</pre>';
	}
}

function sortBookableByDate($services = array()) {
	$bookableByDate = array();
	foreach($services as $service) {
		$serviceDate = date("Y-m-d", strtotime($service['StartDateTime']));
		if(!empty($bookableByDate[$serviceDate])) {
			$bookableByDate[$serviceDate] = array_merge($bookableByDate[$serviceDate], array($service));
		} else {
			$bookableByDate[$serviceDate] = array($service);
		}
	}
	ksort($bookableByDate);
	foreach($bookableByDate as $serviceDate => &$servicees) {
		usort($servicees, function($a, $b) {
			if(strtotime($a['StartDateTime']) == strtotime($b['StartDateTime'])) {
				return 0;
			}
			return $a['StartDateTime'] < $b['StartDateTime'] ? -1 : 1;
		});
	}
	return $bookableByDate;
}


?>