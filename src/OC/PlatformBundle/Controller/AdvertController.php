<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class AdvertController extends Controller
{
    public function indexAction()
    {
        return new Response("Hello World jkjk!");
    }
  public function viewAction()
  {
	 $id=4848;
    return $this->render(
      'OCPlatformBundle:Advert:view.html.twig',
      array('id'  => $id)
    );
  }
}