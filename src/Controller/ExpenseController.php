<?php


namespace Homework3\Controller;


use Homework3\Entity\Expense;
use Homework3\Service\HandleExpense;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ExpenseController
{

    private $expenseTwig;
    private $expenseService;

    public function __construct()
    {
        $this->expenseTwig = new Environment(new FilesystemLoader('../views'));
        $this->expenseService = new HandleExpense();
    }

    public function index()
    {
        echo $this->expenseTwig->render('index.html.twig', ['hello' => 'world']);
    }

    public function handleOperation(string $operation) {
        switch ($operation) {
            case 'new':
                $this->new();
                break;
            case 'read':
                $this->read();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                echo $this->expenseTwig->render('index.html.twig', ['operation' => $operation]);
                break;
        }
    }

    private function new() {
        $this->expenseService->addExpense(1, 'HUF', 'bla bla bla');
    }

    private function read() {
        return new Expense();
    }

    private function update() {
        $this->read();
        $this->expenseService->updateExpense($this->read(), $this->read());
    }

    private function delete() {
        $this->expenseService->deleteExpense($this->read());
    }

}