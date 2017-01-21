<?php
function editlotowner_GET(Web $w) {
	list($lotId,$lotOwnerId) = $w->pathMatch("lotId","lotOwnerId");
	if (empty($lotId)) {
		$w->out("no lot id provided"); return;
	}
	$lot = $w->Bend->getLotForId($lotId);
	$lotOwner = new BendLotOwner($w);
	$user = new User($w);
	$contact = new Contact($w);
	if (!empty($lotOwnerId)) {
		$lotOwner = $w->Bend->getBendLotOwnerForId($lotOwnerId);
		$user = $lotOwner->getUser();
		$contact = $lotOwner->getContact();
	}

	$form["Lot"]=array(
			array(
					array("Lot Number", "static", "", $lot->lot_number),
					array("Occupancy", "static", "", $lot->occupancy),
			),
			array(
					array("Owner From","date","d_start",!empty($lotOwner->d_start) ? formatDate($lotOwner->d_start) : ""),
					array("Owner To","date","d_end",!empty($lotOwner->d_end) ? formatDate($lotOwner->d_end) : ""),
			)
	);
	
	$form["Owner Contact"] = array(
			array(
					empty($lotOwner->user_id) ?
					array("Select Existing User", "select", "user_id", null, $w->Auth->getUsers()) :
					array("User","static","",$lotOwner->getContact()->getFullName())
			),
			array(
					array("First Name", "text", "firstname", $contact->firstname),
					array("Last Name", "text", "lastname", $contact->lastname),
					array("Email", "text", "email", $contact->email),
			),
			array(
					array("Home Phone", "text", "homephone", $contact->homephone),
					array("Work Phone", "text", "workphone", $contact->workphone),
					array("Mobile Phone", "text", "mobile", $contact->mobile),
			),
	);
	
	$form["Owner Address"]=array(
			array(
					array("Address1", "text", "address1", $lotOwner->address1),
					array("Address2", "text", "address2", $lotOwner->address2),
			),
			array(
					array("Town", "text", "town", $lotOwner->town),
					array("Postcode", "text", "postcode", $lotOwner->postcode),
					array("State", "select", "state", $lotOwner->state,getStateSelectArray()),
			),
	);
	
	$w->ctx("form",Html::multiColForm($form, "/bend-lot/editlotowner/{$lotId}/{$lotOwnerId}#owners", "POST", "Save"));

}

function editlotowner_POST(Web $w) {
	list($lotId,$lotOwnerId) = $w->pathMatch("lotId","lotOwnerId");
	if (empty($lotId)) {
		$w->out("no lot id provide"); return;
	}
	$lot = $w->Bend->getLotForId($lotId);
	$lotOwner = new BendLotOwner($w);
	$user = new User($w);
	$contact = new Contact($w);
	if (!empty($lotOwnerId)) {
		$lotOwner = $w->Bend->getBendLotOwnerForId($lotOwnerId);
	}
	
	$lotOwner->fill($_POST);
	$lotOwner->bend_lot_id = $lotId;
	$lotOwner->insertOrUpdate();
	
	if ($lotOwner->user_id) {
		$user = $lotOwner->getUser();
		$contact = $user->getContact();
	}
	
	$contact->fill($_POST);
	$contact->insertOrUpdate();
	
	// create a new user if necessary
	if (empty($user->id)) {
		$user->contact_id = $contact->id;
		$user->login = $contact->email;
		$user->is_active = 1;
		$user->redirect_url = "bend";
		$user->insert();
		
		// assign to bend users group
		$group = $w->Auth->getUserForLogin("Bend Users");
		if (empty($group)) {
			// create the group!
			$group = new User($w);
			$group->is_group = 1;
			$group->login = "Bend Users";
			$group->insert();
		}
		$gu = new GroupUser($w);
		$gu->group_id = $group->id;
		$gu->user_id = $user->id;
		$gu->role = "member";
		$gu->insert();

		$lotOwner->user_id = $user->id;
		$lotOwner->update();
	}

	$w->msg("Lot Owner updated","/bend-lot/showlot/{$lotId}#owners");
}