<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class SqlConn {
    private static $mysqli;

private static function dbconnect_master() {
    self::$mysqli = new mysqli("localhost", "master", "master-password", "telefonbuch");
    if (self::$mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }
}

private static function dbconnect_slave() {
    self::$mysqli = new mysqli("192.168.56.100", "slave", "slave-password", "telefonbuch");
    if (self::$mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }
}

private static function dbclose(): void {
    if(self::$mysqli){
        self::$mysqli->close();
    }
}

public static function add(){
    self::dbconnect_master();
        $stmt = self::$mysqli->prepare("INSERT INTO contacts (vorname, nachname, phone, email) VALUES (?, ?, ?, ?)");
        if($stmt){
            $vorname = $_POST['vorname'];
            $nachname = $_POST['nachname'];
            $phone = $_POST['phone'];
            $email = isset($_POST['email']) ? $_POST['email'] : NULL;
            $stmt->bind_param("ssss", $vorname, $nachname, $phone, $email);
            if($stmt->execute()){
                echo "Neuer Kontakt erfolgreich hinzugefügt!";
            } else {
                echo "Fehler: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Fehler: " . $mysqli->error;
        }
        self::dbclose();
}

public static function delete(){
    self::dbconnect_master();
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ungültige ID.");
    }
    $id = (int) $_GET['id'];
    $stmt = self::$mysqli->prepare("DELETE FROM contacts WHERE id = ?");
    if($stmt){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            echo "Kontakt $id erfolgreich gelöscht!";
            var_dump($stmt);
            header("Location: index.php");
        } else {
            echo "Fehler: " . $stmt->error;
        }
        // header("Location: index.php");
    } else {
        echo "Fehler: " . $mysqli->error;
    }
    self::dbclose();
}

public static function read(){
    self::dbconnect_slave();
    $stmt = self::$mysqli->prepare("SELECT * FROM contacts");
    if (!$stmt) {
        die("Prepare failed: " . self::$mysqli->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    self::dbclose();
    return $result;
}
public static function update(){
    self::dbconnect_master();
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("Ungültige ID.");
    }
    $id = (int) $_GET['id'];
        $stmt = self::$mysqli->prepare("UPDATE contacts SET vorname=?, nachname=?, phone=?, email=? WHERE id=?");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($stmt){
            $stmt->bind_param("ssssi", $vorname, $nachname, $phone, $email, $id);
            $vorname = $_POST['vorname'];
            $nachname = $_POST['nachname'];
            $phone = $_POST['phone'];
            $email = isset($_POST['email']) ? $_POST['email'] : NULL;
            if($stmt->execute()){
                echo "Kontakt $id erfolgreich aktualisiert!";
                /*var_dump($stmt);*/
                header("Location: index.php");
            } else {
                echo "Fehler1: " . $stmt->error;
            }
        } else {
            echo "Fehler2: " . $mysqli->error;
        }
}
    $result = self::$mysqli->query("SELECT * FROM contacts WHERE id = $id LIMIT 1");
    if ($result->num_rows === 0) {
        die("Kontakt nicht gefunden.");
    }
    $contact = $result->fetch_assoc();
    self::dbclose();
    return $contact;
}
}
?>
