<?php


namespace Homework3\Controller;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class IndexController
{

    private $indexTwig;

    public function __construct()
    {
        $this->indexTwig = new Environment(new FilesystemLoader('../views'));
    }

    public function index() {
        echo $this->indexTwig->render('index/index.html.twig', ['hello' => 'world']);
    }

    public function handleOperation(string $operation, $id = 0) {
        echo $this->indexTwig->render('index/index.html.twig', ['operation' => $operation]);
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