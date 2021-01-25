<?php

namespace Knarf\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class LoginController extends Controller
{
    
    public function loginAction(Request $request)
    {
//        $session = $request->getSession();
//
//        // get the login error if there is one
//        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
//            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
//        } else {
//            $error = $session->get(Security::AUTHENTICATION_ERROR);
//            $session->remove(Security::AUTHENTICATION_ERROR);
//        }
//
//        // Add the following lines
//        if ($session->has('_security.target_path')) {
//            if (false !== strpos($session->get('_security.target_path'), $this->generateUrl('fos_oauth_server_authorize'))) {
//                $session->set('_fos_oauth_server.ensure_logout', true);
//            }
//        }
        
        return $this->render('KnarfApiBundle:Login:login.html.twig', [
            'last_username' => $session->get(Security::LAST_USERNAME),
            'error'         => $error]
            );
    }
    
    /**
     * @Route("/oauth/v2/auth_login_check")
     * @param Request $request
     */
    public function loginCheckAction(Request $request)
    {

    }

}
