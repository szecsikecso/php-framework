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

    public function readExpense(int $id) {
        $sql = "SELECT " . Expense::EXPENSE_FIELD_AMOUNT . ", " .
            Expense::EXPENSE_FIELD_AMOUNT_IN_HUF . " AS amountInHuf, " .
            Expense::EXPENSE_FIELD_CURRENCY . ", " . Expense::EXPENSE_FIELD_DESCRIPTION .
            " FROM " . Expense::EXPENSE_TABLE .
            " WHERE " . 'id' . "=?" . " LIMIT 1";
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([$id]);
        echo $message . " ";

        return $statement->fetchObject(Expense::class, [$id]);
    }

    public function readAllExpense() {
        $sql = "SELECT " . Expense::EXPENSE_FIELD_AMOUNT . ", " .
            Expense::EXPENSE_FIELD_AMOUNT_IN_HUF . " AS amountInHuf, " .
            Expense::EXPENSE_FIELD_CURRENCY . ", " . Expense::EXPENSE_FIELD_DESCRIPTION .
            " FROM " . Expense::EXPENSE_TABLE;
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([]);
        var_dump($statement->errorInfo());
        echo $message . " ";

        //return $statement->fetchObject(Expense::class, []);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Expense::class);
    }

    public function addExpense(int $amount, string $currency, string $description)
    {
        $response = $this->validateExpense($amount, $currency, $description);

        if (empty($response)) {
            try {
                $this->databaseConnection->beginTransaction();

                $currencyValue = $this->readCurrencyValue($currency);
                $this->writeExpense($amount, $currencyValue, $description);

                $response['success'] = true;

                $this->databaseConnection->commit();
            } catch (\Exception $e) {
                echo $e->getMessage();

                $this->databaseConnection->rollBack();
            }
        }

        echo 'Last ID: ' . $this->databaseConnection->lastInsertId();

        return $response;
    }

    public function updateExpense(Expense $oldExpense, Expense $newExpense)
    {
        try {
            $this->databaseConnection->beginTransaction();

            if ($oldExpense->getAmount() != $newExpense->getAmount()) {
                $hufValue = $oldExpense->getAmountInHuf() / $oldExpense->getAmount();

                $newExpense->setAmountInHuf($newExpense->getAmount() * $hufValue);
            }

            $this->modifyExpense($newExpense);

            $this->databaseConnection->commit();
        } catch (\Exception $e) {
            echo $e->getMessage();

            $this->databaseConnection->rollBack();
        }
    }

    public function deleteExpense(int $expense_id)
    {
        try {
            $this->databaseConnection->beginTransaction();

            $statement = $this->databaseConnection->prepare(
                "DELETE FROM " . Expense::EXPENSE_TABLE . "WHERE id=?");
            $statement->execute([$expense_id]);

            $this->databaseConnection->commit();
        } catch (\Exception $e) {
            echo $e->getMessage();

            $this->databaseConnection->rollBack();
        }
    }

    private function validateExpense(int $amount, string $currency, string $description)
    {
        $errors = [];
        if (!is_numeric($amount)) {
            $errors['amount'] = 'Not valid number!';
        }
        if (strlen($currency) != 3) {
            $errors['currency'] = 'Not valid currency code!';
        }

        if (strlen($description) == 0) {
            $errors['description'] = 'Empty description!';
        } else if (strlen($description) > 250) {
            $errors['description'] = 'Too long description!';
        }

        return $errors;
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

    private function modifyExpense(Expense $expense)
    {
        $sql = "UPDATE" . Expense::EXPENSE_TABLE . " SET " .
    Expense::EXPENSE_FIELD_AMOUNT . "=?, " .
    Expense::EXPENSE_FIELD_AMOUNT_IN_HUF . "=?, " .
    Expense::EXPENSE_FIELD_CURRENCY . "=?, " .
    Expense::EXPENSE_FIELD_DESCRIPTION . "=?, " .
    "WHERE id=?";
        $statement = $this->databaseConnection->prepare($sql);
        $message = $statement->execute([$expense->getAmount(), $expense->getAmountInHuf(),
            $expense->getCurrency(), $expense->getDescription(), $expense->getId()]);
    }

}