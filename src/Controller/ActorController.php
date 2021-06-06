<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * Show all rows from Actor’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['actors' => $actors]
        );
    }

    /**
     * Getting a actor by id
     *
     * @Route("/{id<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        if (!$actor) {
            throw $this->createNotFoundException(
                "Il n'y a pas d'acteurs renseignés pour cette série."
            );
        }

        $programs = $actor->getPrograms();

        return $this->render(
            'program/actor_show.html.twig',
            ['actor' => $actor,
            'programs' => $programs]
        );
    }
}