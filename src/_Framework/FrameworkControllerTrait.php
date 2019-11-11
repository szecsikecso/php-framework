<?php

namespace Homework3\_Framework;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

trait FrameworkControllerTrait
{

    /**
     * @return bool
     */
    public static function isReachableAsAnonymous(): bool{
        return self::$reachableAsAnonymous;
    }

    /**
     * @var Environment $twig
     */
    private $twig;

    /**
     * @var ViewHandler $view
     */
    private $view;

    /**
     * @var DataOperationProvider $dataProvider
     */
    private $dataProvider;

    /**
     * @var string $machineName
     */
    private $machineName;

    /**
     * @param string $className
     * @throws \ReflectionException
     */
    private function initControllerForClass(string $className)
    {
        $this->twig = new Environment(new FilesystemLoader('../views'), ['debug' => true]);
        $this->twig->addExtension(new DebugExtension());

        $object = new $className();
        $this->machineName = $object::getMachineName();
        $this->dataProvider = new MySQLOperationProvider($object, $className);

        $reflection = new \ReflectionClass(__CLASS__);
        $this->view = new ViewHandler(
            $this->machineName,
            $reflection->implementsInterface(FrameworkCrudController::class)
        );

        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * @param array $response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(array $response = [])
    {
        $expenses = $this->dataProvider->readAll();
        $this->generateView('index', ['response' => $response, 'entities' => $expenses]);
    }

    /**
     * @param int $id
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readAction(int $id)
    {
        $entity = $this->dataProvider->read($id);
        $this->generateView('read', ['entity' => $entity]);
    }

    /**
     * @param int $id
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function newAction(int $id = 0)
    {
        if (!isset($_POST) || empty($_POST)) {
            $this->generateView('new');
        } else {
            $this->save();

            $response['success'] = true;
            $response['message'] = 'The ' . $this->machineName . ' with id: ' . $id . ' successfully updated.';

            $this->indexAction($response);
        }
    }

    /**
     * @param int $id
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateAction(int $id) {
        if (!isset($_POST) || empty($_POST)) {
            $entity = $this->dataProvider->read($id);
            $this->generateView('update', ['entity' => $entity]);

        } else {
            $this->modify($id);

            $response['success'] = true;
            $response['message'] = 'New ' . $this->machineName . 'successfully created.';

            $this->indexAction($response);
        }
    }

    /**
     * @param int $id
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteAction(int $id) {
        $this->dataProvider->delete($id);
        $response['success'] = true;
        $response['message'] = 'The ' . $this->machineName . ' with id: ' . $id . ' successfully deleted.';

        $this->indexAction($response);
    }

    /**
     * @param string $action
     * @param array $attributes
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function generateView(string $action, array $attributes = []): void {
        $templateName = $this->view->handleCustomView($action);
        echo $this->twig->render($templateName, $attributes);
    }

}