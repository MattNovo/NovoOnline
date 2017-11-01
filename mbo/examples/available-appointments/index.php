<?php
require_once('../../lib/helpers.php');
require_once('../../config.php');

if ($_GET['reset'] || !$data = unserialize(file_get_contents('mbodata'))) {

	?><h2>Resetting Data</h2><?php

	$data = $mb->GetBookableItems(array( 'SessionTypeIDs'=> array(13), // 13, 16 ProgramIDs from GetService: 2, 9, 11, 15, 27
									'StartDate'=>date('Y-m-d'), 
									'EndDate'=>date('Y-m-d', strtotime('today + 1 week'))));
									
	file_put_contents('mbodata', serialize($data));
}

// Uncommend following line to debug request.								
//$mb->debug();
?>
<div style="max-width:90%;border:1px solid black;margin-left:3rem;">
	<table>
<?php
if(!empty($data['GetBookableItemsResult']['ScheduleItems']['ScheduleItem'])) {
	$bookable = $mb->makeNumericArray($data['GetBookableItemsResult']['ScheduleItems']['ScheduleItem']);
	$bookable = sortBookableByDateNameTime($bookable);
	foreach ($bookable as $serviceDate => $staffMember){
		?>
		<tr><th colspan="2" style="background: rgba(158, 255, 202, 1);"><?php echo date("l F j, Y", strtotime($serviceDate)); ?></th></tr>
		<?php
		foreach($staffMember as $staff => $services) { 
			$staffImage = isset($services[0]['Staff']['ImageURL']) ? '<a href="'.strip_tags($services[0]['Staff']['Bio']).'"><img src="'.$services[0]['Staff']['ImageURL'].'" style="max-width:100px;height:auto;" /></a>' : ''; ?>
			<tr><td><?php echo $staffImage ?></td>
			<td> <?php echo $services[0]['Staff']['FirstName']; ?>
			<table style="border: 1px solid rgba(158, 255, 202, 1);"> <?php
			foreach($services as $service) { ?>
				<tr><td><?php echo $service['SessionType']['Name'] . ' ' . date('h:i A', strtotime($service['StartDateTime'])) . ' - ' . date('h:i A', strtotime($service['EndDateTime']));?></td></tr>
				<?php
			} ?>
			</table></td>
			<?php
		} // End Staff Member Loop ?>
		</tr>
		<?php
	} // End Date Loop
} else {
	if(!empty($data['GetBookableItemsResult']['Message'])) {
		echo $data['GetBookableItemsResult']['Message'];
	} else {
		echo "Error getting bookable items<br />";
		echo '<pre>'.print_r($data,1).'</pre>';
	}
}
?>
	</table>
</div> <!-- Main Div -->
<?php
function sortBookableByDateNameTime($services = array()) {
	$bookableByDate = array();
	// Build an array for dates with one array for each date key.
	foreach($services as $service) {
		$serviceDate = date("Y-m-d", strtotime($service['StartDateTime']));
		if(!empty($bookableByDate[$serviceDate])) {
			$bookableByDate[$serviceDate] = array_merge($bookableByDate[$serviceDate], array($service));
		} else {
			$bookableByDate[$serviceDate] = array($service);
		}
	}
	// Sort the array of days numerically by date key
	ksort($bookableByDate);
	foreach ($bookableByDate as $serviceDate => $services){
		$bookableByDate[$serviceDate] = sortServicesbyStaff($services);
	}
	return $bookableByDate;
	foreach($bookableByDate as $serviceDate => &$services) {
		usort($services, function($a, $b) {
			if(strtotime($a['StartDateTime']) == strtotime($b['StartDateTime'])) {
				return 0;
			}
			return $a['StartDateTime'] < $b['StartDateTime'] ? -1 : 1;
		});
	}
	return $bookableByDate;
}

function sortServicesbyStaff($services = array()){
	// Create array of times for each Staff Member key
	$servicesbyStaff = array();
	foreach($services as $k => $service){
		$staffMember = $service['Staff']['Email'];
		if(!empty($servicesbyStaff[$staffMember])) {
			$servicesbyStaff[$staffMember] = array_merge($servicesbyStaff[$staffMember], array($service));
		} else {
			$servicesbyStaff[$staffMember] = array($service);
		}
	}
	return $servicesbyStaff;
}


?>