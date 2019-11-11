<?php


namespace Homework3\_Framework;

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

    public function indexAction($message = '') {
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

    /*
    public function handleAuthorization(string $operation) {
        if ($operation == 'login') {
            $this->login();
        }else if ( $operation == 'logout') {
            $this->logout();
        } else {
            $this->handle403('authorization error');
        }
    }
    */

    public function loginAction() {
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

    public function logoutAction(){
        session_destroy();

        session_start();
        $_SESSION['message'] = 'Successful logout!';
        header("Location:/");
    }

    public function handle400(string $message = 'unknown error') {
        echo $this->indexTwig->render('index/error.html.twig',
            ['error_code' => 400, 'error_message' => $message]);
    }

    public function handle401(string $message = 'unknown error') {
        echo $this->indexTwig->render('index/error.html.twig',
            ['error_code' => 401, 'error_message' => $message]);
    }

    public function handle404(string $message = 'unknown error') {
        echo $this->indexTwig->render('index/error.html.twig',
            ['error_code' => 404, 'error_message' => $message]);
    }

    public function handle405(string $message = 'unknown error') {
        echo $this->indexTwig->render('index/error.html.twig',
            ['error_code' => 405, 'error_message' => $message]);
    }

}