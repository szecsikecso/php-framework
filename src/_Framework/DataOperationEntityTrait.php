<?php

namespace Homework3\_Framework;


trait DataOperationEntityTrait
{

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getConstants(): array {
        $objectClass = new \ReflectionClass(self::class);
        return $objectClass->getConstants();
    }

    /**
     * @return string
     */
    public function getTableName(): string {
        return self::getMachineName();
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getFields(): array {
        $fields = [];
        foreach ($this->getConstants() as $constantKey =>  $constantValue) {
            if (strpos($constantKey, '_FIELD') !== false) {
                $fields[$constantKey] = $constantValue;
            }
        }

        return $fields;
    }

    public function getAttributes(): array {
        $attributes = [];
        foreach ($this->getConstants() as $constantKey =>  $constantValue) {
            if (strpos($constantKey, '_FIELD') !== false) {
                $attributes[$constantValue] = self::camelize($constantValue);
            }
        }

        return $attributes;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getData(): array {
        $data = [];
        foreach ($this->getFields() as $fieldKey => $fieldValue) {
            $expectedAttributeName = self::camelize($fieldValue);
            $data[] = $this->$expectedAttributeName;
        }

        return $data;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getActualData(): array {
        $data = [];
        foreach ($this->getFields() as $fieldKey => $fieldValue) {
            $expectedAttributeName = self::camelize($fieldValue);
            $data[$fieldValue] = $this->$expectedAttributeName;
        }

        return $data;
    }

    private function camelize(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $string))));
    }

}