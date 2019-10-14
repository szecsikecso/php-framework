<?php

namespace Homework3\_Framework;


interface DataOperationProvider
{

    public function read(int $id);

    public function readAll();

    public function write(FrameworkEntity $entity);

    public function modify(FrameworkEntity $entity);

    public function delete(int $id);

}