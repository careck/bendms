<?php
function index_ALL(Web $w) {
	History::add("Bend Lots");
	$w->ctx("lots",$w->Bend->getAllLots());
}