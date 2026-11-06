<?php

namespace App\Controller;

use App\Entity\Mesurement;
use App\Form\MesurementType;
use App\Repository\MesurementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mesurement')]
final class MesurementController extends AbstractController
{
    #[Route(name: 'app_mesurement_index', methods: ['GET'])]
    #[IsGranted('ROLE_MESUREMENT_INDEX')]
    public function index(MesurementRepository $mesurementRepository): Response
    {
        return $this->render('mesurement/index.html.twig', [
            'mesurements' => $mesurementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mesurement_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_MESUREMENT_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mesurement = new Mesurement();
        $form = $this->createForm(MesurementType::class, $mesurement, [
            'validation_groups' => 'new',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mesurement);
            $entityManager->flush();

            return $this->redirectToRoute('app_mesurement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mesurement/new.html.twig', [
            'mesurement' => $mesurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mesurement_show', methods: ['GET'])]
    #[IsGranted('ROLE_MESUREMENT_SHOW')]
    public function show(Mesurement $mesurement): Response
    {
        return $this->render('mesurement/show.html.twig', [
            'mesurement' => $mesurement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mesurement_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_MESUREMENT_EDIT')]
    public function edit(Request $request, Mesurement $mesurement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MesurementType::class, $mesurement, [
            'validation_groups' => 'new',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mesurement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mesurement/edit.html.twig', [
            'mesurement' => $mesurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mesurement_delete', methods: ['POST'])]
    #[IsGranted('ROLE_MESUREMENT_DELETE')]
    public function delete(Request $request, Mesurement $mesurement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mesurement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mesurement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mesurement_index', [], Response::HTTP_SEE_OTHER);
    }
}
