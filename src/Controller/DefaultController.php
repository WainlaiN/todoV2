<?php


namespace App\Controller;


use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(PaginatorInterface $paginator, TaskRepository $repo, Request $request)
    {
        $tasks = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            5
        );

        return $this->render('default/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

    }
}