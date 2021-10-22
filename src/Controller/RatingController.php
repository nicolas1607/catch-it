<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rating;
use DateTimeImmutable;
use App\Form\RatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/rating/add/{item_id}", name="add_rating")
     */
    public function add(Request $request, Item $item_id): Response
    {
        $rating = new Rating();
        $addRatingForm = $this->createForm(RatingType::class, $rating);
        $addRatingForm->handleRequest($request);

        if ($addRatingForm->isSubmitted() && $addRatingForm->isValid()) {
            $rating = $addRatingForm->getData();
            $rating->setItem($item_id)
                ->setUser($this->getUser())
                ->setIsValid(false)
                ->setCreatedAt(new DateTimeImmutable());
            $item_id->addRating($rating);

            $this->em->persist($rating);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a été pris en compte et sera traité');

            return $this->redirectToRoute('show_item', ['id' => $item_id->getId()]);
        }

        return $this->render('rating/add.html.twig', [
            'item' => $item_id,
            'add_rating_form' => $addRatingForm->createView()
        ]);
    }

    /**
     * @Route("/rating/delete/{id}", name="delete_rating")
     */
    public function delete(Rating $rating): Response
    {
        $this->em->remove($rating);
        $this->em->flush();

        return $this->redirectToRoute('profile');
        // return $this->redirectToRoute('show_item', ['id' => $rating->getItem()->getId()]);
    }

    /**
     * @Route("/rating/valid/{id}", name="valid_rating")
     */
    public function valid(Rating $rating): Response
    {
        $rating->setIsValid(true);
        $this->em->persist($rating);
        $this->em->flush();

        return $this->redirectToRoute('admin_rating');
        // return $this->redirectToRoute('show_item', ['id' => $rating->getItem()->getId()]);
    }
}
