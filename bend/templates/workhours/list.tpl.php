<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
      <a href="/bend-workhours/" class="button expand large">Workhours</a>
  </div>
  <div class="small-12 large-2 columns"></div>
</div>
<?php if (!empty($workentries)): ?>
    <table width="80%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Person</th>
                <th>Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workentries as $wp):?> 
                <tr>
                    <td><?php echo formatDate($wp->d_date)?></td>
                    <td><?php echo $wp->getFullCategoryTitle()?></td>
                    <td><?php echo $wp->description?>
                    <td><?php echo $wp->getUser()->getFullName()?></td>
                    <td><?php echo $wp->hours?>
                    </td>
					<td>
						<?php echo Html::b("/bend-workhours/editworkentry/" . $wp->id, "Edit");?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else:?>
<div class="row">
  <div class="small-6 large-2 columns"></div>
  <div class="small-6 large-8 columns">
	You have no work hours recorded.
  </div>
  <div class="small-12 large-2 columns"></div>
</div>
<?php endif;?>