<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Knarf\UserBundle\Form\Type\User\EditEmailType;
use Knarf\UserBundle\Form\Type\User\EditAvatarType;
use Knarf\UserBundle\Entity\User\Profile;

/**
 * Description of UpdateProfileController
 *
 * @author franck
 */
class UpdateProfileController extends Controller 
{
   /**
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editEmailAction(Request $request)
    {
        $data = new Profile($this->getUser());
        $form = $this->createForm(EditEmailType::class, $data);
        
        if($this->getEmailProfileFormHandler()->handle($form, $request))
        {
            $this->addFlash('notice', 'Votre profil a bien été mis à jour.');
            return $this->redirect($this->generateUrl('profile'));
        }
        
        return $this->render('KnarfUserBundle:Security:edit_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editImageAction(Request $request)
    {
        $data = new Profile($this->getUser());
        $form = $this->createForm(EditAvatarType::class, $data);
        
        if($this->getAvatarProfileFormHandler()->handle($form, $request))
        {
            $this->addFlash('notice', 'Votre profil a bien été mis à jour.');
            return $this->redirect($this->generateUrl('profile'));
        }
        
        return $this->render('KnarfUserBundle:Security:edit_image.html.twig', [
            'form' => $form->createView(),
        ]);
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
