<?php

namespace App\Services;

use App\Repository\TagRepository;

class TagServices {

    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function tags()
    {
        return $this->tagRepository->findAll();

    }

}