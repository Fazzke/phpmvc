<?php 

/*Diese Klasse weiß nicht, was view() ist, solange sie nicht von den Controller Klasse erbt/extends*/

    class Users extends Controller{
        public function __construct() {
            //greife im Konstruktor auf ein Model zu, erhalte Zugriff auf Methoden des Konstruktors
          $this->userModel = $this->model('User');

        }

        public function register() {

              // Entweder, der User submitet ...
            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                  // sorgt dafuer, dass alle Posts zu Strings werden
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                  // Initialisiere die POSTS
                $data = [
                    'name' => trim($_POST['name']),  //trim entfernt alle whitespaces
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => '',
                ];


                  // Validiere E-Mail
                if(empty($data['email']) || !isset($data['email'])) {
                    $data['email_error'] = "Bitte gib eine gültige E-Mail-Adresse ein.";
                } else {
                    if($this->userModel->findUserByEmail($data['email'])) {
                        $data['email_error'] = "Diese Mailadresse ist vergeben";
                    }
                }

                  // Validiere Name
                if(empty($data['name']) || !isset($data['name'])) {
                    $data['name_error'] = "Bitte gib einen gültigen Namen ein.";
                }

                  // Validiere Passwort
                if(empty($data['password']) || !isset($data['password'])) {
                    $data['password_error'] = "Bitte gib ein gültiges Passwort ein.";
                } elseif(strlen($data['password']) < 6) {
                    $data['password_error'] = "Das Passwort muss mindestens 6 Zeichen enthalten";
                }

                  // Validiere Confirm-Passwort
                if(empty($data['confirm_password']) || !isset($data['confirm_password'])) {
                    $data['confirm_password_error'] = "Bitte bestätige dein Passwort.";
                } elseif($data['password'] !== $data['confirm_password']) {
                    $data['confirm_password_error'] = "Die Passwörter sind nicht identisch.";
                }

                  // Vergewissere dich, dass die Errors empty sind, dann ist Zugang erfolgreich
                if(empty($data['email_error']) && empty($data['name_error']) 
                && empty($data['password_error']) && empty($data['confirm_password_error'])) {

                  // HASH HASHE PW
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                          
                // Schreibe die Daten in die Datenbank, nutze die register() Methode im Model
                if($this->userModel->register($data)) {
                  flash('register_success', 'Du hast dich erfolgreich registriert und kannst dich nun einloggen.');
                  redirect('users/login');
                } else {
                  die('Da ist was schief gegangen.');
                }

                } else {
                      //Load View mit Errors, ohne wirst du nach submit kein GUI erhalten
                    $this->view('users/register', $data);
                }
            }


              // ...oder er fuellt Daten im Formular aus und submittet vllt erst dann
            else {
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => '',
                ];

                //Load View
                $this->view('users/register', $data);
            }
        }



        public function login() {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                  // sorgt dafuer, dass alle Posts zu Strings werden
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                  // Initialisiere die POSTS
                $data = [
                    'email' => trim($_POST['email']), //trim entfernt alle whitespaces
                    'password' => trim($_POST['password']),
                    'email_error' => '',
                    'password_error' => '',
                ];


                  // Validiere E-Mail
                if(empty($data['email']) || !isset($data['email'])) {
                    $data['email_error'] = "Bitte gib eine gültige E-Mail-Adresse ein.";
                }
                // Überprüfe, ob die Mail in der DB existizzelt
                if($this->userModel->findUserByEmail($data['email'])) {
                  
                } else {
                  $data['email_error'] = "Diese Email-Adresse existiert hier nicht.";
                }

                  // Validiere Passwort
                if(empty($data['password']) || !isset($data['password'])) {
                    $data['password_error'] = "Bitte gib ein gültiges Passwort ein.";
                } elseif(strlen($data['password']) < 6) {
                    $data['password_error'] = "Das Passwort muss mindestens 6 Zeichen enthalten";
                }

                  // Vergewissere dich, dass die Errors empty sind, dann ist Zugang erfolgreich
                if(empty($data['email_error']) && empty($data['password_error']) ) {

                      //Prüfen ob Validierung erfolgreich
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                      //Entweder wird ein false returned oder ein true
                    if($loggedInUser) {
                      //Eine Session Aufbauen mit eingeloggtem User 
                      $this->createUserSession($loggedInUser);
                      
                    } else {
                        // Die Email wurde bereits auf existenz ueberprueft, also kann nur das PW falsch sein
                      $data['password_error'] = "Das Passwort ist nicht korrekt.";

                      $this->view('users/login', $data);
                    }


                } else {
                      //Load View mit Errors, ohne wirst du nach submit kein GUI erhalten
                    $this->view('users/login', $data);
                }
            }
            else {
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                ];

                //Load View
                $this->view('users/login', $data);
            }
        }

    
    public function createUserSession($loggedInUser) {
        //$loggedInUser->id ist einfach eine matainformation, die du dir mit var_dump anzeigen lassen kannst
      $_SESSION['user_id'] = $loggedInUser->id;
      $_SESSION['user_email'] = $loggedInUser->email;
      $_SESSION['user_name'] = $loggedInUser->name;
      redirect('posts');
    }

    public function logout() {
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('users/login');
    }
    
    }