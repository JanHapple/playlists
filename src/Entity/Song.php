<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $band = null;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, inversedBy: 'songs')]
    private Collection $playlists;

    #[ORM\Column]
    #[Assert\LessThan(value: 60, message: 'Please enter a number between 0 and 59')]
    private ?int $minDuration = null;

    #[ORM\Column]
    #[Assert\LessThan(value: 60, message: 'Please enter a number between 0 and 59')]
    private ?int $secDuration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename = null;

    public function __construct(private readonly string $targetDirectory)
    {
        $this->playlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBand(): ?string
    {
        return $this->band;
    }

    public function setBand(string $band): static
    {
        $this->band = $band;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        $this->playlists->removeElement($playlist);

        return $this;
    }

    public function getMinDuration(): ?int
    {
        return $this->minDuration;
    }

    public function setMinDuration(int $minDuration): static
    {
        $this->minDuration = $minDuration;

        return $this;
    }

    public function getSecDuration(): ?int
    {
        return $this->secDuration;
    }

    public function setSecDuration(int $secDuration): static
    {
        $this->secDuration = $secDuration;

        return $this;
    }

    public function getFilePath(): ?string
    {
        $fileName = $this->filename;

        if($fileName) {
            return $this->targetDirectory . '/' . $fileName;
        }

        return 'defaults/backup-image.jpg';
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }
}
