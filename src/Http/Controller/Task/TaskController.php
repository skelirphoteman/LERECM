<?php

namespace App\Http\Controller\Task;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Domain\Task\Entity\Task;
use App\Http\Form\AddTaskType;
use App\Infrastructure\Task\TaskService;
/**
 * Class TaskController
 * @package App\Http\Controller\Task
 * @Route("/app/task/")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("add", name="app_task_add")
     */
    public function addTask(Request $request, TaskService $taskService) : Response
    {
        $task = new Task();
        $task->setCompany($this->getUser()->getCompany());

        $formTask = $this->createForm(AddTaskType::class, $task);

        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $task = $formTask->getData();

            $taskResponse = $taskService->addTask($task);
            if($taskResponse)
            {
                $this->addFlash('danger', $taskResponse);
            }else {
                $this->addFlash('success', "La tâche à bien été crée.");
                return $this->redirectToRoute("app_index");
            }

        }

        return $this->render('app/task/add.html.twig', [
            'form_task' => $formTask->createView(),
        ]);
    }

    /**
     * @Route("end/{task}", name="app_task_end")
     */
    public function endTask(Task $task = null, Request $request, TaskService $taskService) : Response
    {
        if($task == null){
            throw $this->createNotFoundException('Aucune tâche trouvé');
        }else {
            $taskResponse = $taskService->endTask($task);
            if($taskResponse){
                $this->addFlash('danger', $taskResponse);
            }else {
                $this->addFlash('success', "La tâche est bien terminé.");
            }
        }
        return $this->redirectToRoute("app_task_list");
    }

    /**
     * @Route("list", name="app_task_list")
     */
    public function listTask() : Response
    {
        $tasks_progress = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findby(['state' => 0], ['end_at' => 'asc']);


        $tasks_end = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findby(['state' => 1], ['end_at' => 'asc']);


        return $this->render("app/task/list.html.twig",[
            'tasks_progress' => $tasks_progress,
            'tasks_end' => $tasks_end,
        ]);
    }

    /**
     * @Route("delete/{task}", name="app_task_delete")
     */
    public function deleteTask(Task $task = null, Request $request, TaskService $taskService) : Response
    {
        if($task == null){
            throw $this->createNotFoundException('Aucune tâche trouvé');
        }else {
            $taskResponse = $taskService->deleteTask($task);
            if($taskResponse){
                $this->addFlash('danger', $taskResponse);
            }else {
                $this->addFlash('success', "La tâche a bien été supprimé.");
            }
        }
        return $this->redirectToRoute("app_task_list");
    }


}