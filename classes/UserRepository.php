<?php
//include 'Database.php';


class UserRepository
{
    private $con; // the DB connection

    public function __construct($con)
    {
         $this->con = Database::getConnection();// assign the DB connection
    }

    public function createUser(string $userName, string $password) : User
    {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $ps = $this->con->prepare("INSERT INTO user(userName, password)VALUES (?, ?)");
        $ps->bind_param("ss", $userName, $hashedPassword);
        $ps->execute();

        if ($ps->affected_rows > 0)
            return new User($userName, $password);
        else
            throw new Exception("Error in creating user");
    }

    public function findByUsername($userName) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM user WHERE userName = ?");
        $stmt->bind_param("s", $userName);
        $stmt->execute();
        $result = $stmt->get_result();

        // Add some debug prints here
        if (!$result) {
            echo 'Query failed: ', $db->error;
        } else {
            echo 'Query success. Row count: ', $result->num_rows;
        }

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new User($row['userName'], $row['password']);
        } else {
            return null;
        }
    }



}