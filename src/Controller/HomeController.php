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

        $qb = $this->em->createQuery(
            "SELECT i.id, i.name, i.added, i.description, a.name album 
            FROM App:item i
            INNER JOIN App:album a
            WITH i.album = a.id
            WHERE a.user IS NULL
            ORDER BY i.added DESC"
        );
        $qb->setMaxResults(6);
        $mostItems = $qb->getResult();

        return $this->render('home/index.html.twig', [
            'lastAlbums' => $lastAlbums,
            'mostAlbums' => $mostAlbums,
            'mostItems' => $mostItems
        ]);
    }
}
