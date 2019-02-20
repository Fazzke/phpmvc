<?php

/****** Unsere Kern-Klasse ******/
/* Spaltet URLs auf, laedt Controller */
/* URL-Format - /controller/methode/param1/param2/  */

    class Core {
        protected $currentController = 'Pages';      /* Controller */
        protected $currentMethod = 'index';     /* Methode */
        protected $parameter = [];              /* parameter-Ary */

        
          /* Methode getUrl() bekommt die URL geliefert */
        public function __construct(){

            $url = $this->getUrl();

              /* Suche im /controllers Ordner nach der /controller URL */
              /*** ACHTUNG! Wir befinden uns in der index.php ***/
              /*** Pfad geht von der index.php zum /controller directory ***/
              /* ucwords Upercased Den Ersten Buchstaben Immer */
            if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')) {

                /* Wenn die Datei dort existiert, wird diese $currentController */
                $this->currentController = ucwords($url[0]);

                /* Unset den Controller, damit parameter auch bei 0 anfangen koennen */
                /* wenn diese gebraucht werden! $url[0] ="param1" $url[1] = "param2" */
                unset($url[0]);
            }
/*             else {
                die("controller ".$url[0]." does not exist. <br />");
            } */


              /*jetzt wissen wir ja welcher Controller requestet wird und laden ihn */
            require_once '../app/controllers/'.$this->currentController.'.php';

              /* Eine Instanz der geforderten Controller-Class erstellen */ 
            $this->currentController = new $this->currentController;



              /* Der zweite Teil der URL wird ermittelt, die Methode */
              
              /* Erst wird ueberprueft, ob die URL eine Methode beinhaltet */
            if(isset($url[1])) {
                if(method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);  
                }
            } 


            /* Der dritte Teil der URL wird ermittelt, die Parameter */

            /* Entweder werden die Array-Werte der URL ermittelt, oder */
            /* es gibt keine Werte, dann ist $url ein leeres Ary */
          $this->parameter = $url ? array_values($url) : [];

            /* Rueckruffunktion  */
            /*** Folgendes Muster:  Call the blubb() function with 2 arguments ***/
            /******call_user_func_array("blubb", array("one", "two"));*****/
          call_user_func_array([$this->currentController, $this->currentMethod], $this->parameter);

        }


        public function getUrl() {

                /*GET bekommt bereits die rewritete URL im Stil /controller/meth/param */            
              if(isset($_GET['url'])) {

                  /*entfernt den Slash am Ende der URL*/
                $url = rtrim($_GET['url'], '/');
                
                  /* entfernt alle ilegallen Zeichen im URL-String */
                $url = filter_var($url, FILTER_SANITIZE_URL);

                  /* Spaltet URL-String an der Stelle / und speichert Einzelteile als Array*/
                $url = explode('/', $url);
                return $url;
            }
        }
    }