<?php

namespace App\Controller;

use App\Form\TaskListType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\BoardRepository;
use App\Repository\TaskListRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/board')]
class TaskListController extends AbstractController
{
    #[Route('/{idBoard}/{idTL}TL/edit', name: 'app_tasklist_edit', methods: ['GET', 'POST'])]
    public function editTaskList(Request $request, TaskRepository $taskRepository, BoardRepository $boardRepository, UserRepository $userRepository, TaskListRepository $taskListRepo): Response
    {
        $boardId = $request->attributes->get('idBoard');
        $board = $boardRepository->findOneBy(['id' => $boardId]);
        $taskListId = $request->attributes->get('idTL');
        $taskList = $taskListRepo->findOneBy(['id' => $taskListId]);
        // dd(taskList);


        $form = $this->createForm(TaskListType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskListRepo->save($taskList, true);

            return $this->redirectToRoute('app_board_show', ['id' => $boardId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task_list/edit.html.twig', [
            // 'task' => $task,
            'form' => $form,
            'board' => $board
        ]);
    }
}