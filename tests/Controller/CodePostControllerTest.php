<?php

namespace App\Tests\Controller;

use App\Entity\CodePost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CodePostControllerTest extends WebTestCase
{
    private static KernelBrowser $client;
    private static EntityManagerInterface $em;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$client = static::createClient();
        self::$em = self::$client->getContainer()->get(EntityManagerInterface::class);

        self::$em->beginTransaction();
        self::$em->createQuery('DELETE FROM App\Entity\CodePost')->execute();
        self::$em->createQuery('DELETE FROM App\Entity\User')->execute();

        $user = new User();
        $user->setEmail('testuser@example.com');
        $user->setPassword('password123');
        self::$em->persist($user);

        $post = new CodePost();
        $post->setTitle('Initial Post');
        $post->setDescription('Initial Description');
        $post->setContent('Initial Content');
        $post->setAuthor($user);
        self::$em->persist($post);

        self::$em->flush();
        self::$em->commit();
    }

    public function testCreatePost(): void
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $entityManager->persist($user);
        $entityManager->flush();

        self::$client->loginUser($user);

        self::$client->request('POST', '/api/posts', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'title' => 'Test Post',
            'description' => 'Test Description',
            'content' => 'Test Content'
        ]));

        $this->assertResponseIsSuccessful();

        $post = $entityManager->getRepository(CodePost::class)->findOneBy(['title' => 'Test Post']);
        $this->assertNotNull($post);
        $this->assertEquals($user->getId(), $post->getAuthor()->getId());
    }

    private function getEntityManager()
    {
        return self::getContainer()->get('doctrine')->getManager();
    }


    public function testEditPost()
    {
        $user = self::$em->getRepository(User::class)->find(1);
        $post = self::$em->getRepository(CodePost::class)->find(1);

        self::$client->loginUser($user);
        $crawler = self::$client->request('GET', '/user/posts/' . $post->getId() . '/edit');

        $this->assertResponseIsSuccessful();

        $formButton = $crawler->selectButton('Save changes');
        $this->assertNotNull($formButton, 'Кнопка Save changes не найдена');

        $form = $formButton->form([
            'code_post[title]' => 'Updated Title',
            'code_post[description]' => 'Updated Description',
            'code_post[content]' => 'Updated Content',
        ]);

        self::$client->submit($form);
        $this->assertResponseRedirects('/user/posts');

        self::$em->refresh($post);
        $this->assertEquals('Updated Title', $post->getTitle());
    }

    public function testDeletePost()
    {
        $user = self::$em->getRepository(User::class)->find(1);
        $post = self::$em->getRepository(CodePost::class)->find(1);

        self::$client->loginUser($user);
        self::$client->request('POST', '/user/posts/' . $post->getId() . '/delete');

        $this->assertResponseRedirects('/user/posts');

        $deletedPost = self::$em->getRepository(CodePost::class)->find($post->getId());
        $this->assertNull($deletedPost);
    }

    public function testAllPosts()
    {
        self::$client->request('GET', '/user/posts');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'All Code Posts');
    }

    public function testMyPosts()
    {
        $user = self::$em->getRepository(User::class)->find(1);
        self::$client->loginUser($user);
        self::$client->request('GET', '/user/posts/my-posts');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'My Posts');
    }

    public function testDateFormatting()
    {
        $post = new CodePost();
        $post->setTitle('Test Title');
        $post->setDescription('Test Description');
        $post->setContent('Test Content');

        $createdAt = $post->getCreatedAt();
        $this->assertInstanceOf(\DateTime::class, $createdAt);
        $this->assertEquals(date('Y-m-d'), $createdAt->format('Y-m-d'));
    }

    public function testSetCreatedAtOnCreate()
    {
        $post = new CodePost();
        $this->assertNotNull($post->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $post->getCreatedAt());
    }

    public function testEditPermission()
    {
        $user = self::$em->getRepository(User::class)->find(1);
        $post = self::$em->getRepository(CodePost::class)->find(2);

        self::$client->loginUser($user);
        self::$client->request('GET', '/user/posts/' . $post->getId() . '/edit');

        $this->assertResponseRedirects('/user/posts');
        $this->assertSelectorTextContains('div.alert', 'You cant edit this post');
    }

    public function testAuthorAssignment()
    {
        $user = self::$em->getRepository(User::class)->find(1);
        $post = new CodePost();
        $post->setTitle('Test Post');
        $post->setDescription('Test Description');
        $post->setContent('Test Content');
        $post->setAuthor($user);

        self::$em->persist($post);
        self::$em->flush();

        $this->assertEquals($user, $post->getAuthor());
    }

    public function testCreatePostWithoutAuthor()
    {
        $post = new CodePost();
        $post->setTitle('Test Post');
        $post->setDescription('Test Description');
        $post->setContent('Test Content');

        $this->expectException(\Doctrine\DBAL\Exception\NotNullConstraintViolationException::class);
        self::$em->persist($post);
        self::$em->flush();
    }
}
