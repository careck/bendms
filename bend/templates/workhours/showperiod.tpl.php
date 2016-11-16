<h1>Workperiod <?php echo formatDate($workperiod->d_start)?> - <?php echo formatDate($workperiod->d_end)?></h1>
    <table width="80%">
        <thead>
            <tr>
                <th>Status</th>
                <th>Months</th>
                <th>Hours Budget</th>
                <th>Hours Logged</th>
                <th>Hours Profit / Loss</th>
            </tr>
        </thead>
        <tbody>
            <?php  
            	$months = $workperiod->getNumberOfMonths();
            	$budget = $workperiod->getAllPersonsBudget();
            	$logged = $workperiod->getTotalHoursLogged();
            	$profit = $logged - $budget;?>
                <tr>
                    <td><?php echo $workperiod->is_closed ? "CLOSED" : "OPEN"?></td>
                    <td><?php echo $months?>
                    <td><?php echo $budget?>
                    <td><?php echo $logged?>
                    <td><?php echo $profit?>
                    </td>
                </tr>
        </tbody>
    </table>
<h2>Breakdown by Category</h2>
<ol id="menutree">
<?php foreach ($categories as $tc):
		$hours = $tc->getHoursLoggedForPeriod($workperiod,true);
		$class = "bend-cat-1".($hours ? " hours" : " no-hours");
		$children = $tc->getChildren();
?>
<li>
<label class="menu_label <?php echo $class?>" for="<?php echo $tc->id?>">
<?php echo $tc->title?> [<?php echo $hours?>]
</label><input type="checkbox" id="<?php echo $tc->id?>"/>
	<?php if ($children):?>
	<ol>
	<?php foreach ($children as $tc2):
			$hours2 = $tc2->getHoursLoggedForPeriod($workperiod,true);
			$class = "bend-cat-2".($hours2 ? " hours" : " no-hours");
			$children2 = $tc2->getChildren();
	?>
	<li>
	<label class="menu_label <?php echo $class?>" for="<?php echo $tc2->id?>">
	<?php echo $tc2->title?> [<?php echo $hours2?>]
	</label><input type="checkbox" id="<?php echo $tc2->id?>"/>
	
		<?php if ($children2):?>
		<ol>
		<?php foreach ($children2 as $tc3):
				$hours3 = $tc3->getHoursLoggedForPeriod($workperiod);
				$class = "bend-cat-3".($hours3 ? " hours" : " no-hours");
		?>
		<li>
		<label class="menu_label <?php echo $class?>" for="<?php echo $tc3->id?>">
		<?php echo $tc3->title?> [<?php echo $hours3?>]
		</label><input type="checkbox" id="<?php echo $tc3->id?>"/>
		
		</li>
		<?php endforeach;?>
		</ol>
		<?php endif;?>
		
	</li>
	<?php endforeach;?>
	</ol>
	<?php endif;?>

</li>
<?php endforeach;?>
</ol>
<h2>Breakdown by Household</h2>
<?php foreach($households as $household):?>
<?php 
$users_and_entries = []; // this will contain a structure of [user_id,hours]
$total_hours = 0;
$workentries = $w->Bend->getWorkEntriesForHousehold($household,$workperiod);
if (!empty($workentries)) {
	foreach ($workentries as $entry) {
		$users_and_entries[$entry->attributed_user_id] = empty($users_and_entries[$entry->attributed_user_id]) ? $entry->hours : $users_and_entries[$entry->attributed_user_id] + $entry->hours;
		$total_hours += $entry->hours;
	}
}
?>
<h3>Household #<?php echo $household->streetnumber?> - Total: <?php echo $total_hours?> hours</h3>
<?php foreach ($users_and_entries as $user_id => $hours):?>
<?php 
$user = $w->Auth->getUser($user_id);
echo Html::a("/bend-workhours/list/{$user_id}/{$workperiod->id}",$user->getFullName().": ".$hours)?><br/>
<?php endforeach;?>

<?php endforeach;?>
