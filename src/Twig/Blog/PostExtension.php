<?php

declare(strict_types=1);

namespace App\Twig\Blog;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class PostExtension
 * @package App\Twig\Blog
 */
class PostExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getPost', [$this, 'getPost']),
        ];
    }
}
