<?php


namespace Homework3\Controller;


use Homework3\Entity\Expense;
use Homework3\Service\HandleExpense;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class ExpenseController
{

    private $expenseTwig;
    private $expenseService;

    public function __construct()
    {
        $this->expenseTwig = new Environment(new FilesystemLoader('../views'), ['debug' => true]);
        $this->expenseTwig->addExtension(new DebugExtension());
        $this->expenseService = new HandleExpense();

    }

    public function index()
    {
        $expenses = $this->expenseService->readAllExpense();

        echo $this->expenseTwig->render('expense/index.html.twig', ['expenses' => $expenses]);
    }

    public function handleOperation(string $operation, $expense_id = 0) {
        switch ($operation) {
            case 'new':
                $this->new();
                break;
            case 'read':
                $this->read($expense_id);
                break;
            case 'update':
                $this->update($expense_id);
                break;
            case 'delete':
                $this->delete($expense_id);
                break;
            default:
                echo $this->expenseTwig->render('expense/index.html.twig', ['operation' => $operation]);
                break;
        }
    }

    private function new() {
        if (isset($_POST) && !empty($_POST)) {
            var_dump($_POST);
            $amount = strip_tags($_POST['amount']);
            $currency = strip_tags($_POST['currency']);
            $description = strip_tags($_POST['description']);

            $response = $this->expenseService->addExpense($amount, $currency, $description);
            echo $this->expenseTwig->render('expense/new.html.twig', ['response' => $response]);
        } else {
            echo $this->expenseTwig->render('expense/new.html.twig');
        }
    }

    private function read($expense_id) {
        $expense = $this->expenseService->readExpense($expense_id);
        echo $this->expenseTwig->render('expense/read.html.twig', ['expense' => $expense]);
    }

    private function update($expense_id) {
        $expense = $this->expenseService->readExpense($expense_id);
        if (isset($_POST)) {
            $this->expenseService->updateExpense($expense, $expense);
        } else {
            echo $this->expenseTwig->render('expense/update.html.twig', ['expense' => $expense]);
        }
    }

    private function delete($expense_id) {
        $this->expenseService->deleteExpense($expense_id);
    }

}