<h2>Work Periods</h2>
<p></p>
<?php echo Html::box("/bend-workhours/editperiod/", "Add Work Period", true); ?>

<?php if (!empty($workperiods)): ?>
    <table width="80%">
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Months</th>
                <th>Hours Budget</th>
                <th>Hours Logged</th>
                <th>Hours Profit / Loss</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workperiods as $wp): 
            	$months = $wp->getNumberOfMonths();
            	$budget = $wp->getAllPersonsBudget();
            	$logged = $wp->getTotalHoursLogged();
            	$profit = $logged - $budget;?>
                <tr>
                    <td><?php echo formatDate($wp->d_start)?></td>
                    <td><?php echo formatDate($wp->d_end)?></td>
                    <td><?php echo $wp->is_closed ? "CLOSED" : "OPEN"?></td>
                    <td><?php echo $months?>
                    <td><?php echo $budget?>
                    <td><?php echo $logged?>
                    <td><?php echo $profit?>
                    </td>
					<td>
						<?php echo Html::b("/bend-workhours/showperiod/" . $wp->id, "Details");?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>