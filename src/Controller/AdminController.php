<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function user(): Response
    {
        $user = $this->getUser();
        $users = $this->em->getRepository(User::class)->findAll();
        return $this->render('admin/user.html.twig', [
            'user' => $user,
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/collection", name="admin_collection")
     */
    public function collection(): Response
    {
        $user = $this->getUser();
        $qb = $this->em->createQuery(
            "SELECT a.id, a.name FROM App:album a
            WHERE a.user is NULL
            ORDER BY a.createdAt DESC"
        );
        $res = $qb->getResult();
        // On récupère le nombre d'items par collection
        $collections = [];
        foreach ($res as $collection) {
            $qb = $this->em->createQuery(
                "SELECT count(i.name) FROM App:item i
                INNER JOIN App:album a
                WITH a.id = i.album
                WHERE a.name = '" . $collection['name'] . "'"
            );
            array_push($collections, [$collection['id'], $collection['name'], $qb->getResult()[0][1]]);
        }
        return $this->render('admin/collection.html.twig', [
            'user' => $user,
            'collections' => $collections
        ]);
    }
}
