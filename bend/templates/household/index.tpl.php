<h2>Bend Households</h2>
<p></p>
<?php if (!empty($households)): ?>
    <table width="80%">
        <thead>
            <tr>
                <th width="10">Street Number</th>
                <th width="10">CHL</th>
                <th width="10">Occupied?</th>
                <th>Current Occupants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($households as $household): ?>
                <tr>
                    <td><?php echo $household->streetnumber?></td>
                    <td><?php echo $household->is_chl ? "CHL" : ""?></td>
                    <td><?php echo $household->is_occupied ? "yes" : ""?></td>
                    <td>
                        <?php
                        $occupants = $household->getCurrentOccupants();
                        if (!empty($occupants)) {
                            foreach ($occupants as $oc) {
                                echo $oc->getFullname() . ", ";
                            }
                        }
                        ?>
                    </td>
					<td>
						<?php echo Html::b("/bend-household/show/" .$household->getLot()->id.'/'. $household->id, "Details");?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


