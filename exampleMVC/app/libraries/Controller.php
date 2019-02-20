<?php

/* BASIS-Controller, enthaelt alle Methoden um ein Model und View zu laden */ 
/* Alle Controller im controller-dir erben von dieser Mutter-Controller-Klasse */
/* Alle Controller haben somit Zugriff auf die Methoden view() und model() */

    class Controller {

        public function model($model) {
            require_once '../app/models/'.$model.'.php';

            // Instanziere das Model
            return new $model();
        }


          /*** Der View wird geladen und die variablen Daten werden an den View uebergeben ***/
          /* Die Daten im Array machen den View dynamisch */
          /* Die Daten werden aufgerufen ueber $data['assozAryKey'] */ 
        public function view($view, $data = []) {

            /* Ueberpruefe ob View File existiert */
            if(file_exists('../app/views/'.$view.'.phtml')) {
                require_once '../app/views/'.$view.'.phtml';
            }
            else {
                die("View does not exist");
            }
        }
    }