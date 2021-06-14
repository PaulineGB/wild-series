<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
}
