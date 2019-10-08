<?php


namespace Homework3\Entity;


class Expense
{

    public const EXPENSE_TABLE = 'expense';

    public const EXPENSE_FIELD_AMOUNT = 'amount';
    public const EXPENSE_FIELD_AMOUNT_IN_HUF = 'amount_in_huf';
    public const EXPENSE_FIELD_CURRENCY = 'currency';
    public const EXPENSE_FIELD_DESCRIPTION = 'description';

    public function __construct()
    {
    }

    /** @var int $amount */
    private $amount;

    /** @var int $amountInHuf */
    private $amountInHuf;

    /**
     * HUF | EUR | GPB | CHF | USD
     *
     * @var string $currency
     */
    private $currency = 'HUF';

    /** @var string $description */
    private $description;

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmountInHuf(): int
    {
        return $this->amountInHuf;
    }

    /**
     * @param int $amountInHuf
     */
    public function setAmountInHuf(int $amountInHuf): void
    {
        $this->amountInHuf = $amountInHuf;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

}