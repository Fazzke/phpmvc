<?php
    class Pages extends Controller {
        public function __construct(){

            /* Lade das model Post.php */

        }

          /* Du brauchst immer eine Index-Methode, die ist in den Server- */
          /* Einstellungen als Default gesetzt. Wenn du keine hast wird ein Fehler */
          /* ausgegeben, wenn du einen Controller ohne Methode aufrufst */ 
        public function index() {

            if(isLoggedIn()) {
                redirect('posts');
            }
            
            //DB poststest, vor den data, damit das ergebnis in den data mituebergeben werden kann

              /* Wir erben von der Controller-Class die Moegl. views aufzurufen */
              /* Und Daten in einem Assoz. Array $data zu uebergeben */
              /* Aufruf erfolgt im View ueber $data['key'] */
            $data = [
                'title' => 'SharePosts',
                'description' => 'Simple Soziale-Netzwerk-Übung nach TraversyMVC',
            ];

            $this->view('pages/index', $data);

        }

        public function about() {


            $data = [
                'title' => 'About',
                'description' => 'Ich bin Fazzke und ich bin nett (:',
            ];


            $this->view('pages/about', $data);
        }
    }