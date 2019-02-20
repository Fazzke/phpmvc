<?php

/********** IMMER WIEDER ZU VERWENDENDER CODE WIRD ZU HELPER *********/


/* URL-Rewrite, in dem nur noch die URL-Endung in der Funktion redirect() uebergeben werden muss  */
function redirect($page) {
    header('location: '.BASE_URL.'/'.$page);
}