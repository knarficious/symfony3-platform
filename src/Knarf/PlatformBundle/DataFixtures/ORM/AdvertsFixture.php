<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\Persistence\ObjectManager;
use Knarf\PlatformBundle\Entity\Advert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Description of AdvertsFixture
 *
 * @author franck
 */
class AdvertsFixture extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array 
    {
        return array (RubriqueFixtures::class, UserFixtures::class);
    }

    public function load(ObjectManager $manager) 
    {
        for ($i = 0; $i < 10; $i++)
        {
            $advert = new Advert();
            $advert->setContent('Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
                lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
                labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
                **turkey** shank eu pork belly meatball non cupim.
                Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
                laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
                capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
                picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
                occaecat lorem meatball prosciutto quis strip steak.
                Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
                mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
                strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
                cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
                fugiat.');
            $advert->setDate(new \DateTime('now'));
            $advert->setMedia($this->getReference(MediaFixtures::MEDIA_REF_2));
            $advert->setPublished(true);
            $advert->setRubrique($this->getReference(RubriqueFixtures::RUBRIQUE_REFERENCE_1));
            $advert->setTitle('advert '.$i);
            $advert->setUpDateAt(new \DateTime('now'));
            $advert->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));            
            
            $advert1 = new Advert();
            $advert1->setTitle('Pourquoi l\'univers sent-il la framboise?');
                    //->setSlug('pourquoi-l-univers-sent-il-la-framboise')
            $advert1->setContent('Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
                lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
                labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
                **turkey** shank eu pork belly meatball non cupim.
                Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
                laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
                capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
                picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
                occaecat lorem meatball prosciutto quis strip steak.
                Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
                mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
                strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
                cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
                fugiat.');
            $advert1->setPublished(true);
            $advert1->setMedia($this->getReference(MediaFixtures::MEDIA_REF_1));
            $advert1->setRubrique($this->getReference(RubriqueFixtures::RUBRIQUE_REFERENCE_2));
            $advert1->setUser($this->getReference('user2'));
            $advert1->setDate(new \DateTime('now'));
            $advert1->setUpDateAt(new \DateTime('now'));
            
            $advert2 = new Advert();
            $advert2->setTitle('Pourquoi l\'univers sent-il la framboise?');
                    //->setSlug('pourquoi-l-univers-sent-il-la-framboise')
            $advert2->setContent('Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
                lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
                labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
                **turkey** shank eu pork belly meatball non cupim.
                Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
                laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
                capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
                picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
                occaecat lorem meatball prosciutto quis strip steak.
                Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
                mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
                strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
                cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
                fugiat.');
            $advert2->setPublished(true);
            $advert2->setMedia($this->getReference(MediaFixtures::MEDIA_REF_3));
            $advert2->setRubrique($this->getReference(RubriqueFixtures::RUBRIQUE_REFERENCE_3));
            $advert2->setUser($this->getReference('user2'));
            $advert2->setDate(new \DateTime('now'));
            $advert2->setUpDateAt(new \DateTime('now'));
            
            $advert3 = new Advert();
            $advert3->setTitle('Pourquoi l\'univers sent-il la framboise?');
                    //->setSlug('pourquoi-l-univers-sent-il-la-framboise')
            $advert3->setContent('Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
                lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
                labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
                **turkey** shank eu pork belly meatball non cupim.
                Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
                laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
                capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
                picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
                occaecat lorem meatball prosciutto quis strip steak.
                Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
                mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
                strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
                cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
                fugiat.');
            $advert3->setPublished(true);
            $advert3->setMedia($this->getReference(MediaFixtures::MEDIA_REF_4));
            $advert3->setRubrique($this->getReference(RubriqueFixtures::RUBRiQUE_REFERENCE_4));
            $advert3->setUser($this->getReference('user3'));
            $advert3->setDate(new \DateTime('now'));
            $advert3->setUpDateAt(new \DateTime('now'));

        
            $manager->persist($advert);
            $manager->persist($advert1);
            $manager->persist($advert2);
            $manager->persist($advert3);
            
            $manager->flush();
        }
    }



}
