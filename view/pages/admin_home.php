<div class="page-header">
	<h1>Welcome</h1>		
</div>

<div>
	<h4>Utilisateurs</h4>
	<p>
		Total : <strong><?php echo $totalUsers;?></strong>
	</p>
	<p>
		Inscrit aujourd'hui :<strong><?php echo $todayRegistering;?></strong>
	</p>
	<p>
		Inscription / mois :
		<?php foreach ($monthRegistering as $year => $months):?>
			<table class="table-bordered table-hover table-stripped" style="width:100%;">
				<th></th>
				<?php foreach($months as $month => $number): ?>
					<th><?php echo $month;?></th>
				<?php endforeach; ?>
				<tr>
					<td><?php echo $year;?></td>
					<?php 
					reset($months);
					foreach($months as $month => $number): ?>
					<td style="text-align:center"><?php echo $number;?></td>
					<?php endforeach; ?>
				</tr>
		<?php endforeach; ?>
		<table>			
	</p>
</div>

<div>
	<h4>Evenements</h4>
	<p>
		Total : <strong><?php echo $totalEvents;?></strong>
	</p>
	<p>
		Ont lieu aujourd'hui: <strong><?php echo $nbEventsToday;?></strong>
	</p>
	<p>
		Evts / mois :
		<?php foreach ($nbEventsPerMonth as $year => $months):?>
			<table class="table-bordered table-hover table-stripped" style="width:100%;">
				<th></th>
				<?php foreach($months as $month => $number): ?>
					<th><?php echo $month;?></th>
				<?php endforeach; ?>
				<tr>
					<td><?php echo $year;?></td>
					<?php 
					reset($months);
					foreach($months as $month => $number): ?>
					<td style="text-align:center"><?php echo $number;?></td>
					<?php endforeach; ?>
				</tr>
		<?php endforeach; ?>
		<table>			
	</p>
</div>