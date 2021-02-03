<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $game_master;

    /**
     * @ORM\Column(type="integer")
     */
    private $current_frame;

    /**
     * @ORM\ManyToOne(targetEntity=Scenario::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scenario;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="game")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="games")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentFrame(): ?int
    {
        return $this->current_frame;
    }

    public function setCurrentFrame(int $current_frame): self
    {
        $this->current_frame = $current_frame;

        return $this;
    }

    public function getGameMaster(): ?User
    {
        return $this->game_master;
    }

    public function setGameMaster(?User $game_master): self
    {
        $this->game_master = $game_master;

        return $this;
    }

    public function getScenario(): ?Scenario
    {
        return $this->scenario;
    }

    public function setScenario(?Scenario $scenario): self
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setGame($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getGame() === $this) {
                $message->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

}
