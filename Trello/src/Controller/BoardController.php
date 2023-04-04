<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\TaskList;
use App\Entity\Task;

use App\Form\BoardType;

use App\Form\newTaskListType;
use App\Repository\BoardRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

use App\Form\newTaskType;
use App\Controller\TaskListController;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/board')]
class BoardController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_board_index', methods: ['GET'])]
    public function index(BoardRepository $boardRepository): Response
    {
        $user = $this->getUser();
        $ownerBoards = $boardRepository->findBy(['owner' => $user], ['id' => 'DESC']);
        $userBoards = $user->getMyBoards();


        // dd($userBoards);

        return $this->render('board/index.html.twig', [
            'ownerBoards' => $ownerBoards,
            'userBoards' => $userBoards
        ]);
    }

    #[Route('/new', name: 'app_board_new', methods: ['GET', 'POST'])]
    public function new (Request $request, BoardRepository $boardRepository): Response
    {
        $board = new Board();
        $form = $this->createForm(BoardType::class, $board);
        $user = $this->getUser();
        $board->setOwnerId($user);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $boardRepository->save($board, true);

            return $this->redirectToRoute('app_board_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('board/new.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_board_show', methods: ['GET', 'POST'])]
    public function show(Board $board, Request $request, BoardRepository $boardRepository, TaskRepository $taskRepo): Response
    {
        // Nouveau tableau de tâches 
        $newTaskList = new TaskList();
        $taskListForm = $this->createForm(newTaskListType::class, $newTaskList);
        $taskListForm->handleRequest($request);
        $user = $this->getUser();
        // dd($user);
        if ($taskListForm->isSubmitted() && $taskListForm->isValid()) {
            $board->addTaskList($newTaskList);

            $boardRepository->save($board, true);

            $idFromRequest = $request->attributes->get('id');
            return $this->redirectToRoute('app_board_show', ['id' => $idFromRequest], Response::HTTP_SEE_OTHER);
        }
        $taskLists = $board->getTaskLists()->getValues();
        // dd($board->getTaskLists()->getValues()[0]->getTasks()->getValues()[0]->getUsers()->getValues());


        // dd($taskLists);
        return $this->render('board/show.html.twig', [
            'board' => $board,
            'formNewTaskList' => $taskListForm,
            'taskLists' => $taskLists,
            'userId' => $user->getId()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_board_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Board $board, BoardRepository $boardRepository): Response
    {
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardRepository->save($board, true);

            return $this->redirectToRoute('app_board_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('board/edit.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_board_delete', methods: ['POST'])]
    public function delete(Request $request, Board $board, BoardRepository $boardRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $board->getId(), $request->request->get('_token'))) {
            $boardRepository->remove($board, true);
        }
        dd($board->getId());

        return $this->redirectToRoute('app_board_index', [], Response::HTTP_SEE_OTHER);
    }
}