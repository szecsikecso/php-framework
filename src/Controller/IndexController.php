<?php


namespace Homework3\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class IndexController
{

    private $indexTwig;

    public function __construct()
    {
        $this->indexTwig = new Environment(new FilesystemLoader('../views'), ['debug' => true]);
        $this->indexTwig->addExtension(new DebugExtension());
    }

    public function index($message = '') {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        $loggedIn =  false;
        $loginName = '';
        if (isset($_SESSION) && isset($_SESSION['login_name']) && !empty($_SESSION['login_name'])) {
            $loggedIn = true;
            $loginName = $_SESSION['login_name'];
        }

        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
        }

        if (!empty($message)) {
            echo $this->indexTwig->render('index/index.html.twig',
                ['logged_in' => $loggedIn, 'login_name' => $loginName, 'response' => ['message' => $message]]
            );

        } else {
            echo $this->indexTwig->render('index/index.html.twig',
                ['logged_in' => $loggedIn, 'login_name' => $loginName, 'response' => []]
            );
        }
    }

    public function handleOperation(string $operation, $id = 0) {
        if ($operation == 'login') {
            $this->login();
        }else if ( $operation == 'logout') {
            $this->logout();
        } else {
            $this->handle404();
            //echo $this->indexTwig->render('index/index.html.twig', ['operation' => $operation]);
        }
    }

    private function login() {
        session_start();
        $loggedIn = isset($_SESSION) && isset($_SESSION['login_name']) && !empty($_SESSION['login_name']);

        if (!$loggedIn) {
            if (isset($_POST) && !empty($_POST)) {
                $_SESSION['login_name'] = strip_tags($_POST['name']);
                $_SESSION['message'] = 'Successful login!';
                header("Location:/");
            } else {
                echo $this->indexTwig->render('index/login.html.twig');
            }
        } else {
            $_SESSION['message'] = 'Already logged in!';
            header("Location:/");
        }
    }

    private function logout(){
        session_start();
        session_destroy();

        session_start();
        $_SESSION['message'] = 'Successful logout!';
        header("Location:/");
    }

    public function handle400() {
        echo $this->indexTwig->render('index/error.html.twig', ['error_code' => 400]);
    }

    public function handle401() {
        echo $this->indexTwig->render('index/error.html.twig', ['error_code' => 401]);
    }

    public function handle404() {
        echo $this->indexTwig->render('index/error.html.twig', ['error_code' => 404]);
    }

    public function handle405() {
        echo $this->indexTwig->render('index/error.html.twig', ['error_code' => 405]);
    }

}