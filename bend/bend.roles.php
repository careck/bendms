<?php

function role_bend_admin_allowed(Web $w, $path) {
    return startsWith($path, "bend");
}

function role_bend_user_allowed(Web $w, $path) {
    return 
    	$w->checkUrl($path, "bend", "workhours", [
    			"index",
    			"editworkentry",
    			"enter",
    			"ajax_getchildcategories",
    			"ajax_gethouseholds",
    			"list"]) || 
    	$w->checkUrl($path, "bend", "electricity", [
    			"index"]) ||
    	$w->checkUrl($path, "bend", "", "index");
}
