<?php
class User
{
private $userName;
private $password;
private $userID;
private $profileText;
private $birthday;
private $hideAge;
private $userTitle;
private $contingent;
private $userRole;
private $tempUserRole;
private $dataLoaded = false;

    public function getUsername() {
        $this->loadOwnData();
        return $this->username;
    }

    public function getPassword() {
        $this->loadOwnData();
        return $this->password;
    }

    public function getProfileText() {
        $this->loadOwnData();
        return $this->profileText;
    }

    public function getBirthday() {
        $this->loadOwnData();
        return $this->birthday;
    }

    public function getHideAge() {
        $this->loadOwnData();
        return $this->hideAge;
    }

    public function getUserTitle() {
        $this->loadOwnData();
        return $this->userTitle;
    }

    public function getContingent() {
        $this->loadOwnData();
        return $this->contingent;
    }

    public function getUserRole() {
        $this->loadOwnData();
        return $this->userRole;
    }

    public function getTempUserRole() {
        $this->loadOwnData();
        return $this->tempUserRole;
    }

    private function loadOwnData() {
        if (!$this->dataLoaded) {
            // Erstellen Sie eine Datenbankverbindung mit PDO oder mysqli
            $db = Database::getConnection();

            // Eine SQL-Abfrage vorbereiten
            $stmt = $db->prepare('SELECT * FROM user WHERE userID = ?');

            // Die Abfrage mit der ID dieses Benutzers ausführen
            $stmt->execute([$this->userID]);

            // Die Daten abrufen
            $userData = $stmt->fetch();

            // Die Daten in die Eigenschaften des Objekts einfügen
            $this->username = $userData['userName'];
            $this->password = $userData['password'];
            $this->profileText = $userData['profileText'];
            $this->birthday = $userData['birthday'];
            $this->hideAge = $userData['hideAge'];
            $this->userTitle = $userData['userTitle'];
            $this->contingent = $userData['contingent'];
            $this->userRole = $userData['userRole'];
            $this->tempUserRole = $userData['tempUserRole'];

            $this->dataLoaded = true;
        }
    }



}