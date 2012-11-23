<?php


	$curDate = '';
foreach ($events as $event) {
	
	

	if($curDate!=$event->date) {
		echo '<div class="eventsDate">'.datefr($event->date).'</div>';
	}
	require('view.php');

	$curDate = $event->date;
}

?>