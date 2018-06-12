<?php
// src/Knarf/UserBundle/Controller/SecurityController.php;

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knarf\UserBundle\Entity\User;
use Knarf\UserBundle\Form\UserType;
use Knarf\UserBundle\Form\UserEditType;
use Knarf\UserBundle\Geoloc\AdresseIp;

class SecurityController extends Controller
{
  /**
   * @return type
   * @Route("/login", name="login")
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
  
    public function loginCheckAction()
    {       
       
    }
    
    public function logoutAction()    {
          
        
    }
    
  
  public function registerAction (Request $request)
  {
    if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
      return $this->redirectToRoute('profile');
    }  
      
      $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $adresseIp = new AdresseIp();
            $adresseIp->get_ip_address();
            $user->setAdresseIp($adresseIp);
            $user->setRoles(array('ROLE_USER'));
           
           // $role = $user->getRoles();
           // $user->setRoles(array('roles' => $role));

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'Votre compte est enregistré. Vous pouvez maintenant vous connecter');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'KnarfUserBundle:Security:register.html.twig',
            array('form' => $form->createView())
        );
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
        $user = new User();
        $form = $this->createForm(UserEditType::class, $currentUser);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $currentUser;
            $user->setUpdatedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();    
            

            $request->getSession()->getFlashBag()->add('notice', 'Modifications effectuées avec succès');
            return $this->redirectToRoute('profile');
        
        }
        
        return $this->render('KnarfUserBundle:Security:edit_profil.html.twig', array('form' => $form->createView(), 'user' => $currentUser));
    }
    
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KnarfUserBundle:User');
        $listUsers = $repository->findAll();
        
        return $this->render('KnarfUserBundle:Security:menu.html.twig', array(
        'listUsers' => $listUsers
        ));        
        
    }
}
