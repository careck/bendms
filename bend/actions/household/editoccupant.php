<?php
function editoccupant_GET(Web $w) {
	list($householdid, $occupantid) = $w->pathMatch("a","b");
	if (empty($householdid)) $w->error("Need a household ID");
	
	$household = $w->Bend->getHouseholdForId($householdid);
	if (empty($household)) $w->error("Household not found");
	
	$oc = new BendHouseholdOccupant($w);
	$contact = new Contact($w);
	if (!empty($occupantid)) {
		$oc = $w->Bend->getHouseholdOccupantForId($occupantid);
		$contact = $oc->getContact();
	}
	$form["Household"]=array(
			array(
					array("Street Number", "static", "", $household->streetnumber),
					array("Is CHL?", "static", "", $household->is_chl?"yes":"no"),
					array("Is Occupied?", "static", "", $household->is_occupied?"yes":"no"),
			),
	);
	$form["Occupant"]=array(
			array(
					array("Occupant From","date","d_start",!empty($oc->d_start) ? formatDate($oc->d_start) : ""),
					array("Occupant To","date","d_end",!empty($oc->d_end) ? formatDate($oc->d_end) : ""),
			),
			array(
					array("Pays Electricity?", "select", "pays_electricity", $oc->pays_electricity,lookupForSelect($w, "YesNo")),
					array("Does Workhours?", "select", "does_workhours", $oc->does_workhours,lookupForSelect($w, "YesNo")),
			),
	);
	$form["Occupant Contact"] = array(
			array(
					empty($oc->user_id) ?
					array("Select Existing User", "select", "user_id", null, $w->Auth->getUsers()) :
					array("User","static","",$oc->getFullName())
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
	$w->ctx("form",Html::multiColForm($form, "/bend-household/editoccupant/{$householdid}/{$occupantid}#occupants", "POST", "Save"));
	
}

function editoccupant_POST(Web $w) {
	list($householdid,$occupantid) = $w->pathMatch("a","b");
	if (empty($householdid)) {
		$w->out("no household id provide"); return;
	}
	$household = $w->Bend->getHouseholdForId($householdid);
	$oc = new BendHouseholdOccupant($w);
	$user = new User($w);
	$contact = new Contact($w);
	if (!empty($occupantid)) {
		$oc = $w->Bend->getHouseholdOccupantForId($occupantid);
	}
	
	$oc->fill($_POST);
	$oc->bend_household_id = $householdid;
	$oc->insertOrUpdate(true);
	
	if (!empty($oc->user_id)) {
		$user = $oc->getUser();
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
	
		$oc->user_id = $user->id;
		$oc->update();
	}
	
	$w->msg("Occupant updated","/bend-household/show/{$household->bend_lot_id}/{$householdid}#occupants");
}