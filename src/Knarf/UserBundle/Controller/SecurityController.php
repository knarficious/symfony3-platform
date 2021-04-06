<?php
// src/Knarf/UserBundle/Controller/SecurityController.php;

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecurityController extends Controller
{
  
  /**
   * @return void
   * @Route("/login", name="security_login_form")
   */  
  public function loginAction()
  {
    // Si le visiteur est déjà identifié, on le redirige vers sa page
    if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
      return $this->redirectToRoute('profile');
    }
    
    // Le service authentication_utils permet de récupérer le nom d'utilisateur
    // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
    // (mauvais mot de passe par exemple)
    $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('KnarfUserBundle:Security:login_.html.twig', array(
      'last_username' => $authenticationUtils->getLastUsername(),
      'error'         => $authenticationUtils->getLastAuthenticationError(),
    ));
  }
  
  /**
   * @Route("/login-modal", name="login_modal")
   * @return void
   */
  public function loginModalAction()
  {
    // Si le visiteur est déjà identifié, on le redirige vers sa page
    if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
      return $this->redirectToRoute('profile');
    }
    
    // Le service authentication_utils permet de récupérer le nom d'utilisateur
    // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
    // (mauvais mot de passe par exemple)
    $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('KnarfUserBundle:Security:login_modal.html.twig', array(
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
     *@Route("/member/{slug}", name="profile_view")
     */
    public function viewMemberAction($slug)
    {   
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfUserBundle:App_User');

        $user = $repository->findOneBy(array('slug' => $slug));
        
    return $this->render('KnarfUserBundle:Security:profiler.html.twig', array( 'user' => $user ) );
    }   
    
    
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfUserBundle:App_User');
        $listUsers = $repository->findBy(array(), array('lastTimeConnect' => 'DESC'));
        
        return $this->render('KnarfUserBundle:Security:menu.html.twig', array(
        'listUsers' => $listUsers
        ));        
        
    }
    

    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        
       
        $listUsers = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('KnarfUserBundle:App_User')
                ->findAll();        
        
        
        return $this->render('KnarfUserBundle:Security:members.html.twig', 
                array( 'listUsers' => $listUsers 
        ));

    }
    
    /**
     * @Route("/supprimer/{slug}", name="knarf_user_delete")
     * @param string $slug
     * @param Request $request
     * @throws NotFoundHttpException
     */
    public function deleteProfileAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();    
        $user = $em->getRepository('KnarfUserBundle:App_User')->findOneBy(array('slug' => $slug));
    
        if($this->getUser() === $user)
        {
            if(null === $user)
            {
                throw new NotFoundHttpException("L'utilisateur  ".$slug." n'existe pas!");
            }
    
            $form = $this->createFormBuilder()->getForm();
    
            if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {
                $em->remove($user);
                $em->flush();
                $this->get('security.token_storage')->setToken(null);
                $request->getSession()->invalidate();
                $this->addFlash('success', "Votre profil a été supprimé avec succès");

                return $this->redirect($this->generateUrl('knarf_platform_home'));    
            }

            return $this->render('KnarfUserBundle:Security:delete.html.twig', array(
            'user'    => $user,
            'form'      => $form->createView()
            ));
        }

        else
        {            
            return $this->redirectToRoute('profile');
        }   

    }
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getAccountDeletionHandler()
    {
        return $this->get('app.user_deletion.handler');
    }
    
}
