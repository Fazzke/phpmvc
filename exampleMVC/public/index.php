<?php

/* index.php ist unser Eintrittspunkt */
/* Beliebt: Alle Requires in die index.php schreiben */
/* Hier: Stattdessen beziehen wir alle requires ueber die bootstrap.php */

require_once '../app/bootstrap.php';


/* Initialisiere die Core Library (die Core Klasse) */

$init = new Core;