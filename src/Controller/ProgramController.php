<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Category;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(Program $program):Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
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
     * @Route("/{programId}/season/{seasonId}", methods={"GET"}, name="season_show")
     * @return Response
     */
    public function showSeason(Program $programId, Season $seasonId)
    {
        $episodes = $seasonId->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $programId,
            'season' => $seasonId,
            'episodes' => $episodes,
        ]);
    }

    /**
     * Getting an episode by id
     *
     * @Route("/{programId}/seasons/{seasonId}/episodes/{episodeId}", methods={"GET"}, name="episode_show")
     * @return Response
     */
    public function showEpisode(Program $programId, Season $seasonId, Episode $episodeId)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $programId,
            'season' => $seasonId,
            'episode' => $episodeId,
        ]);
    }
}
