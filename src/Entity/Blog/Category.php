<?php

namespace App\Entity\Blog;

use App\Repository\ORM\Blog\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Gedmo\Tree(type="nested")
 */
class Category
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
     * @Groups({"category:read", "category:write"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"category:read", "category:write"})
     */
    private ?bool $enabled;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="uuid")
     */
    private ?string $uuid;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"category:read", "category:write"})
     */
    private ?string $description;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private ?int $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private ?int $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private ?int $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Category $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     * @Groups({"category:read", "category:write"})
     */
    private ?Category $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     * @Groups({"category:read"})
     */
    private ?Collection $children;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->children = new ArrayCollection;
        $this->parent = null;
        $this->enabled = false;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
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

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    public function setLvl(?int $lvl): Category
    {
        $this->lvl = $lvl;
        return $this;
    }

    public function getRoot(): ?Category
    {
        return $this->root;
    }

    public function setParent(?Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function getChildren(): ?Collection
    {
        return $this->children;
    }
}
