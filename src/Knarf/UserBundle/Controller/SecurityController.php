<?php
// src/Knarf/UserBundle/Controller/SecurityController.php;

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knarf\UserBundle\Entity\User;
use Knarf\UserBundle\Form\UserType;
use Knarf\UserBundle\Form\Type\User\EditEmailType;
use Knarf\UserBundle\Geoloc\AdresseIp;
use Knarf\UserBundle\Util\TokenGeneratorInterface;

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
  

    public function profileAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');     

        $currentUser = $this->getUser();        

        
        return $this->render('KnarfUserBundle:Security:profil.html.twig', array( 'user' => $currentUser) );
    }
    
    public function viewMemberAction($id)
    {   
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('KnarfUserBundle:User');

        $user = $repository->find($id);
        
    return $this->render('KnarfUserBundle:Security:profiler.html.twig', array( 'user' => $user ) );
    }
    
    public function editProfileAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');     

        $currentUser = $this->getUser();        
        if ($currentUser instanceof User)
        {
             $form = $this->createForm(EditEmailType::class, $currentUser);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $currentUser->setUpdatedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentUser);
            $entityManager->flush();    
            

            $request->getSession()->getFlashBag()->add('notice', 'Modifications effectuées avec succès');
            return $this->redirectToRoute('profile');
        
        }
        }
       
        
        return $this->render('KnarfUserBundle:Security:edit_profile.html.twig', array('form' => $form->createView(), 'user' => $currentUser));
    }
    
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfUserBundle:User');
        $listUsers = $repository->findAll();
        
        return $this->render('KnarfUserBundle:Security:menu.html.twig', array(
        'listUsers' => $listUsers
        ));        
        
    }
    
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
