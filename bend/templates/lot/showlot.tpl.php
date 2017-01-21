<h1>Lot <?php echo $lot->lot_number?></h1>
<div class="tabs">
	<div class="tab-head">
         <a href="#lot">Lot</a>
         <a href="#owners">Owners</a>
         <a href="#households">Households</a>
	</div>
</div>
<div class="tab-body">
	<div id="lot">
		<?php echo Html::box("/bend-lot/editlot/".$lot->id, "Edit Lot", true); ?>
		<p></p>
		<div class="row-fluid small-12">
		<?php echo $lotTable?>
		</div>
	</div>
	<div id="owners">
		<h2>Current Owners</h2>
		<?php echo Html::box("/bend-lot/editlotowner/".$lot->id, "Add Lot Owner", true); ?>
		<p></p>		
		<?php if (!empty($owners)):?>
		<table width="80%">
			<thead>
		       <tr>
		            <th>Name</th>
					<th>Owner From</th>
					<th>Owner To</th>
		            <th>Actions</th>
		        </tr>
			</thead>
		    <tbody>
				<?php foreach ($owners as $owner) { 
					if ($owner->isCurrent()) {
						$contact = $owner->getContact();?>
				<tr>
					<td><?php echo $contact->getFullName()?></td>
					<td><?php echo formatDate($owner->d_start)?></td>
					<td><?php echo formatDate($owner->d_end)?></td>
					<td>
						<?php echo Html::box("/bend-lot/editlotowner/{$lot->id}/{$owner->id}", "Edit", true);?>
						<?php echo Html::b("/bend-lot/deletelotowner/{$lot->id}/{$owner->id}", "Remove", "Are you certain to remove this owner?");?>
					</td>
				</tr>
		        <?php }} ?>
			</tbody>
		</table>
		
		<h2>Past Owners</h2>
		<table width="80%">
			<thead>
		       <tr>
		            <th>Name</th>
					<th>Owner From</th>
					<th>Owner To</th>
		            <th>Actions</th>
		        </tr>
			</thead>
		    <tbody>
				<?php foreach ($owners as $owner) { 
					if (!$owner->isCurrent()) {
						$contact = $owner->getContact();?>
				<tr>
					<td><?php echo $contact->getFullName()?></td>
					<td><?php echo formatDate($owner->d_start)?></td>
					<td><?php echo formatDate($owner->d_end)?></td>
					<td><?php echo Html::box("/bend-lot/editlotowner/{$lot->id}/{$owner->id}", "Edit", true);?></td>
				</tr>
		        <?php }} ?>
			</tbody>
		</table>
		<?php endif;?>
	</div>
	<div id="households">
		<?php echo Html::box("/bend-household/edit/".$lot->id, "Add Household", true); ?>
		<p></p>
		<?php if (!empty($households)):?>
		<table width="80%">
			<thead>
		       <tr>
		            <th>Street Number</th>
					<th>CHL?</th>
					<th>Occupied?</th>
		            <th>Actions</th>
		        </tr>
			</thead>
		    <tbody>
				<?php foreach ($households as $household) {?> 
				<tr>
					<td><?php echo $household->streetnumber?></td>
					<td><?php echo $household->is_chl ? "yes" : "no"?></td>
					<td><?php echo $household->is_occupied ? "yes" : "no"?></td>
					<td><?php echo Html::ab("/bend-household/show/{$lot->id}/{$household->id}", "Details", true);?>
					<?php echo Html::b("/bend-lot/deletehousehold/{$lot->id}/{$household->id}", "Remove", "Are you certain to remove this household?");?></td>
				</tr>
		        <?php } ?>
			</tbody>
		</table>
		<?php endif;?>
	</div>
</div>

