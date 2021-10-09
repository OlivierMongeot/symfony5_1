<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration/creation", name="registration_creation")
     */
    public function creation(  Request $request,UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager ): Response
    {
        $user = new User();
        // $hash = $this->encoder->encodePassword($admin, "password");
        // $admin->setFullName("Admin");
        // $admin->setEmail("admin@gmail.com");
        // $admin->setPassword($hash);
        // $admin->setRoles(["ROLE_ADMIN"]);
        // $manager->persist($admin);

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user,  $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(["ROLE_USER"]);
            // $user->setFullName($user->getFullName());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homePage');
        }


        $formView = $form->createView();
        return $this->render('registration/index.html.twig', compact('formView'));
    }
}
