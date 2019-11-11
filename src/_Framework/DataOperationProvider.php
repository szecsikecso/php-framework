<?php

namespace Homework3\_Framework;


interface DataOperationProvider
{

    /**
     * It should return an array of objects.
     * @return array
     */
    public function readAll(): array;

    /**
     * @param int $id
     * @return mixed
     */
    public function read(int $id);

    /**
     * @param FrameworkEntity $entity
     * @return mixed
     */
    public function write(FrameworkEntity $entity);

    /**
     * @param FrameworkEntity $entity
     * @return mixed
     */
    public function modify(FrameworkEntity $entity);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

}