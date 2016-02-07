<h2>Bend Lot Management</h2>
<?php echo Html::box("/bend-lot/editlot", "Add Lot", true); ?>
<p></p>
<?php if (!empty($lots)): ?>
    <table width="80%">
        <thead>
            <tr>
                <th width="10">Lot Number</th>
                <th width="10">Occupancy</th>
                <th>Current Owners</th>
                <th>Households</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lots as $lot): ?>
                <tr>
                    <td><?php echo $lot->lot_number?></td>
                    <td><?php echo $lot->occupancy?></td>
                    <td>
                        <?php
                        $owners = $lot->getCurrentOwners();
                        if (!empty($owners)) {
                            foreach ($owners as $owner) {
                                echo $owner->getFullname() . "<br/>";
                            }
                        }
                        ?>
                    </td>
                    <td>                        
                    	<?php
                        $households = $lot->getAllHouseholds();
                        if (!empty($households)) {
                            foreach ($households as $house) {
                                echo $house->streetnumber .
                                ($house->is_chl == 1 ? " (CHL) " : "") . "<br/>";
                            }
                        }
                        ?>
					</td>
					<td>
						<?php echo Html::b("/bend-lot/showlot/" . $lot->id, "Details");?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


