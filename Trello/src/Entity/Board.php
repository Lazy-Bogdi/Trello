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

  

    #[ORM\Column(length: 50)]
    private ?string $board_name = null;

    #[ORM\OneToMany(mappedBy: 'board', targetEntity: TaskList::class)]
    private Collection $taskLists;

    #[ORM\ManyToOne(inversedBy: 'boards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'board')]
    private Collection $users;



    public function __construct()
    {
        $this->taskLists = new ArrayCollection();
        $this->users = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwnerId(): ?User
    {
        return $this->owner;
    }

    public function setOwnerId(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addBoardId($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeBoardId($this);
        }

        return $this;
    }

}
