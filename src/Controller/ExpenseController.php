<?php


namespace Homework3\Controller;

use Homework3\_Framework\FrameworkCrudController;
use Homework3\_Framework\FrameworkControllerTrait;
use Homework3\Entity\Expense;

class ExpenseController implements FrameworkCrudController
{
    use FrameworkControllerTrait;

    /**
     * @var bool $reachableAsAnonymous
     */
    public static $reachableAsAnonymous = false;

    /**
     * ExpenseController constructor.
     *
     * #########################################################################
     * initControllerForClass method should by called by Entity:class parameter!
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->initControllerForClass(Expense::class);
    }

    private function save() {
        $amount = strip_tags($_POST['amount']);
        $currency = strip_tags($_POST['currency']);
        $description = strip_tags($_POST['description']);

        $expense = new Expense();
        $expense->setAmount($amount);
        $expense->setAmountInHuf($amount);
        $expense->setCurrency($currency);
        $expense->setDescription($description);

        $this->dataProvider->write($expense);
        // echo $this->twig->render('expense/new.html.twig', ['response' => $response]);
    }

    private function modify(int $id) {
        /** @var Expense $expense */
        $expense = $this->dataProvider->read($id);

        $amount = strip_tags($_POST['amount']);
        $currency = strip_tags($_POST['currency']);
        $description = strip_tags($_POST['description']);

        $newExpense = clone($expense);
        $newExpense->setAmount($amount);
        $newExpense->setAmountInHuf($amount);
        $newExpense->setCurrency($currency);
        $newExpense->setDescription($description);

        $this->dataProvider->modify($newExpense);
        // echo $this->twig->render('expense/update.html.twig', ['response' => $response, 'expense' => $expense]);
    }
}