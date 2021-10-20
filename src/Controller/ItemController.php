<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Album;
use App\Form\ItemType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
    public function addExist(Request $request, Item $item_id, Album $album_id): Response
    {
        $item_id->setAdded($item_id->getAdded() + 1);
        // On récupère l'album de l'utilisateur
        $qb = $this->em->createQuery(
            "SELECT a FROM App:album a
            WHERE a.user IS NOT NULL
            AND a.name IN (
                SELECT alb.name FROM App:album alb
                INNER JOIN App:item i
                WITH i.album = alb.id
                WHERE i.id = " . $item_id->getId() . ")"
        );
        // Si le user possède déjà la collection ou non
        if (count($qb->getResult()) == 0) {
            $user = $this->getUser();
            $album = new Album;
            $album->setName($album_id->getName())
                ->setDescription($album_id->getDescription())
                ->setUser($user);
            $album->setCreatedAt(new DateTimeImmutable());
            $user->addAlbum($album);
        } else {
            $album = $qb->getResult()[0];
        }
        // On récupère l'album d'origine pour la redirection
        $qb = $this->em->createQuery(
            "SELECT a FROM App:album a
            WHERE a.user IS NULL
            AND a.name = '" . $album->getName() . "'"
        );
        $origin = $qb->getResult()[0];
        // On ajoute l'item à l'album de l'utilisateur
        $item = new Item;
        $item->setName($item_id->getName())
            ->setDescription($item_id->getDescription())
            ->setAlbum($album);
        $album->addItem($item);

        $this->em->persist($item);
        $this->em->persist($album);
        $this->em->flush();

        $this->addFlash('success', $item_id->getName() . ' ajouté(e) avec succès !');

        return $this->redirectToRoute('show_album', ['id' => $origin->getId()]);
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
