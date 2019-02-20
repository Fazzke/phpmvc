<?php

/****** PDO - Datenbank-Klasse ******/
/* Verbindet sich zu der Datenbank */
/* Wird vorbereitete SQL_Statements ausfuehren */
/* bind values */
/* liefert Datensätze zurück */

/***** PDO Reihenfolge ********/
// 1. prepare($sqlStatement) 
// 2. bindvalue($derInhalt :inhalt, $dieVariable $inhalt, $derTyp zb String)
// 3. execute()
// 4. fetch()

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PW;
    private $dbname = DB_NAME;

    private $dbhandler;
    private $statement;
    private $error;

    public function __construct() {

          // Data Source Network
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
          // PDO Optionen, muessen VOR/MIT der DB_Instanzierung erfolgen um zu wirken
        $options = array(
              // Pers.Conn werden nur pro Session benutzt
            PDO::ATTR_PERSISTENT => true,
              // Achte auf einen sicheren Error-Mode (silent, warning, exception)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

          /******* Erstelle eine PDO Instanz *******/

        try {
            $this->dbhandler = new PDO($dsn, $this->user, $this->pass, $options);
        } 
        catch(PDOException $e) {
            $this->error = $e->getMessage();
              // Spaeter exception.log erstellen
            echo $this->error;
        }
    }

      /***** Bereite das SQL-Statement vor  ********/
    public function query($sql) {
        $this->statement = $this->dbhandler->prepare($sql);
    }

      /************* Bind Values **********/
      /* $parameter = der Wert :value im SQL-Statement */
      /* $value = der $value - die Variable */
      /* Der Datentyp, zb PDO::PARAM_INT fuer einen Integer-Wert, hier default NULL, */
      /* denn wir wollen den Typen automatisch ermitteln und brauchen null als startwert */
    public function bind($parameter, $value, $type = null) {

          // Pruefen des Value-Types und automatisches Setzen des PDO-types
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                $type = PDO::PARAM_INT;
                break;

                case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;

                case is_null($value):
                $type = PDO::PARAM_NULL;
                break;

                default:
                $type = PDO::PARAM_STR;
            }
        }

        $this->statement->bindValue($parameter, $value, $type);
    }


      /*********** Nutze die PDO::execute() um dein SQL Statement auszufuehren ***********/

    public function execute() {
        return $this->statement->execute();
    }


      /*********** Liefere die Abgefragewerte zurueck  ***********/

      // GET die Resultate der Abfrage als Objekt-Array
    public function setResult() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

      // GET ein EINZELNES Resultat der Abfrage als Objekt-Array
    public function singleResult() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

      // Get row count
    public function numRows() {
        return $this->statement->rowCount();
    }

}