<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
class Board
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'boards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner_id = null;

    #[ORM\Column(length: 50)]
    private ?string $board_name = null;

    #[ORM\OneToMany(mappedBy: 'board_id', targetEntity: TaskList::class)]
    private Collection $taskLists;

    public function __construct()
    {
        $this->taskLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnerId(): ?User
    {
        return $this->owner_id;
    }

    public function setOwnerId(?User $owner_id): self
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    public function getBoardName(): ?string
    {
        return $this->board_name;
    }

    public function setBoardName(string $board_name): self
    {
        $this->board_name = $board_name;

        return $this;
    }

    /**
     * @return Collection<int, TaskList>
     */
    public function getTaskLists(): Collection
    {
        return $this->taskLists;
    }

    public function addTaskList(TaskList $taskList): self
    {
        if (!$this->taskLists->contains($taskList)) {
            $this->taskLists->add($taskList);
            $taskList->setBoardId($this);
        }

        return $this;
    }

    public function removeTaskList(TaskList $taskList): self
    {
        if ($this->taskLists->removeElement($taskList)) {
            // set the owning side to null (unless already changed)
            if ($taskList->getBoardId() === $this) {
                $taskList->setBoardId(null);
            }
        }

        return $this;
    }
}
