<?php

namespace App\Controller;

use App\Entity\Album;
use DateTimeImmutable;
use App\Form\AlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AlbumController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/browse", name="browse")
     */
    public function browse(): Response
    {
        $user = $this->getUser();
        $qb = $this->em->createQuery(
            "SELECT alb FROM App:album alb
            WHERE alb.user is NULL
            ORDER BY alb.createdAt DESC"
        );
        $albums = $qb->getResult();

        return $this->render('album/browse.html.twig', [
            'albums' => $albums
        ]);
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
        // $cat = $this->em->getRepository(Category::class)->findAll();
        // $categories = [];
        // foreach ($cat as $c) {
        //     array_push($categories, $c->getName());
        // }
        $addAlbumForm = $this->createForm(AlbumType::class, $album, array(
            // 'categories' => $categories
        ));

        $addAlbumForm->handleRequest($request);

        if ($addAlbumForm->isSubmitted() && $addAlbumForm->isValid()) {
            $album = $addAlbumForm->getData();
            $album->setCreatedAt(new DateTimeImmutable());

            $this->em->persist($album);
            $this->em->flush();

            return $this->redirectToRoute('admin_collection');
        }

        return $this->render('album/add.html.twig', [
            'add_album_form' => $addAlbumForm->createView()
        ]);
    }

    /**
     * @Route("/album/add/{id}", name="add_album_exist")
     */
    public function addExist(Request $request, Album $id): Response
    {
        $user = $this->getUser();

        $album = new Album;
        $album->setName($id->getName())
            ->setDescription($id->getDescription())
            ->setUser($user);
        $album->setCreatedAt(new DateTimeImmutable());
        $user->addAlbum($album);

        $qb = $this->em->createQuery(
            "SELECT a FROM App:album a
            WHERE a.user IS NULL
            AND a.name = '" . $album->getName() . "'"
        );
        $origin = $qb->getResult()[0];

        $this->em->persist($user);
        $this->em->persist($album);
        $this->em->flush();

        $this->addFlash('success', $id->getName() . ' ajouté avec succès à vos collections !');

        return $this->redirectToRoute('browse');
    }

    /**
     * @Route("/album/show/{id}", name="show_album")
     */
    public function show(Album $album): Response
    {
        $qb = $this->em->createQuery(
            "SELECT a FROM App:album a
            WHERE a.user IS NULL
            AND a.name = '" . $album->getName() . "'"
        );
        $origin = $qb->getResult()[0];
        return $this->render('album/show.html.twig', [
            'album' => $album,
            'origin' => $origin
        ]);
    }

    /**
     * @Route("/album/edit/{id}", name="edit_album")
     */
    public function edit(Request $request, Album $id): Response
    {
        $updateAlbumForm = $this->createForm(AlbumType::class, $id);

        $updateAlbumForm->handleRequest($request);

        if ($updateAlbumForm->isSubmitted() && $updateAlbumForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('show_album', array('id' => $id->getId()));
        }

        return $this->render('album/edit.html.twig', [
            'edit_album_form' => $updateAlbumForm->createView()
        ]);
    }

    /**
     * @Route("/album/{id}", name="delete_album")
     */
    public function delete(Request $request, Album $id): Response
    {
        $user = $this->getUser();
        $this->em->remove($id);
        $this->em->flush();

        if (in_array('ROLE_ADMIN', $user->getRoles())) return $this->redirectToRoute('admin_collection');
        else return $this->redirectToRoute('album');
    }
}
