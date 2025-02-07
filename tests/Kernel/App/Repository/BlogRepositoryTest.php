<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\BlogRepository;

class BlogRepositoryTest extends KernelTestCase
{
    public function testFindById(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $blogRepository = $container->get(BlogRepository::class);

        $blog = $blogRepository->find(1);

        $this->assertNotNull($blog);
        $this->assertSame(1, $blog->getId());
    }
}
