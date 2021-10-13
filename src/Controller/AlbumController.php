<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/album", name="album")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('album/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/album/add", name="add_album")
     */
    public function add(Request $request): Response
    {
        $user = $this->getUser();
        $album = new Album();
        $addAlbumForm = $this->createForm(AlbumType::class, $album);

        $addAlbumForm->handleRequest($request);

        if ($addAlbumForm->isSubmitted() && $addAlbumForm->isValid()) {
            $album = $addAlbumForm->getData();
            $album->setUser($user);
            var_dump($album);


            $this->em->persist($album);
            $this->em->flush();

            return $this->redirectToRoute('album');
        }

        return $this->render('album/add.html.twig', [
            'add_album_form' => $addAlbumForm->createView()
        ]);
    }

    /**
     * @Route("/album/show/{id}", name="show_album")
     */
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/album/edit/{id}", name="edit_album")
     */
    public function edit(Request $request, Album $id): Response
    {
        $updateAlbumForm = $this->createForm(AlbumFormType::class, $id);

        $updateAlbumForm->handleRequest($request);

        if ($updateAlbumForm->isSubmitted() && $updateAlbumForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('album');
        }

        return $this->render('album/edit.html.twig', [
            'edit_album_form' => $updateAlbumForm->createView()
        ]);
    }

    /**
     * @Route("/album/delete/{id}", name="delete_album")
     */
    public function delete(Album $id): Response
    {
        $this->em->remove($id);
        $this->em->flush();

        return $this->redirectToRoute('user');
    }
}
