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
    public function loginAction(Request $request)
    {
		$session = $request->getSession();
		if(null!==$session->get('userinfos')){
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/home');
		}
		else{
			return $this->render(
			  'OCPlatformBundle:Carnet:login.html.twig',
			  array()
			);
		}
    }
    public function addamiAction(Request $request)
    {
		$session = $request->getSession();
		if(null!==$session->get('userinfos')){
			$login = $session->get('userinfos')['login'];
			$pw = $session->get('userinfos')['mp'];
			$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
			$reponse = $bdd->query("SELECT amis FROM user WHERE login='$login' AND mp ='$pw'");
			$r=$reponse->fetch()[0];
			$upd = $r.",".$_GET['ami'];
			$no =true;
			foreach(explode(",",$r) as $e){
				if($e ==$_GET['ami']){$no =false;}
			}
			if($no!=false){
			$bdd->exec("UPDATE user SET amis='$upd'  WHERE mp='$pw'AND login='$login'");
			}
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/carnet?'.$no);
		}
		else{
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
		}
    }
    public function logoutAction(Request $request)
    {
		$session = $request->getSession();
		$session->remove('userinfos');
		$session->invalidate();
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
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
	if(null!==$session->get('userinfos')){
		return $this->render(
		  'OCPlatformBundle:Carnet:home.html.twig',
		  array('data'=>json_encode($session->get('userinfos')))
		);
	}
	else{
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
	}

  }
  public function carnetAction(Request $request)
  {
	$session = $request->getSession();
	$login = $session->get('userinfos')['login'];
	$pw = $session->get('userinfos')['mp'];
	if(null!==$session->get('userinfos')){
		$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
		$reponse = $bdd->query("SELECT amis FROM user WHERE login='$login' AND mp ='$pw'");
		$donnees = $reponse->fetch();
		$data = array('listsimple'=>explode(",",$donnees[0]),'listall'=>array());
		foreach(explode(",",$donnees[0])as $ami){
			$amir = $bdd->query("SELECT * FROM user WHERE login='$ami'");
			$amid = json_encode($amir->fetch());
			$data['listall'] = array_merge($data['listall'],array_fill_keys ( array($ami) , $amid ));			
		}
		return $this->render(
		  'OCPlatformBundle:Carnet:carnet.html.twig',
		  array('data'=>$data)
		);
	}
	else{
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
	}

  }
  public function handlemodAction(Request $request)
  {
	$session = $request->getSession();
	$login = $session->get('userinfos')['login'];
	$pw = $session->get('userinfos')['mp'];
	$email = $_GET['mail'];
	$adr = $_GET['adr'];
	$tel = $_GET['tel'];
	$site = $_GET['site'];
	$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
	$bdd->exec("UPDATE user SET email = '$email' , adr = '$adr' , tel = '$tel',site ='$site'  WHERE mp='$pw'AND login='$login'");
	$reponse = $bdd->query("SELECT * FROM user WHERE login='$login' AND mp ='$pw'");
	$donnees = $reponse->fetch();
	if($donnees==false){
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
	}
	else{
		$session->set('userinfos',$donnees);	
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/home');
	}
  }
  public function modAction(Request $request)
  {
	$session = $request->getSession();
	$login = $session->get('userinfos')['login'];
	$pw = $session->get('userinfos')['mp'];		
	$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');	
	$reponse = $bdd->query("SELECT * FROM user WHERE login='$login' AND mp ='$pw'");
	$donnees = $reponse->fetch();
	if($donnees==false){
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
	}
	else{	
		return $this->render(
		  'OCPlatformBundle:Carnet:mod.html.twig',
		  array('data'=>$session->get('userinfos'))
		);
	}
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