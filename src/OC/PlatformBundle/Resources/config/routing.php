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

$collection->add('carnet', new Route('/inscr', array(
    '_controller' => 'OCPlatformBundle:Carnet:index',
)));
$collection->add('inscr', new Route('/trait', array(
    '_controller' => 'OCPlatformBundle:Carnet:inscr',
)));
$collection->add('home', new Route('/home', array(
    '_controller' => 'OCPlatformBundle:Carnet:home',
)));
$collection->add('co', new Route('/login', array(
    '_controller' => 'OCPlatformBundle:Carnet:login',
)));
$collection->add('cogrant', new Route('/grant', array(
    '_controller' => 'OCPlatformBundle:Carnet:grant',
)));
$collection->add('mod', new Route('/mod', array(
    '_controller' => 'OCPlatformBundle:Carnet:mod',
)));
$collection->add('hmod', new Route('/hmod', array(
    '_controller' => 'OCPlatformBundle:Carnet:handlemod',
)));
$collection->add('logout', new Route('/logout', array(
    '_controller' => 'OCPlatformBundle:Carnet:logout',
)));

$collection->add('hello_the_world', new Route('/hello-world', array(
    '_controller' => 'OCPlatformBundle:Advert:index',
)));
return $collection;
