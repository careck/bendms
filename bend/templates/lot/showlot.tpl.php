<h1>Lot <?php echo $lot->lot_number?></h1>
<?php echo Html::box("/bend-lot/editlot/".$lot->id, "Edit Lot", true); ?>
<div class="row-fluid small-12">
<?php echo $lotTable?>
</div>
<hr/>
<h2>Current Owners</h2>
<?php echo Html::box("/bend-lot/editlotowner/".$lot->id, "Add Lot Owner", true); ?>

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
			<td><?php echo Html::box("/bend-lot/editlotowner/{$lot->id}/{$owner->id}", "Edit", true);?></td>
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

<hr/>
<h2>Households</h2>
<?php echo Html::box("/bend-lot/edithousehold/".$lot->id, "Add Household", true); ?>
