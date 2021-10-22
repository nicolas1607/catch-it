<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Album;
use App\Form\ItemType;
use App\Repository\AlbumRepository;
use App\Repository\ItemRepository;
use App\Repository\RatingRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    private EntityManagerInterface $em;
    private AlbumRepository $albumRepo;
    private ItemRepository $itemRepo;
    private RatingRepository $ratingRepo;

    public function __construct(EntityManagerInterface $em, AlbumRepository $albumRepo, ItemRepository $itemRepo, RatingRepository $ratingRepo)
    {
        $this->em = $em;
        $this->albumRepo = $albumRepo;
        $this->itemRepo = $itemRepo;
        $this->ratingRepo = $ratingRepo;
    }

    /**
     * @Route("/item/add/{album_id}", name="add_item")
     */
    public function add(Request $request, Album $album_id): Response
    {
        $item = new Item();
        $addItemForm = $this->createForm(ItemType::class, $item);
        $addItemForm->handleRequest($request);

        if ($addItemForm->isSubmitted() && $addItemForm->isValid()) {
            $item = $addItemForm->getData();
            $item->setAlbum($album_id)
                ->setAdded(0);
            $album_id->addItem($item);

            $this->em->persist($item);
            $this->em->flush();

            return $this->redirectToRoute('show_album', ['id' => $album_id->getId()]);
        }

        return $this->render('item/add.html.twig', [
            'album' => $album_id,
            'add_item_form' => $addItemForm->createView()
        ]);
    }

    /**
     * @Route("/collection/item/add/{item_id}/{album_id}", name="add_item_exist")
     */
    public function addExist(Item $item_id, Album $album_id): Response
    {
        $item_id->setAdded($item_id->getAdded() + 1);
        $userAlbum = $this->albumRepo->findUserAlbumByItem($item_id->getId());
        // Si le user ne possède pas encore la collection, on lui crée
        if (count($userAlbum) == 0) {
            $user = $this->getUser();
            $userAlbum = new Album;
            $userAlbum->setName($album_id->getName())
                ->setDescription($album_id->getDescription())
                ->setUser($user);
            $userAlbum->setCreatedAt(new DateTimeImmutable());
            $user->addAlbum($userAlbum);
            $album_id->setAdded($album_id->getAdded() + 1);
        } else {
            $userAlbum = $userAlbum[0];
        }
        // On ajoute l'item à l'album de l'utilisateur
        $albumOrigin = $this->albumRepo->findByNameCreateByAdmin($userAlbum->getName());
        $itemOrigin = $this->itemRepo->findUserByIdAndItemByName($this->getUser()->getId(), $item_id->getName());
        if ($itemOrigin) {
            $this->addFlash('danger', 'Vous posséder déjà cet item');
            return $this->redirectToRoute('show_album', ['id' => $albumOrigin->getId()]);
        } else {
            $item = new Item;
            $item->setName($item_id->getName())
                ->setDescription($item_id->getDescription())
                ->setAlbum($userAlbum);
            $userAlbum->addItem($item);

            $this->em->persist($item);
            $this->em->persist($userAlbum);
            $this->em->flush();

            $this->addFlash('success', $item_id->getName() . ' ajouté(e) avec succès !');
            return $this->redirectToRoute('show_album', ['id' => $albumOrigin->getId()]);
        }
    }

    /**
     * @Route("/item/show/{id}", name="show_item")
     */
    public function show(Item $item): Response
    {
        $count_rating = $this->itemRepo->findCountRating($item->getId());
        $rating = $this->ratingRepo->findRatingByItem($item);
        return $this->render('item/show.html.twig', [
            'item' => $item,
            'rating' => $rating,
            'count' => $count_rating
        ]);
    }

    /**
     * @Route("/item/edit/{id}", name="edit_item")
     */
    public function edit(Request $request, Item $id): Response
    {
        $updateItemForm = $this->createForm(ItemType::class, $id);
        $updateItemForm->handleRequest($request);

        if ($updateItemForm->isSubmitted() && $updateItemForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('item');
        }

        return $this->render('item/edit.html.twig', [
            'edit_item_form' => $updateItemForm->createView()
        ]);
    }

    /**
     * @Route("/item/delete/{id}", name="delete_item")
     */
    public function delete(Item $item): Response
    {
        $this->em->remove($item);
        $this->em->flush();

        return $this->redirectToRoute('show_album', ['id' => $item->getAlbum()->getId()]);
    }
}
