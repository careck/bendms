<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
      <a href="/bend-workhours/" class="button expand large">Workhours</a>
  </div>
  <div class="small-6 large-2 columns"></div>
</div>

<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns" style="text-align: center;">
      <h2>Workperiod <?php echo $workPeriod->getSelectOptionTitle()?>
      <?php if ($workPeriod->is_closed){?> (CLOSED)<?php }?></h2>
      <h2><?php echo $user->getFullName()?></h2>
      <p><b>Total Workhours attributed to <?php echo $user->getFullName()?>: <?php echo $total_accredited + $total_attributed?></b></p>
  </div>
  <div class="small-6 large-8 columns" style="text-align: center;">
      <?php if (!empty($previous_workperiod_id)){?>
      <a href="/bend-workhours/list/<?php echo $user->id?>/<?php echo $previous_workperiod_id?>">&lt; Previous Workperiod</a>
      <?php } 
      		if (!empty($next_workperiod_id)){?>
      &nbsp;|&nbsp;<a href="/bend-workhours/list/<?php echo $user->id?>/<?php echo $next_workperiod_id?>">Next Workperiod &gt;</a>
      <?php }?>
  </div>
  <div class="small-6 large-2 columns"></div>
</div>


<?php if (!empty($workentries)): ?>
    <div class="row">
      <div class="small-6 large-2 columns"></div>
      <div class="small-6 large-8 columns" style="text-align: center;">
          <h3>Workhours done</h3>
      </div>
      <div class="small-6 large-2 columns"></div>
    </div>
    <table width="80%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Who did the work?</th>
                <th>Work credited to</th>
                <th>Household</th>
                <th>Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workentries as $wp):?> 
                <tr>
                    <td><?php echo formatDate($wp->d_date,"d/m/Y",true)?></td>
                    <td><?php echo $wp->getFullCategoryTitle()?></td>
                    <td><?php echo $wp->description?>
                    <td><?php echo $wp->getUser()->getFullName()?></td>
                    <td><?php echo $wp->getAccredited() ? $wp->getAccredited()->getFullName() : ""?></td>
                    <td>#<?php echo $wp->getHousehold()->streetnumber?></td>
                    <td><?php echo $wp->hours?>
                    </td>
					<td>
						<?php if (!$workPeriod->is_closed):?>
						<?php echo Html::b("/bend-workhours/editworkentry/" . $wp->id, "Edit");?>
						<?php echo Html::b("/bend-workhours/deleteworkentry/" . $wp->id, "Delete","This action cannot be reversed, really delete?");?>
						<?php endif;?>
					</td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="8" style="text-align: center; font-size: 1.5em">
                    	Total Hours Worked for this Period: 
                    	<b><?php echo $total_worked?></b>. Accredited to self: <b><?php echo $total_accredited?></b>
                    </td>
                </tr>
            
        </tbody>
    </table>
<?php endif;?>


<?php if (!empty($workentries_attributed)): ?>
    <div class="row">
      <div class="small-6 large-2 columns"></div>
      <div class="small-6 large-8 columns" style="text-align: center;">
          <h3>Workhours attributed to by others</h3>
      </div>
      <div class="small-6 large-2 columns"></div>
    </div>
    <table width="80%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Who did the work?</th>
                <th>Work credited to</th>
                <th>Household</th>
                <th>Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workentries_attributed as $wp):?> 
                <tr>
                    <td><?php echo formatDate($wp->d_date,"d/m/Y",true)?></td>
                    <td><?php echo $wp->getFullCategoryTitle()?></td>
                    <td><?php echo $wp->description?>
                    <td><?php echo $wp->getUser()->getFullName()?></td>
                    <td><?php echo $wp->getAccredited() ? $wp->getAccredited()->getFullName() : ""?></td>
                    <td>#<?php echo $wp->getHousehold()->streetnumber?></td>
                    <td><?php echo $wp->hours?>
                    </td>
          <td>
            <?php echo Html::b("/bend-workhours/editworkentry/" . $wp->id, "Edit");?>
			<?php if (!$workPeriod->is_closed):?>
			<?php echo Html::b("/bend-workhours/deleteworkentry/" . $wp->id, "Delete","This action cannot be reversed, really delete?");?>
			<?php endif;?>
          </td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="8" style="text-align: center; font-size: 1.5em">
                      Total Hours Attributed for this Period: 
                      <b><?php echo $total_attributed?></b>.
                    </td>
                </tr>
            
        </tbody>
    </table>
<?php endif;?>