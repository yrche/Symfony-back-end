<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TagRepository $tagRepository,
    ) {
    }
    public function transform($tag): string
    {
        if (null === $tag) {
            return '';
        }

        return $tag->getId();
    }

    public function reverseTransform($tag): ?Issue
    {
        if (!$tag) {
            return null;
        }

        $issue = $this->tagRepository
            ->find($tag)
        ;

        return $tag;
    }
}