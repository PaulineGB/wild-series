<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Form\SearchProgramType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\ProgramRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'index.html.twig',
            ['programs' => $programs,
            'categories' => $categories]
        );
    }

    /**
     * @Route("/my-profile", methods={"GET"}, name="app_profile")
     */
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('security/user_profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/", name="app_search")
     * @return Response A response instance
     */
    public function searchProgram(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findBy(['title' => $search]);
        } else {
            $programs = $programRepository->findAll();
        };

        return $this->render('_search.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
            ]);
    }
}
