<?php

namespace App\Entity\Blog;

use App\Entity\User\User;
use App\Repository\ORM\Blog\PostRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post:read", "post:write"})
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"title"})
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post:read", "post:write"})
     */
    private ?string $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"post:read"})
     */
    private ?DateTimeInterface $publishedAt = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post:read"})
     */
    private ?string $status = null;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post:read", "post:write"})
     */
    private ?string $digest = null;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="posts")
     * @Groups({"post:read", "post:write"})
     */
    private ?Collection $categories;

    /**
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
     *
     * @var ?File
     */
    private ?File $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var ?EmbeddedFile
     */
    private ?EmbeddedFile $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?UserInterface $author;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * If digest null, set it from content
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generateDigest()
    {
        if (null === $this->getDigest() || !strlen($this->getDigest())) {
            $this->setDigest(substr(htmlentities(strip_tags($this->getContent())), 0, 250) . "...");
        }
    }

    /**
     * @Groups({"post:read"})
     */
    public function getTextContent(): string
    {
        return html_entity_decode(strip_tags($this->content));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status, $context = []): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDigest(): ?string
    {
        return $this->digest;
    }

    public function setDigest(string $digest): self
    {
        $this->digest = $digest;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @param File|UploadedFile $imageFile
     * @throws Exception
     */
    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image)
    {
        $this->image = $image;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }

    public function getAuthor(): ?UserInterface
    {
        return $this->author;
    }

    public function setAuthor(?UserInterface $author): self
    {
        $this->author = $author;

        return $this;
    }
}
