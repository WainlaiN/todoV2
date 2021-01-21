<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
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
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    private const LIMIT = 6;
    private PaginatorInterface $paginator;

    private EntityManagerInterface $manager;

    /**
     * TaskController constructor.
     *
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $manager
     */
    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $manager)
    {
        $this->paginator = $paginator;
        $this->manager = $manager;
    }

    /**
     * @Route("/task/all", name="task_list")
     *
     * @param PaginatorInterface $paginator
     * @param TaskRepository $repo
     * @param Request $request
     * @return Response
     */
    public function indexAll(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
                'btn' => 'btnAll'
            ]
        );
    }

    /**
     * @Route("/task/todo", name="task_list_todo")
     *
     * @param TaskRepository $repo
     * @param Request $request
     * @return Response
     */
    public function indexTodo(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAllTodo(),
            $request->query->getInt('page', 1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
                'btn' => 'btnTodo'
            ]
        );
    }

    /**
     * @Route("/task/done", name="task_list_done")
     *
     * @param TaskRepository $repo
     * @param Request $request
     * @return Response
     */
    public function indexDone(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAllDone(),
            $request->query->getInt('page', 1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
                'btn' => 'btnDone'
            ]
        );
    }

    /**
     * @Route("/task/inprogress", name="task_list_progress")
     *
     * @param TaskRepository $repo
     * @param Request $request
     * @return Response
     */
    public function indexInProgress(TaskRepository $repo, Request $request): Response
    {
        $tasks = $this->paginator->paginate(
            $repo->findAllInProgress(),
            $request->query->getInt('page', 1),
            self::LIMIT
        );

        return $this->render(
            'task/index.html.twig',
            [
                'controller_name' => 'TaskController',
                'tasks' => $tasks,
                'btn' => 'btnInProgress'
            ]
        );
    }

    /**
     * @Route("/task/assign/{id}", name="task_assign", methods={"POST"})
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function assignTask(Task $task): JsonResponse
    {
        $task->setAssignedTo($this->getUser())
            ->setAssignedAt(new \DateTime('NOW'));

        $this->manager->persist($task);
        $this->manager->flush();

        $this->addFlash('success', 'La tâche vous a été assigné.');

        return new JsonResponse(['success' => 1]);
    }


    /**
     * @Route("/tasks/create", name="task_create")
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $task = new Task();
        $user = $this->getUser();
        $task->setUser($user);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);

            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @param Task $task
     * @param Request $request
     * @return Response
     */
    public function editAction(Task $task, Request $request): Response
    {
        if ($this->isGranted('edit', $task)) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->manager->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('task_list');
            }
            return $this->render(
                'task/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'task' => $task,
                ]
            );
        }

        $this->addFlash('error', 'Vous n\'avez pas le droit d\'editer cette tâche.');

        return $this->redirectToRoute('task_list');
    }


    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     *
     * @param Task $task
     * @return Response
     */
    public function toggleTaskAction(Task $task): Response
    {
        if ($this->isGranted('validate', $task)) {
            if ($task->isDone()) {
                $task->setIsDone(false)
                    ->setAssignedTo(null)
                    ->setAssignedAt(null);

                $this->addFlash('success', sprintf('La tâche %s a été réinitialisé.', $task->getTitle()));
            } else {
                $task->setIsDone(true);
                $this->addFlash('success', sprintf('La tâche %s a été marqué comme validé.', $task->getTitle()));
            }

            $this->manager->flush();

            return $this->redirectToRoute('task_list');
        }
        $this->addFlash('error', 'Vous n\'avez pas le droit de modifier cette tâche.');

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task): Response
    {
        if ($this->isGranted('delete', $task)) {
            $this->manager->remove($task);
            $this->manager->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('task_list');
        }

        $this->addFlash('error', 'Vous n\'avez pas le droit de supprimer cette tâche.');

        return $this->redirectToRoute('task_list');
    }
}
