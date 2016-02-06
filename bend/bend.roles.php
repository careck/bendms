<?php

function role_bend_admin_allowed($w, $path) {
    return startsWith($path, "bend");
}

function role_bend_view_allowed($w, $path) {
    return false;
}
