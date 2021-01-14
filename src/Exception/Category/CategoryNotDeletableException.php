<?php

namespace App\Exception\Category;

use App\Entity\Blog\Category;

class CategoryNotDeletableException extends \Exception
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
        parent::__construct(
            "This category can't be deleted because it has at least one child.",
            0,
            null
        );
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
