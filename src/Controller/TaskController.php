<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task_list_todo")
     * @param TaskRepository $repo
     * @return Response
     */
    public function indexDone(TaskRepository $repo): Response
    {
        $tasks = $repo->findBy(['isDone' => false, 'inProgress' => false], ['createdAt' => 'DESC']);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

    }

    /**
     * @Route("/task/done", name="task_list_done")
     * @param TaskRepository $repo
     * @return Response
     */
    public function indexNotDone(TaskRepository $repo): Response
    {
        $tasks = $repo->findBy(['isDone' => true], ['createdAt' => 'DESC']);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

    }

    /**
     * @Route("/task/inprogress", name="task_list_progress")
     * @param TaskRepository $repo
     * @return Response
     */
    public function indexInProgress(TaskRepository $repo): Response
    {
        $tasks = $repo->findBy(['inProgress' => true], ['createdAt' => 'DESC']);

        //dd($tasks);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

    }

    /**
     * @Route("/task/assign/{id}", name="task_assign")
     * @return Response
     */
    public function assignTask(): Response
    {
        $user = $this->getUser();




        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);

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
            $em = $this->getDoctrine()->getManager();

            //assign current user to created task
            //$user = $this->getUser();

            //dd($user);
            $task->setUser($user)
                ->setInProgress(false);


            $em->persist($task);
            $em->flush();

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
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $user = $this->getUser();

        if ( $this->isGranted('ROLE_ADMIN') || $task->getUser() == $user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('homepage');

        }
        $this->addFlash('error', 'Vous n\'avez pas le droit de supprimer cette tâche.');
        return $this->redirectToRoute('homepage');


    }
}
