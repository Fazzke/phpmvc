<?php

    /****** EIGENS ERSTELLTE KONFIGURATIONS-WERTE *******/

    /* Zusaetzlich noch URL in der public/.htaccess aendern! */

    /* Ermittle die APP_ROOT_URL und definiere eine Konstante */
    /* Mit der APP_ROOT_URL kannst du sie über APP_ROOT_URL */
    /* von ueberall einfach aufrufen */ 

    /* mit dirname(__FILE__) bekommst du den aktuellen Folder in dem diese Datei hier liegt */
    /* Der Folder ist config, mit einem weiteren dirname bekommst du den Folder app */

define('APP_ROOT_URL', dirname(dirname(__FILE__)));

    /* Definiere die BASE_URL, Achtung: Hardcoded! */
define('BASE_URL', 'http://localhost/shareposts');

    /* Definiere den WEBSEITENNAMEN, Achtung: Hardcoded! */
define('SITENAME', 'Shareposts');

    /* Definiere die Datenbank-Parameter */
    // Normalerweise immer localhost, es dei denn der DB Server ist remote
define('DB_HOST', 'localhost');

define('DB_USER', 'root');

define('DB_NAME', 'sharepostsdb');

define('DB_PW', '123456');

define('APP_VERSION', '1.0.0');

