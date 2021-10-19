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
            "SELECT alb FROM App:album alb
            WHERE alb.user IS NULL
            ORDER BY alb.createdAt DESC"
        );
        $qb->setMaxResults(6);
        $albums = $qb->getResult();

        return $this->render('home/index.html.twig', [
            'lastAlbums' => $albums
        ]);
    }
}
