<?php

namespace Homework3\_Framework;


interface FrameworkEntity
{

    public static function getMachineName(): string;

    public static function isLocatable(): bool;

    public function getConstants(): array;

    public function getTableName(): string;

    public function getFields(): array;

    public function getAttributes(): array;

    public function getData(): array;

    public function getActualData(): array;

}
