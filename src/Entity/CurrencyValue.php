<?php


namespace Homework3\Entity;


class CurrencyValue
{

    public const CURRENCY_VALUE_TABLE = 'currency_value';

    public const CURRENCY_VALUE_FIELD_CURRENCY = 'currency';
    public const CURRENCY_VALUE_FIELD_VALUE_IN_HUF = 'value_in_huf';

    private $currency = '';

    private $valueInHuf = 0;

    public function __construct($currency = '', $valueInHuf = 0)
    {
        if ($currency != '') {
            $this->currency = $currency;
        }
        if ($valueInHuf != '') {
            $this->valueInHuf = $valueInHuf;
        }
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getValueInHuf()
    {
        return $this->valueInHuf;
    }

    /**
     * @param mixed $valueInHuf
     */
    public function setValueInHuf($valueInHuf): void
    {
        $this->valueInHuf = $valueInHuf;
    }



}