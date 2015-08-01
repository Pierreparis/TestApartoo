<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('oc_platform_homepage', new Route('/hello/{name}', array(
    '_controller' => 'OCPlatformBundle:Default:index',
)));
$collection->add('wah', new Route('/wa', array(
    '_controller' => 'OCPlatformBundle:Advert:view',
)));
$collection->add('hello_the_world', new Route('/hello-world', array(
    '_controller' => 'OCPlatformBundle:Advert:index',
)));
return $collection;
