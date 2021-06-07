<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Actor;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify) : Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/{slug}", methods={"GET"}, name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}})
     * @return Response
     */
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                "Il n'existe pas de série correspondant à ce numéro."
            );
        }

        $seasons = $program->getSeasons();

        return $this->render(
            'program/show.html.twig',
            ['program' => $program,
            'seasons' => $seasons]
        );
    }

    /**
     * Getting a season by id
     *
     * @Route("/{programSlug}/season/{seasonId}", methods={"GET"}, name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @return Response
     */
    public function showSeason(Program $program, Season $season)
    {
        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * Getting an episode by id
     *
     * @Route("/{programSlug}/season/{seasonId}/episode/{episodeSlug}", methods={"GET"}, name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeSlug": "slug"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    /**
     * Getting an actor by id
     *
     * @Route("/{programSlug}/actor/{actorId}", methods={"GET"}, name="actor_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @return Response
     */
    public function showActor(Program $program, Actor $actorId)
    {
        return $this->render('program/actor_show.html.twig', [
            'program' => $program,
            'actor' => $actorId,
        ]);
    }
}
