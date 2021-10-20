<?php

namespace App\Controller;

use App\Entity\Item;
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
        $users = $this->em->getRepository(User::class)->findAll();
        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/collection", name="admin_collection")
     */
    public function collection(): Response
    {
        $qb = $this->em->createQuery(
            "SELECT a.id, a.name, a.added, a.createdAt 
            FROM App:album a
            WHERE a.user is NULL
            ORDER BY a.id ASC"
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
            array_push($collections, [$collection['id'], $collection['name'], $qb->getResult()[0][1], $collection['added'], $collection['createdAt']]);
        }
        return $this->render('admin/collection.html.twig', [
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/admin/item", name="admin_item")
     */
    public function item(): Response
    {
        $qb = $this->em->createQuery(
            "SELECT i FROM App:item i
            INNER JOIN App:album a
            WITH i.album = a.id
            WHERE a.user IS NULL"
        );
        $items = $qb->getResult();
        return $this->render('admin/item.html.twig', [
            'items' => $items
        ]);
    }
}
