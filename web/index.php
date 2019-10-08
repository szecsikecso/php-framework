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

if (isset($_SERVER['PATH_INFO'])) {
    $path= $_SERVER['PATH_INFO'];
//    print_r($_SERVER);
    echo $path . "<br>";
    $path_split = explode('/', ltrim($path));
    print_r($path_split);
    echo "<br>";

    $controller = new \Homework3\Controller\IndexController();

    echo $path_split[count($path_split)-1];

//    if (strpos($_SERVER['QUERY_STRING'], '%%') ||  strpos($_SERVER['QUERY_STRING'], '}')) {
//    }

    foreach ($path_split as $key => $path_item) {
        if ($key == 1) {
            echo '<br><b>1</b><br>';
            if ($path_item == 'expense') {
                $controller = new \Homework3\Controller\ExpenseController();
            } else {
                $controller->handle404();
            }
        } else if ($key == 2) {
            echo '<br><b>2</b><br>';
            $controller->handleOperation($path_item);
        }
    }

} else {
    $path_split = '/';
}