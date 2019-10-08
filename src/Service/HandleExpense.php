<?php


namespace Homework3\Service;


use Homework3\Entity\CurrencyValue;
use Homework3\Entity\Expense;

class HandleExpense
{

    private $databaseConnection;

    public function __construct()
    {
        $pdo = new \PDO("mysql:dbname=mydb;host=localhost", "root", "" );
        $pdo->setAttribute( \PDO::ATTR_CASE, \PDO::CASE_NATURAL );
        $this->databaseConnection = $pdo;
    }

    public function addExpense(int $amount, string $currency, string $description)
    {
        try {
//            $this->databaseConnection->beginTransaction();

            $currencyValue = $this->readCurrencyValue($currency);
            $this->writeExpense($amount, $currencyValue, $description);

//            $this->databaseConnection->commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo 'error';
//            $this->databaseConnection->rollBack();
        }

        echo $this->databaseConnection->lastInsertId();
        print_r($this->databaseConnection->errorInfo());
    }

    public function updateExpense(Expense $expense)
    {

    }

    public function deleteExpense(Expense $expense)
    {

    }

    private function readCurrencyValue(string $currency)
    {
        $sql = "SELECT " . CurrencyValue::CURRENCY_VALUE_FIELD_VALUE_IN_HUF . " AS valueInHuf" .
            " FROM " . CurrencyValue::CURRENCY_VALUE_TABLE .
            " WHERE " . CurrencyValue::CURRENCY_VALUE_FIELD_CURRENCY . "=?" . " LIMIT 1";
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([$currency]);
        echo $message . " ";

        return $statement->fetchObject(CurrencyValue::class, [$currency]);
    }

    private function writeExpense(int $amount, CurrencyValue $currencyValue, string $description)
    {
        var_dump($currencyValue);
        $amountInHuF = $amount;
        $currency = 'HUF';
        if ($currencyValue->getCurrency() != 'HUF') {
            $amountInHuF = $amount * $currencyValue->getValueInHuf();
            $currency = $currencyValue->getCurrency();
        }

        $sql = "INSERT INTO " . Expense::EXPENSE_TABLE .
            " ( " . Expense::EXPENSE_FIELD_AMOUNT . ", " . Expense::EXPENSE_FIELD_AMOUNT_IN_HUF . ", " .
            Expense::EXPENSE_FIELD_CURRENCY . ", " . Expense::EXPENSE_FIELD_DESCRIPTION .
            " ) VALUES (?,?,?,?)";
        $statement = $this->databaseConnection->prepare($sql);
        $message = $statement->execute([$amount, $amountInHuF, $currency, $description]);
        echo $this->databaseConnection->lastInsertId();

        echo $message . "\n";
    }

}