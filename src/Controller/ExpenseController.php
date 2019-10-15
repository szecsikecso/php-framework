<?php


namespace Homework3\Controller;


use Homework3\_Framework\MySQLOperationProvider;
use Homework3\Entity\Expense;
use Homework3\Service\HandleExpense;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class ExpenseController
{

    private $expenseTwig;
    //private $expenseService;
    private $expenseProvider;

    public function __construct()
    {
        $this->expenseTwig = new Environment(new FilesystemLoader('../views'), ['debug' => true]);
        $this->expenseTwig->addExtension(new DebugExtension());

        //$this->expenseService = new HandleExpense();

        $expense = new Expense();
        $this->expenseProvider = new MySQLOperationProvider($expense, Expense::class);

        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function index()
    {
        //$expenses = $this->expenseService->readAllExpense();
        $expenses = $this->expenseProvider->readAll();

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

            $expense = new Expense();
            $expense->setAmount($amount);
            $expense->setAmountInHuf($amount);
            $expense->setCurrency($currency);
            $expense->setDescription($description);

            $this->expenseProvider->write($expense);
            $response['success'] = true;

            //$response = $this->expenseService->addExpense($amount, $currency, $description);
            echo $this->expenseTwig->render('expense/new.html.twig', ['response' => $response]);
        } else {
            echo $this->expenseTwig->render('expense/new.html.twig');
        }
    }

    private function read(int $id) {
        //$expense = $this->expenseService->readExpense($expense_id);
        $expense = $this->expenseProvider->read($id);

        echo $this->expenseTwig->render('expense/read.html.twig', ['expense' => $expense]);
    }

    private function update(int $id) {
        /** @var Expense $expense */
        $expense = $this->expenseProvider->read($id);
        //$expense = $this->expenseService->readExpense($expense_id);
        var_dump($expense);
        if (isset($_POST) && !empty($_POST)) {
            var_dump($_POST);
            $amount = strip_tags($_POST['amount']);
            $currency = strip_tags($_POST['currency']);
            $description = strip_tags($_POST['description']);

            $newExpense = clone($expense);
            $newExpense->setAmount($amount);
            $newExpense->setAmountInHuf($amount);
            $newExpense->setCurrency($currency);
            $newExpense->setDescription($description);

            $this->expenseProvider->modify($newExpense);
            $response['success'] = true;

            //$response = $this->expenseService->updateExpense($expense, $newExpense);
            echo $this->expenseTwig->render('expense/update.html.twig', ['response' => $response, 'expense' => $expense]);
        } else {
            echo $this->expenseTwig->render('expense/update.html.twig', ['expense' => $expense]);
        }
    }

    private function delete(int $id) {
        //$response = $this->expenseService->deleteExpense($expense_id);
        $response = ['success' => true];
        $this->expenseProvider->delete($id);

        $expenses = $this->expenseProvider->readAll();
        echo $this->expenseTwig->render('expense/index.html.twig', ['response' => $response, 'expenses' => $expenses]);
    }

}