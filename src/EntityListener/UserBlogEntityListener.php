<?php

namespace App\EntityListener;

use App\Entity\Blog;
use Doctrine\Common\EventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserBlogEntityListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Blog $blog, EventArgs $event)
    {
        $blog->computeSlug($this->slugger);
    }

    public function preUpdate(Blog $blog, EventArgs $event){
        $blog->computeSlug($this->slugger);
    }
}