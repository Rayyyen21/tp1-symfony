<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TacheController extends AbstractController
{
    #[Route('/taches', name: 'app_taches')]
    public function index(TacheRepository $repository): Response
    {
        $taches = $repository->findAll();

        return $this->render('taches/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/taches/ajouter', name: 'app_tache_ajouter')]
    public function ajouter(EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setTitre('Nouvelle tâche');
        $tache->setDescription('Description de la tâche créée en dur.');
        $tache->setTerminee(false);
        // dateCreation est initialisée dans le constructeur

        $em->persist($tache);
        $em->flush();

        return new Response('Tâche créée avec l\'id : ' . $tache->getId());
    }

    #[Route('/taches/{id}', name: 'app_tache_detail', requirements: ['id' => '\\d+'])]
    public function detail(Tache $tache): Response
    {
        return $this->render('taches/detail.html.twig', [
            'tache' => $tache,
        ]);
    }

    #[Route('/taches/{id}/terminer', name: 'app_tache_terminer', requirements: ['id' => '\\d+'])]
    public function terminer(Tache $tache, EntityManagerInterface $em): Response
    {
        $tache->setTerminee(true);
        $em->persist($tache);
        $em->flush();

        return $this->redirectToRoute('app_taches');
    }
}
