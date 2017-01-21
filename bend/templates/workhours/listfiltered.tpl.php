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
  <?php if ($workperiod):?>
      <h2>Workperiod <?php echo $workperiod->getSelectOptionTitle()?></h2>
  <?php endif;?>
  <?php if ($user):?>
      <h2>Hours attributed to User: <?php echo $user->getFullName()?></h2>
  <?php endif;?>
  <?php if ($household):?>
      <h2>Household: <?php echo $household->getTitle()?></h2>
  <?php endif;?>
  </div>
  <div class="small-6 large-2 columns"></div>
</div>

<?php if (!empty($workentries)): ?>
    <div class="row">
  		<div class="small-6 large-8 columns" style="text-align: center;">
			<h2>Total Hours Attributed: <?php echo $total_hours?></h2>.
		</div>
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
					<td><?php echo $wp->getHouseholdTitle();?>
                    <td><?php echo $wp->hours?>
                    </td>
          <td>
            <?php echo Html::b("/bend-workhours/editworkentry/" . $wp->id, "Edit");?>
          </td>
                </tr>
            <?php endforeach; ?>
                
            
        </tbody>
    </table>
<?php endif;?>