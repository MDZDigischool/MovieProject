<?php

namespace MDZ\DigischoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MDZ\DigischoolBundle\Entity\User;
use Symfony\Component\{
	HttpFoundation\Response,
	HttpFoundation\Request,
	HttpFoundation\RedirectResponse,
	HttpFoundation\JsonResponse
};

class UserController extends Controller
{
	/**
	* Function to register user in database
	*
	* @param object $request
	*/
	final public function registerUserAction(Request $request){
		if($request->isMethod('POST')){
			$user = new User();
			$user->setPseudo($request->request->get('pseudo'));
			$user->setEmail($request->request->get('email'));
			$user->setBirthday($request->request->get('birthday'));

			$em = $this->getDoctrine()->getManager();
			
			$em->persist($user);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Enregistrement effectué avec succès, vous allez prochainement recevoir un email à l\'adresse....');
			return new JsonResponse(['user'=>$user]);
		}
		//Case fail redirect somewhere..
	}
	
	
}
