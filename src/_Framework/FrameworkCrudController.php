<?php

namespace Homework3\_Framework;


interface FrameworkCrudController extends FrameworkController
{

    public function newAction();

    public function readAction(int $id);

    public function updateAction(int $id);

    public function deleteAction(int $id);

}