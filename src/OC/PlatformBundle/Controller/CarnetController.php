<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;
use \PDO;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class CarnetController extends Controller
{ 
    public function indexAction()
    {
		return $this->render(
		  'OCPlatformBundle:Carnet:view.html.twig',
		  array()
		);
    }
    public function loginAction()
    {
		return $this->render(
		  'OCPlatformBundle:Carnet:login.html.twig',
		  array()
		);
    }	
    public function grantAction(Request $request)
    {
		$login = $_GET['id'];
		$pw = $_GET['pw'];		
		$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');	
		$reponse = $bdd->query("SELECT * FROM user WHERE login='$login' AND mp ='$pw'");
		$donnees = $reponse->fetch();
		if($donnees==false){
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
		}
		else{
			$session = $request->getSession();
			$session->set('userinfos',$donnees);	
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/home');
		}
		//return new Response(json_encode($donnees));
    }
  public function homeAction(Request $request)
  {
	$session = $request->getSession();
    return new Response("home yo ".json_encode($session->get('userinfos')));
  }
	public function inscrAction(Request $request)
	{
		print_r($_GET);
		$login = $_GET['id'];
		$pw = $_GET['pw'];
		$email = $_GET['mail'];
		$adr = $_GET['adr'];
		$tel = $_GET['tel'];
		$site = $_GET['site'];
		try
		{
		$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');			
		$bdd->exec("INSERT INTO user VALUES('$login','$pw','$email','$adr','$tel','$site','')");
		$session = $request->getSession();
	    $session->set('userinfos',$_GET );	
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}	
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/home');
	}
}