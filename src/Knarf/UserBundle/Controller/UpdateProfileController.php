<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Knarf\UserBundle\Form\Type\User\EditEmailType;
use Knarf\UserBundle\Form\Type\AvatarType;
use Knarf\UserBundle\Form\Type\EditAvatarType;
use Knarf\UserBundle\Entity\User\Profile;
use Knarf\UserBundle\Entity\Avatar;

/**
 * Description of UpdateProfileController
 *
 * @author franck
 * @Route("/profile")
 */
class UpdateProfileController extends Controller 
{
   /**
     * @param Request $request
     * @Route("/modifier-email", name="change_email")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editEmailAction(Request $request)
    {
        
        $profile = new Profile($this->getUser());
        $form = $this->createForm(EditEmailType::class, $profile);
        
        if($this->getEmailProfileFormHandler()->handle($form, $request))
        {
            $this->addFlash('success', 'Votre profil a bien été mis à jour.');
            return $this->redirect($this->generateUrl('profile'));
        }
        
        return $this->render('KnarfUserBundle:Security:edit_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @param Request $request
     * @Route("/ajout-avatar", name="add_avatar")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAvatarAction(Request $request)
    {   
        $user = $this->getUser();
        
        if($user->getAvatar() !== null){
            
            $avatar = $user->getAvatar();            
        }
        
        else {
            
            $avatar = new Avatar($user); 
        }
        
        $em = $this->getDoctrine()->getManager();             

        $form = $this->createForm(AvatarType::class, $avatar);
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {            
            $em->persist($avatar);
            $user->setAvatar($avatar);
            $em->flush();
            
            $this->addFlash('success', 'Votre avatar a bien été mis à jour.');
            return $this->redirect($this->generateUrl('profile'));
        }
        
        return $this->render('KnarfUserBundle:Security:add_avatar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/modifier-avatar", name="edit_avatar")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAvatarAction(Request $request)
    {
        $user = $this->getUser();
        
        if($user->getAvatar() !== null){
            
            $avatar = $user->getAvatar();            
        }
        
        $em = $this->getDoctrine()->getManager();             

        $form = $this->createForm(EditAvatarType::class, $avatar);
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {            
            $avatar->setDate(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $user->setAvatar($avatar);
            $em->flush();
            
            $this->addFlash('success', 'Votre avatar a bien été mis à jour.');
            return $this->redirect($this->generateUrl('profile'));
        }
        
        return $this->render('KnarfUserBundle:Security:edit_avatar.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
    
    /**
     * @param Request $request
     * @Route("/delete-avatar", name="delete_avatar")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAvatarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();
        
        $avatar = $user->getAvatar();
        
        $form = $this->createFormBuilder()->getForm();
        
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {
                $em->remove($avatar);
                $user->setAvatar(null);
                $em->flush();
        
                $this->addFlash('success', "L'avatar a été supprimé avec succès");
        
            return $this->redirect($this->generateUrl('profile'));    
            }

            return $this->render('KnarfUserBundle:Security:delete_avatar.html.twig', array(
            'avatar'    => $avatar,
            'form'      => $form->createView()
            ));
        
    }
    
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getEmailProfileFormHandler()
    {
        return $this->get('app.user_edit_email.handler');
    }
    
    /**
     * @return \Knarf\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getAvatarProfileFormHandler()
    {
        return $this->get('app.user_edit_avatar.handler');
    }

}
