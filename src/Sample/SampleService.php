<?php

namespace Homework3\Sample;

/**
 * Class SampleService
 *
 * @package Homework3\Sample
 */
class SampleService
{

    /**
     * @var \Homework3\Sample\RandomInterface
     */
    private $random;

    public function __construct(RandomInterface $random)
    {
        $this->random = $random;
    }

    /**
     * @return string
     */
    public function sample(): string
    {
        return 'sample_service'. $this->random->random();
    }
}
