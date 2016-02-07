<?php

function role_bend_admin_allowed($w, $path) {
    return startsWith($path, "bend");
}

function role_bend_user_allowed($w, $path) {
    return false;
}
