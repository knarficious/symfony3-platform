<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knarf\PlatformBundle\Entity\Contact;
use Knarf\PlatformBundle\Form\ContactType;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ContactController
 *
 * @author franck
 */
class ContactController extends Controller{
    
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        
         if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
         {
              $request = Request::createFromGlobals();
              
              $nom = $form['nom']->getData();
              $courriel = $form['courriel']->getData();
              $objet = $form['objet']->getData();
              $message = $form['message']->getData();

            $email = \Swift_Message::newInstance()
                ->setSubject($objet)
                ->setFrom(array($courriel))
                ->setTo('contact@norminfo.eu')
                ->setCharset('utf-8')    
                ->setContentType('text/html')    
                ->setBody($this->container->get('templating')->render(
                        'KnarfPlatformBundle:Contact:email.html.twig', array(
                            'nom'       => $nom,
                            'courriel'  => $courriel,
                            'objet'     => $objet,
                            'message'   => $message
                        ))
                )
            ;

            $this->get('mailer')->send($email);
             
            $this->addFlash('notice', 'Votre email a été envoyé avec succès');
            
            
            
            $this->redirectToRoute('knarf_platform_home');
         }
        
        return $this->render('KnarfPlatformBundle:Contact:contact.html.twig',
                array('form' => $form->createView()));
    }
}
