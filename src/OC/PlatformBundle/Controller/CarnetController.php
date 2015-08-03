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
			$ga =htmlspecialchars ($_GET['ami']);
			$rep1 = $bdd->query("SELECT * FROM user WHERE login='$ga'");
			$r1 = $rep1->fetch();
			$upd = $r.",".htmlspecialchars ($_GET['ami']);
			$no =true;
			foreach(explode(",",$r) as $e){
				if($e ==htmlspecialchars ($_GET['ami'])){$no =false;}
			}
			if($no!=false&&$r1!=false){
			$bdd->exec("UPDATE user SET amis='$upd'  WHERE mp='$pw'AND login='$login'");
			}
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/carnet?'.$no);
		}
		else{
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
		}
    }
    public function delamiAction(Request $request)
    {
		$session = $request->getSession();
		if(null!==$session->get('userinfos')){
			$login = $session->get('userinfos')['login'];
			$pw = $session->get('userinfos')['mp'];
			$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
			$reponse = $bdd->query("SELECT amis FROM user WHERE login='$login' AND mp ='$pw'");
			$r=$reponse->fetch()[0];
			$ga =htmlspecialchars ($_GET['ami']);	
			$tab = explode(",",$r);
			array_splice ( $tab,array_search ( $ga, $tab ),1);
			$amix='';
			foreach($tab as $t){
				$amix = $t.",".$amix;
			}
			$ok=$bdd->exec("UPDATE user SET amis='$amix'  WHERE mp='$pw'AND login='$login'");
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/carnet?'.$ok);
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
		$login = htmlspecialchars ($_GET['id']);
		$pw = htmlspecialchars ($_GET['pw']);		
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
		  array('data'=>$session->get('userinfos'))
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
			$amid = $amir->fetch();
			if($amid){$data['listall'] = array_merge($data['listall'],array_fill_keys ( array($ami) , $amid ));}			
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
	$email = htmlspecialchars ($_GET['mail']);
	$adr = htmlspecialchars ($_GET['adr']);
	$tel = htmlspecialchars ($_GET['tel']);
	$site = htmlspecialchars ($_GET['site']);
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
		$login = htmlspecialchars ($_GET['id']);
		$pw = htmlspecialchars ($_GET['pw']);
		$email = htmlspecialchars ($_GET['mail']);
		$adr = htmlspecialchars ($_GET['adr']);
		$tel = htmlspecialchars ($_GET['tel']);
		$site = htmlspecialchars ($_GET['site']);
		try
		{
		$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');			
		$i = $bdd->exec("INSERT INTO user VALUES('$login','$pw','$email','$adr','$tel','$site','')");
		$session = $request->getSession();
	    $session->set('userinfos', array_merge($_GET,array('login'=>$login,'email'=>$email,'amis'=>'')) );	
		}
		catch (Exception $e)
		{
				return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
		}
		if($i){
		return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/home?'.$i);
		}
		else{
			$session->remove('userinfos');
			$session->invalidate();
			return new RedirectResponse('http://localhost/Symfony/web/app_dev.php/login');
		}
	}
}