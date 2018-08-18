<?php
// src/Knarf/UserBundle/Controller/SecurityController.php;

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
  
  /**
   * @return type
   * @Route("/login", name="security_login_form")
   */  
  public function loginAction()
  {
    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
    if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
      return $this->redirectToRoute('profile');
    }
    
    // Le service authentication_utils permet de récupérer le nom d'utilisateur
    // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
    // (mauvais mot de passe par exemple)
    $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('KnarfUserBundle:Security:login.html.twig', array(
      'last_username' => $authenticationUtils->getLastUsername(),
      'error'         => $authenticationUtils->getLastAuthenticationError(),
    ));
  }
    
    /**
     * This is the route the login form submits to.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the login automatically. See form_login in app/config/security.yml
     *
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {       
      throw new \Exception('This should never be reached!');
    }
    
    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()   
    {
      throw new \Exception('This should never be reached!');
    } 
  
    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');     

        $currentUser = $this->getUser();        

        
        return $this->render('KnarfUserBundle:Security:profil.html.twig', array( 'user' => $currentUser) );
    }
    
    /**
     *@Route("/member/{id}", name="profile_view")
     */
    public function viewMemberAction($id)
    {   
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfUserBundle:User');

        $user = $repository->find($id);
        
    return $this->render('KnarfUserBundle:Security:profiler.html.twig', array( 'user' => $user ) );
    }   
    
    
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfUserBundle:User');
        $listUsers = $repository->findBy(array(), array('lastTimeConnect' => 'DESC'));
        
        return $this->render('KnarfUserBundle:Security:menu.html.twig', array(
        'listUsers' => $listUsers
        ));        
        
    }
    
    /**
     * @Route("/admin")
     * @return type
     */
    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        
        $user = $this->getUser();
        $name = $user->getUsername();
        
        return $this->render('KnarfUserBundle:Security:admin.html.twig', array(
            'name' => $name
        ));

    }    
    
}
