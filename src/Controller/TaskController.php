<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 *
 * @IsGranted("ROLE_USER")
 *
 * @package App\Controller
 */
class TaskController extends AbstractController
{

    const LIMIT = 6;
    private PaginatorInterface $paginator;

    private EntityManagerInterface $manager;

    /**
     * TaskController constructor.
     *
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $manager)
    {
        $this->paginator = $paginator;
        $this->manager = $manager;
    }

    /**
     * @Route("/task", name="task_list")
     *
     * @param PaginatorInterface $paginator
     * @param TaskRepository $repo
     * @param Request $request
     * @return Response
     */
    public function indexAction(TaskRepository $repo, Request $request)
    {
        $tasks = $this->paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            self::LIMIT
        );

        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

    }

    /**
     * @Route("/task/todo", name="task_list_todo")
     * @param TaskRepository $repo
     * @return Response
     */
    public function indexTodo(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAllTodo(),
            $request->query->getInt('page',1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
            ]
        );

    }

    /**
     * @Route("/task/done", name="task_list_done")
     * @param TaskRepository $repo
     * @return Response
     */
    public function indexDone(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAllDone(),
            $request->query->getInt('page',1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
            ]
        );

    }

    /**
     * @Route("/task/inprogress", name="task_list_progress")
     * @param TaskRepository $repo
     * @return Response
     */
    public function indexInProgress(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAllInProgress(),
            $request->query->getInt('page',1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
            ]
        );

    }

    /**
     * @Route("/task/assign/{id}", name="task_assign", methods={"POST"})
     * @param Task $task
     * @return Response
     */
    public function assignTask(Task $task): Response
    {
        $task->setAssignedTo($this->getUser())
            ->setInProgress(true);

        $this->manager->persist($task);
        $this->manager->flush();

        $this->addFlash('success', 'La tâche vous a été assigné.');

        return new JsonResponse(['success' => 1]);
    }


    /**
     * @Route("/tasks/create", name="task_create")
     * @IsGranted("ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $user = $this->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUser($user)
                ->setInProgress(false);


            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'task/edit.html.twig',
            [
                'form' => $form->createView(),
                'task' => $task,
            ]
        );
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());

        $this->manager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {

        if ($task->getUser() == $this->getUser()) {

            $this->manager->remove($task);
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('homepage');

        }
        $this->addFlash('error', 'Vous n\'avez pas le droit de supprimer cette tâche.');

        return $this->redirectToRoute('task_list');

    }
}
