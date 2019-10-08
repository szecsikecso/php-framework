<?php


namespace Homework3\Controller;


use Homework3\Service\HandleExpense;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ExpenseController
{

    private $expenseTwig;

    public function __construct()
    {
        $this->expenseTwig = new Environment(new FilesystemLoader('../views/expense'));
    }

    public function index()
    {
        echo $this->expenseTwig->render('index.html.twig', ['hello' => 'world']);
    }

    public function handleOperation(string $operation) {
        if ($operation == 'new') {
            $this->new();
        } else {
            echo $this->expenseTwig->render('index.html.twig', ['operation' => $operation]);
        }
    }

    private function new() {
        $service = new HandleExpense();
        $service->addExpense(1, 'HUF', 'bla bla bla');
    }

}