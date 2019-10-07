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
$sampleService = $containerBuilder->get('sample_service');
$sampleParam = $containerBuilder->getParameter('sample_param');
echo $sampleService->sample(). "\n";
echo $sampleParam;
