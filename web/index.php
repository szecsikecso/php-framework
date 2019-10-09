<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\AutowirePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addCompilerPass(new AutowirePass());

$loader = new XmlFileLoader($containerBuilder,
    new FileLocator(dirname(__DIR__).DIRECTORY_SEPARATOR."config"));

$loader->load('services.xml');
$containerBuilder->compile();

/** @var \Homework3\Sample\SampleService $sampleService */
//$sampleService = $containerBuilder->get('sample_service');
//$sampleParam = $containerBuilder->getParameter('sample_param');
//echo $sampleService->sample(). "\n";
//echo $sampleParam;

$controller = new \Homework3\Controller\IndexController();
if (!empty($_SERVER['QUERY_STRING'])) {
    $controller->handle400();
}

if (isset($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];
    $path_split = explode('/', ltrim($path));

    foreach ($path_split as $key => $path_item) {
        if ($key == 1) {
            echo '<br><b>1</b><br>';
            if ($path_item == 'expense') {
                if (isset($_SESSION)) {
                    // User should be logged in to go on expense page(s)
                    $controller->handle401();
                } else {
                    $controller = new \Homework3\Controller\ExpenseController();
                    $controller->index();
                }
            } else {
                $controller->handle404();
            }
        } else if ($key == 2 && !isset($path_split[3]))  {
            echo '<br><b>2</b><br>';
            $controller->handleOperation($path_item);
        } else if ($key == 3) {
            echo '<br><b>3</b><br>';
            $controller->handleOperation($path_split[2], $path_split[3]);
        }
    }

} else {
    $path_split = '/';

    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        $controller->handle405();
    } else {
        $controller->index();
    }
}