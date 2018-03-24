<h1><?php echo formatDate($workperiod->d_start)?> - <?php echo formatDate($workperiod->d_end)?></h1>
<div class="tabs">
	<div class="tab-head">
         <a href="#workperiod">Workperiod</a>
         <a href="#categories">By Categories</a>
         <a href="#households">By Households</a>
	</div>
</div>
<div class="tab-body">
	<div id="workperiod">
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
	</div>
	<div id="categories">
		<ol id="menutree">
		<?php foreach ($categories as $tc):
				$hours = $tc->getHoursLoggedForPeriod($workperiod,true);
				$class = "bend-cat-1".($hours ? " hours" : " no-hours");
				$children = $tc->getChildren();
		?>
		<li>
		<label class="menu_label <?php echo $class?>" for="<?php echo $tc->id?>">
		[<?php echo $hours?>] <?php echo $tc->title?> 
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
			[<?php echo $hours2?>] <?php echo $tc2->title?> 
			</label><input type="checkbox" id="<?php echo $tc2->id?>"/>
			
				<?php if ($children2):?>
				<ol>
				<?php foreach ($children2 as $tc3):
						$hours3 = $tc3->getHoursLoggedForPeriod($workperiod);
						$class = "bend-cat-3".($hours3 ? " hours" : " no-hours");
				?>
				<li>
				<label class="menu_label <?php echo $class?>" for="<?php echo $tc3->id?>">
				[<?php echo $hours3?>] <?php echo $tc3->title?> 
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
	</div>
	<div id="households">
		<table>
			<thead>
				<tr>
					<td>Name</td>
					<td>Hours Necessary</td>
					<td>Hours Attributed</td>
					<td>Hours Owing</td>
					<td>Sapphs/AUD Owing</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach($households as $household):?>
					<?php 
					$users_and_entries = []; // this will contain a structure of [user_id,hours]
					$total_hours = 0;
					$workentries = $w->Bend->getWorkEntriesForHousehold($household,$workperiod);
					$occupants = $household->getOccupantsForWorkperiod($workperiod);
					$household_budget = $household->getWorkhoursLevyForWorkperiod($workperiod);
					if (!empty($workentries)) {
						foreach ($workentries as $entry) {
							$users_and_entries[$entry->attributed_user_id] = empty($users_and_entries[$entry->attributed_user_id]) ? $entry->hours : $users_and_entries[$entry->attributed_user_id] + $entry->hours;
							$total_hours += $entry->hours;
						}
					}
					$debt = $household_budget - $total_hours;
					?>
					<tr class="show-period-household-list-household-row">
						<td><?php echo $household->getSelectOptionTitle();?></td>
						<td><?php echo $household_budget?></td>
						<td><?php echo $total_hours;?></td>
						<td><?php echo $debt < 0 ? 0 : $debt?></td>
						<td><?php echo $debt < 0 ? 0 : $workperiod->getSapphsForHours($debt)?></td>
					</tr>
					<?php foreach ( $occupants as $occ):?>
					<?php 
						if (!$occ->does_workhours) continue;
						$user = $w->Auth->getUser($occ->user_id);
						// calculate the number of months that this user needs to work
						$user_budget = $occ->getWorkhoursLevyForWorkperiod($workperiod);
						$user_hours = !empty($users_and_entries[$user->id]) ? $users_and_entries[$user->id] : 0;
						$user_debt = $user_budget - $user_hours;
					?>
					<tr class="show-period-household-list-occupant-row">
						<td><?php echo Html::a("/bend-workhours/list/{$user->id}/{$workperiod->id}",$user->getFullName())?></td>
						<td><?php echo $user_budget?></td>
						<td><?php echo $user_hours;?></td>
						<td><?php echo $user_debt < 0 ? 0 : $user_debt?></td>
						<td><?php echo $user_debt < 0 ? 0 : $workperiod->getSapphsForHours($user_debt)?></td>
					</tr>
					<?php endforeach;?>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>
