<?php

/**
 * inject font awesome and our css for every action in the bend module
 * 
 * @param Web $w
 */
function bend_core_web_after_get_bend(Web $w) {
	$w->enqueueStyle(["uri" => "/modules/bend/assets/css/bend.css", "weight" => 500]);
	$w->enqueueStyle(["uri" => "/modules/bend/assets/css/font-awesome.min.css", "weight" => 501]);
}