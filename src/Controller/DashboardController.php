<?php

namespace App\Controller;

use App\Form\DuckType;
use App\Entity\Ducks;
use App\Repository\DucksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/dashboard/edit', name: 'dashboard_edit')]
    public function edit(Request $request, DucksRepository $ducksRepository, Security $security, UserPasswordHasherInterface $passwordHasher): Response
    {
        $ducks = $security->getUser();
        $form = $this->createForm(DuckType:: class, $ducks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($ducks, $plainPassword);
            $ducks->setPassword($hashedPassword);
            $ducksRepository->save($ducks, true);
            return $this->redirectToRoute('dashboard_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/index.html.twig', [
            'duck' => $ducks,
            'form' => $form,
        ]);
    }


}

