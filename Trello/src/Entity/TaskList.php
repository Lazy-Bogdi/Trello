<?php

namespace App\Entity;

use App\Repository\TaskListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskListRepository::class)]
class TaskList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $task_list_name = null;

    #[ORM\ManyToOne(inversedBy: 'taskLists')]
    private ?Board $board_id = null;

    #[ORM\OneToMany(mappedBy: 'task_list_id', targetEntity: Task::class)]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskListName(): ?string
    {
        return $this->task_list_name;
    }

    public function setTaskListName(string $task_list_name): self
    {
        $this->task_list_name = $task_list_name;

        return $this;
    }

    public function getBoardId(): ?Board
    {
        return $this->board_id;
    }

    public function setBoardId(?Board $board_id): self
    {
        $this->board_id = $board_id;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setTaskListId($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getTaskListId() === $this) {
                $task->setTaskListId(null);
            }
        }

        return $this;
    }
}
