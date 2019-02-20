<?php

    // Lade Config
    require_once 'config/Config.php';

    // Lade Helpers
    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';  //so wird immer ein session_start herbeigefuehrt!


    /**** Autoloader von Libraries ***/
    spl_autoload_register(function($className) {
        require_once 'libraries/'.$className.'.php';
    });
