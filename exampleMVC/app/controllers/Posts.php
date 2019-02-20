<?php 
    class Posts extends Controller {

        public function __construct() {
            if(!isLoggedIn()) {

                  // kein Zugriff auf die Posts/Nachrichten Seite, wenn nicht eingeloggt 
                redirect('users/login');
            }

            $this->postModel = $this->model('Post');
              
              //Um in der show() ez auf die User-Daten zuzugreifen 
            $this->userModel = $this->model('User');
        }

        public function index() {
            // Get posts
            $posts = $this->postModel->getPosts();

            $data = [
                'posts' => $posts,
            ];

            $this->view('posts/index', $data);
        }

        public function add() {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'title' => trim($_POST['title']),
                    'title_error' => '',
                    'nachricht' => trim($_POST['nachricht']),
                    'nachricht_error' => '',
                    'user_id' => $_SESSION['user_id'],            
                ];

                  // Überprüfe, ob Eingabe erfolgte
                if(empty($data['title'])) {
                    $data['title_error'] = "Bitte gib einen Titel ein.";
                }

                if(empty($data['title'])) {
                    $data['nachricht_error'] = "Bitte gib eine Nachricht ein.";
                }

                  // Überprüfe, ob Fehlerfrei
                if(empty($data['title_error']) && empty($data['nachricht_error'])) {

                    if($this->postModel->addPost($data)) {
                        flash('post_message', 'Nachricht hinzugefügt');
                        redirect('posts');
                    } else {
                        die('Hoppla. Da ist etwas schief gegangen.');
                    }

                } else {
                    $this->view('posts/add', $data);
                }
            
            }           
            else {
                $data = [
                    'title' => '',
                    'title_error' => '',
                    'nachricht' => '',
                    'nachricht_error' => '',            
                ];
            }
            $this->view('posts/add', $data);
        }

          // beispiel: posts/show/15
        public function show($id) {
            $post = $this->postModel->getPostById($id);
              //verzichte hiermit auf ne Join
            $user = $this->userModel->getUserById($post->user_id);


            $data = [
                'post' => $post,
                'user' => $user,
            ];

            $this->view('posts/show', $data);
        }


        public function edit($id) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'title' => trim($_POST['title']),
                    'title_error' => '',
                    'nachricht' => trim($_POST['nachricht']),
                    'nachricht_error' => '',
                    'id' => $id,            
                ];

                  // Überprüfe, ob Eingabe erfolgte
                if(empty($data['title'])) {
                    $data['title_error'] = "Bitte gib einen Titel ein.";
                }

                if(empty($data['title'])) {
                    $data['nachricht_error'] = "Bitte gib eine Nachricht ein.";
                }

                  // Überprüfe, ob Fehlerfrei
                if(empty($data['title_error']) && empty($data['nachricht_error'])) {

                    if($this->postModel->updatePost($data)) {
                        flash('post_message', 'Deine Nachricht wurde bearbeitet');
                        redirect('posts');
                    } else {
                        die('Hoppla. Da ist etwas schief gegangen.');
                    }

                } else {
                    $this->view('posts/edit', $data);
                }
            
            }           
            else {
                  //greife auf bereits bestehende Methode zum Abfragen der Nutzer Id zurueck
                $post = $this->postModel->getPostById($id);

                  /*ein falscher User kann zwar den Button zum editieren erst gar nicht sehen,
                    koennten aber trotzdem ueber URL aufrufen */
                if($post->user_id != $_SESSION['user_id']) {
                    redirect('posts');
                }

                $data = [
                    'id' => $id,
                    'title' => $post->title,
                    'nachricht' => $post->nachricht,      
                ];
            }
            $this->view('posts/edit', $data);
        }

        public function delete($id) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                  //greife auf bereits bestehende Methode zum Abfragen der Nutzer Id zurueck
                  $post = $this->postModel->getPostById($id);

                  /*ein falscher User kann zwar den Button zum loeschen erst gar nicht sehen,
                    koennten aber trotzdem ueber URL den Loeschvorgang initiieren */
                if($post->user_id != $_SESSION['user_id']) {
                    redirect('posts');
                }


                if($this->postModel->deletePost($id)) {
                    flash('post_message', 'Deine Nachricht wurde gelöscht');
                    redirect('post');
                } else {
                    die('Irgendwas ist schief gelaufen');
                }
                $data = [
                    'id' => $id,            
                ];


            } else {
                redirect('posts');
            }
        }

    }