<?php


namespace Homework3\Entity;


use Homework3\_Framework\DataOperationEntityTrait;
use Homework3\_Framework\FrameworkEntity;

class Expense implements FrameworkEntity
{
    use DataOperationEntityTrait;

    public const MACHINE_NAME = 'expense';

    public const EXPENSE_FIELD_AMOUNT = 'amount';
    public const EXPENSE_FIELD_AMOUNT_IN_HUF = 'amount_in_huf';
    public const EXPENSE_FIELD_CURRENCY = 'currency';
    public const EXPENSE_FIELD_DESCRIPTION = 'description';

    public static $locatable = true;

    public static function isLocatable(): bool{
        return self::$locatable;
    }

    /**
     * @var int $id
     */
    private $id;

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

    public function __construct()
    {
    }

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public static function getMachineName(): string
    {
        return self::MACHINE_NAME;
    }

}