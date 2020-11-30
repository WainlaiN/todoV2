<?php


namespace App\Controller;


use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(TaskRepository $repository)
    {
        $tasks = $repository->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

    }
}