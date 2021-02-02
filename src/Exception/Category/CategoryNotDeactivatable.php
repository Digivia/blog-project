<?php

namespace App\Exception\Category;

use App\Entity\Blog\Category;

class CategoryNotDeactivatable extends \Exception
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
        parent::__construct(
            "This category can't be disabled because it has at least one enabled child.",
            0,
            null
        );
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
