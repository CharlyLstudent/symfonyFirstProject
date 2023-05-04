<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManualcrudController extends AbstractController
{//Display all
    #[Route('/manualcrud', name: 'app_manualcrud', methods: ['GET'])]
    public function index(QuackRepository $quackRepository): Response
    {
        return $this->render('manualcrud/index.html.twig', [
            'quacks' => $quackRepository->findAll(),
        ]);
    }
    //Add one
    #[Route('manualcrud/new', name: 'app_manualcrud_new',  methods: ['GET', 'POST'])]
    public function new(Request $request, QuackRepository $quackRepository): Response
    {
        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quackRepository->save($quack, true);

            return $this->redirectToRoute('app_manualcrud', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('manualcrud/new.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);
    }
//Show one
    #[Route('manualcrud/{id}', name: 'app_manualcrud_show', methods: ['GET'])]
    public function show(Quack $quack): Response
    {
        return $this->render('manualcrud/show.html.twig', [
            'quack' => $quack,
        ]);
    }
//Edit
    #[Route('manualcrud/{id}/edit', name: 'app_manualcrud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quackRepository->save($quack, true);
            return $this->redirectToRoute('app_manualcrud', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('manualcrud/edit.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);
    }
//Delete
    #[Route('manualcrud/{id}/delete', name: 'app_manualcrud_delete', methods: ['POST'])]
    public function delete(Quack $quack, QuackRepository $quackRepository): Response
    {
        $quackRepository->remove($quack, true);
        return $this->redirectToRoute('app_manualcrud', [], Response::HTTP_SEE_OTHER);
    }
}