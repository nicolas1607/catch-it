<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private AlbumRepository $albumRepo;
    private ItemRepository $itemRepo;
    private UserRepository $userRepo;

    public function __construct(AlbumRepository $albumRepo, ItemRepository $itemRepo, UserRepository $userRepo)
    {
        $this->albumRepo = $albumRepo;
        $this->itemRepo = $itemRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function user(): Response
    {
        $users = $this->userRepo->findAll();
        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/collection", name="admin_collection")
     */
    public function collection(): Response
    {
        $res = $this->albumRepo->findCreateByAdmin();
        // On récupère le nombre d'items par collection
        $collections = [];
        foreach ($res as $collection) {
            $count_items = $this->itemRepo->findCountByOneAlbum($collection->getName());
            array_push(
                $collections,
                [
                    $collection->getId(),
                    $collection->getName(),
                    $count_items, $collection->getAdded(),
                    $collection->getCreatedAt()
                ]
            );
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
        $items = $this->itemRepo->findCreateByAdmin();
        return $this->render('admin/item.html.twig', [
            'items' => $items
        ]);
    }
}
