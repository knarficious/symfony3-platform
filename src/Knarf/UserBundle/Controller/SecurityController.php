<?php
// src/Knarf/UserBundle/Controller/SecurityController.php;

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knarf\UserBundle\Entity\User;
use Knarf\UserBundle\Form\UserType;
use Knarf\UserBundle\Form\UserEditType;
use Knarf\UserBundle\Geoloc\AdresseIp;

class SecurityController extends Controller
{
  public function loginAction(Request $request)
  {
    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
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
  
    public function loginCheckAction(Request $request)
    {
        return $this->redirectToRoute('profile');
    }
    
  
  public function registerAction (Request $request)
  {
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
           
           // $role = $user->getRoles();
           // $user->setRoles(array('roles' => $role));

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Votre compte est enregistré. Vous pouvez maintenant vous connecter');

            return $this->redirectToRoute('knarf_platform_home');
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
}
