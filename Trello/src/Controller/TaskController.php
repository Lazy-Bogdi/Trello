<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Entity\TaskList;
use App\Repository\BoardRepository;
use App\Repository\TaskRepository;
use App\Repository\TaskListRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/board')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/{idBoard}/{idTL}TL/new-task', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new (Request $request, TaskRepository $taskRepository, TaskListRepository $taskListRepo, UserRepository $userRepository): Response
    {

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $taskListId = $request->attributes->get('idTL');
        $boardId = $request->attributes->get('idBoard');


        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTaskListId($taskListRepo->findOneBy(['id' => $taskListId]));
            $usersArray = $form->get('users')->getData()->getValues();
            foreach ($usersArray as $users) {
                $users->addTask($task);
                $userRepository->save($users, true);
            }

            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_board_show', ['id' => $boardId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{idBoard}/{idTL}TL/{idT}T/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskRepository $taskRepository, BoardRepository $boardRepository, UserRepository $userRepository): Response
    {
        $boardId = $request->attributes->get('idBoard');
        $board = $boardRepository->findOneBy(['id' => $boardId]);

        $taskId = $request->attributes->get('idT');
        $task = $taskRepository->findOneBy(['id' => $taskId]);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usersArray = $form->get('users')->getData()->getValues();
            dump($usersArray);

            // if (count($task->getUsers()->getValues()) > 0) {

            foreach ($task->getUsers()->getValues() as $user) {
                $user->removeTask($task);
                // $taskRepository->save($task, false);
            }
            
            // // }
            dump($task->getUsers()->getValues());

            if (count($usersArray) > 0) {
                foreach ($usersArray as $users) {
                    $users->addTask($task);
                    $userRepository->save($users, true);
                }
                dump($task->getUsers()->getValues());
            }
            $taskRepository->save($task, true);


            return $this->redirectToRoute('app_board_show', ['id' => $boardId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
            'board' => $board
        ]);
    }

    #[Route('/{idBoard}/{idT}T/delete', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, TaskRepository $taskRepository): Response
    {
        $boardId = $request->attributes->get('idBoard');
        $taskId = $request->attributes->get('idT');
        $task = $taskRepository->findOneBy(['id'=>$taskId]);

        // $board = $boardRepository->findOneBy(['id' => $boardId]);
        
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_board_show', ['id' => $boardId], Response::HTTP_SEE_OTHER);
    }
}