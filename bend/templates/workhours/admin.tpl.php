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
                        <?php echo Html::box("/bend-workhours/editperiod/$wp->id", "Edit", true); ?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<h1>Work Categories</h1>
<?php echo Html::box("/bend-workhours/addcategory/", "+", true); ?>
<?php if (!empty($focusgroups)):?>
<ol>
<?php foreach ($focusgroups as $fc):?>
<li class="bend-cat-1">
<?php echo $fc->title?>&nbsp;
<?php echo Html::box("/bend-workhours/addcategory/".$fc->id, "<i class=\"fa fa-plus\"></i>", false); ?>&nbsp;
<?php echo Html::box("/bend-workhours/editcategory/".$fc->id, "<i class=\"fa fa-pencil\"></i>", false); ?>&nbsp;
<?php echo Html::a("/bend-workhours/deletecategory/".$fc->id, "<i class=\"fa fa-trash-o\"></i>", null, null, "Remove?"); ?>&nbsp;
	<?php $teams = $fc->getChildren(); if (!empty($teams)):?>
	<ol>
	<?php foreach ($teams as $tc):?>
	<li class="bend-cat-2">
	<?php echo $tc->title?>&nbsp;
	<?php echo Html::box("/bend-workhours/addcategory/".$tc->id, "<i class=\"fa fa-plus\"></i>", false); ?>&nbsp;
	<?php echo Html::box("/bend-workhours/editcategory/".$tc->id, "<i class=\"fa fa-pencil\"></i>", false); ?>&nbsp;
	<?php echo Html::a("/bend-workhours/deletecategory/".$tc->id, "<i class=\"fa fa-trash-o\"></i>", null, null, "Remove?"); ?>&nbsp;
		<?php $actions = $tc->getChildren(); if (!empty($actions)):?>
		<ol>
		<?php foreach ($actions as $ac):?>
		<li class="bend-cat-3">
		<?php echo $ac->title?>&nbsp;
		<?php echo Html::box("/bend-workhours/editcategory/".$ac->id, "<i class=\"fa fa-pencil\"></i>", false); ?>&nbsp;
		<?php echo Html::a("/bend-workhours/deletecategory/".$ac->id, "<i class=\"fa fa-trash-o\"></i>", null, null,"Remove?"); ?>&nbsp;
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
<?php endif;?>