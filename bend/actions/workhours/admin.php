<?php

function admin_ALL(Web $w) {
    History::add("Workhours Admin");
    $w->ctx("workperiods",$w->Bend->getAllWorkPeriods());
    $w->ctx("focusgroups",$w->Bend->getTopLevelWorkCategories());
    $w->enqueueStyle(["uri" => "/modules/bend/assets/css/bend.css", "weight" => 500]);
    
}