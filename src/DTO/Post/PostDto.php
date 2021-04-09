<?php


namespace App\DTO\Post;


use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

class PostDto
{
    public string $title;
    public string $excerpt;
    public ?DateTimeInterface $publishedAt;
    public ?string $link;
    public ?string $image;
    public Collection $categories;
}
