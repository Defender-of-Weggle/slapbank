<?php
class LoginClass{

    private $userRepository;

    public function __construct
    (UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login($username, $password) {
        $user = $this->userRepository->findByUsername($username);
        $hashedPassword = "";
        if ($user) {
            $hashedPassword = $user->getPassword();
            var_dump($hashedPassword);
        }
        if ($user && password_verify($password, $hashedPassword)) {
            // The password is correct.
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['login'] = $username;
            return true;
        }
        return false;
    }

}