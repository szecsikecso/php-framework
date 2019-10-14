<?php


namespace Homework3\Service;


use Homework3\_Framework\MySQLOperationProvider;
use Homework3\Entity\CurrencyValue;
use Homework3\Entity\Expense;

class HandleExpense
{

    /**
     * @var \PDO $databaseConnection
     */
    private $databaseConnection;

    private $expenseProvider;

    public function __construct()
    {
        $expense = new Expense();
        $this->expenseProvider = new MySQLOperationProvider($expense, Expense::class);

        $pdo = new \PDO("mysql:dbname=mydb;host=localhost", "root", "");
        $pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
        $this->databaseConnection = $pdo;
    }

    /**
     * @param int $id
     * @return Expense
     */
    public function readExpense(int $id): Expense
    {
        $sql = "SELECT id, " . Expense::EXPENSE_FIELD_AMOUNT . ", " .
            Expense::EXPENSE_FIELD_AMOUNT_IN_HUF . " AS amountInHuf, " .
            Expense::EXPENSE_FIELD_CURRENCY . ", " . Expense::EXPENSE_FIELD_DESCRIPTION .
            " FROM " . Expense::EXPENSE_TABLE .
            " WHERE " . 'id' . "=?" . " LIMIT 1";
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([$id]);
        echo $message . " ";

        return $statement->fetchObject(Expense::class);
    }

    /**
     * @return array
     */
    public function readAllExpense(): array
    {
        $sql = "SELECT id, " . Expense::EXPENSE_FIELD_AMOUNT . ", " .
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

    /**
     * @param int $amount
     * @param string $currency
     * @param string $description
     * @return array
     */
    public function addExpense(int $amount, string $currency, string $description): array
    {
        $response = $this->validateExpense($amount, $currency, $description);

        if (empty($response)) {
            try {
                $this->databaseConnection->beginTransaction();

                $currencyValue = $this->readCurrencyValue($currency);

                //$this->writeExpense($amount, $currencyValue, $description);

                $amountInHuF = $amount;
                $currency = 'HUF';
                if ($currencyValue->getCurrency() != 'HUF') {
                    $amountInHuF = $amount * $currencyValue->getValueInHuf();
                    $currency = $currencyValue->getCurrency();
                }

                $expense = new Expense();
                $expense->setAmount($amount);
                $expense->setAmountInHuf($amountInHuF);
                $expense->setCurrency($currency);
                $expense->setDescription($description);

                $this->expenseProvider->write($expense);

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

    /**
     * @param Expense $oldExpense
     * @param Expense $newExpense
     * @return array
     */
    public function updateExpense(Expense $oldExpense, Expense $newExpense): array
    {
        $response = $this->validateExpense(
            $newExpense->getAmount(),
            $newExpense->getCurrency(),
            $newExpense->getDescription()
        );

        if (!$response) {
            try {
                $this->databaseConnection->beginTransaction();

                if ($oldExpense->getAmount() != $newExpense->getAmount()) {
                    $hufValue = $oldExpense->getAmountInHuf() / $oldExpense->getAmount();

                    $newExpense->setAmountInHuf($newExpense->getAmount() * $hufValue);
                }

                var_dump($oldExpense);
                var_dump($newExpense);

                $this->modifyExpense($newExpense);

                $response['success'] = true;

                $this->databaseConnection->commit();
            } catch (\Exception $e) {
                echo $e->getMessage();

                $this->databaseConnection->rollBack();
            }
        }

        return $response;
    }

    /**
     * @param int $expense_id
     * @return array
     */
    public function deleteExpense(int $expense_id): array
    {
        try {
            $this->databaseConnection->beginTransaction();

            $statement = $this->databaseConnection->prepare(
                "DELETE FROM " . Expense::EXPENSE_TABLE . " WHERE id=?");
            $statement->execute([$expense_id]);

            var_dump($statement->errorInfo());

            $this->databaseConnection->commit();
        } catch (\Exception $e) {
            echo $e->getMessage();

            $this->databaseConnection->rollBack();
        }

        return ['success' => true];
    }

    /**
     * @param int $amount
     * @param string $currency
     * @param string $description
     * @return array
     */
    private function validateExpense(int $amount, string $currency, string $description): array
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

    /**
     * @param string $currency
     * @return CurrencyValue
     */
    private function readCurrencyValue(string $currency): CurrencyValue
    {
        $sql = "SELECT " . CurrencyValue::CURRENCY_VALUE_FIELD_VALUE_IN_HUF . " AS valueInHuf" .
            " FROM " . CurrencyValue::CURRENCY_VALUE_TABLE .
            " WHERE " . CurrencyValue::CURRENCY_VALUE_FIELD_CURRENCY . "=?" . " LIMIT 1";
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([$currency]);
        echo $message . " ";

        return $statement->fetchObject(CurrencyValue::class, [$currency]);
    }

    /**
     * @param int $amount
     * @param CurrencyValue $currencyValue
     * @param string $description
     */
    private function writeExpense(int $amount, CurrencyValue $currencyValue, string $description): void
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

    /**
     * @param Expense $expense
     */
    private function modifyExpense(Expense $expense): void
    {
        $sql = "UPDATE " . Expense::EXPENSE_TABLE . " SET " .
            Expense::EXPENSE_FIELD_AMOUNT . "=?, " .
            Expense::EXPENSE_FIELD_AMOUNT_IN_HUF . "=?, " .
            Expense::EXPENSE_FIELD_CURRENCY . "=?, " .
            Expense::EXPENSE_FIELD_DESCRIPTION . "=? " .
            "WHERE id=?";
        $statement = $this->databaseConnection->prepare($sql);
        $message = $statement->execute([$expense->getAmount(), $expense->getAmountInHuf(),
            $expense->getCurrency(), $expense->getDescription(), (int)$expense->getId()]);

        var_dump($statement->errorInfo());

        echo $message . "\n";
    }
}