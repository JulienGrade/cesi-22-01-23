<?php

namespace App\EntityListener;

use App\Entity\Articles;
use Doctrine\Common\EventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticlesEntityListener
{
    private SluggerInterface $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Articles $articles, EventArgs $event): void
    {
        $articles->computeSlug($this->slugger);
    }

    public function preUpdate(Articles $articles, EventArgs $event): void
    {
        $articles->computeSlug($this->slugger);
    }
}