<?php

namespace App\Controller;

use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $qb = $this->em->createQuery(
            "SELECT a FROM App:album a
            WHERE a.user IS NULL
            ORDER BY a.createdAt DESC"
        );
        $qb->setMaxResults(6);
        $lastAlbums = $qb->getResult();

        $qb = $this->em->createQuery(
            "SELECT a FROM App:album a
            WHERE a.user IS NULL
            ORDER BY a.added DESC"
        );
        $qb->setMaxResults(6);
        $mostAlbums = $qb->getResult();

        return $this->render('home/index.html.twig', [
            'lastAlbums' => $lastAlbums,
            'mostAlbums' => $mostAlbums
        ]);
    }
}
