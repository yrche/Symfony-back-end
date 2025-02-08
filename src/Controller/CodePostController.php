<?php

namespace App\Controller;

use App\Entity\CodePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CodePostRepository;

#[Route("/user/posts")]
class CodePostController extends AbstractController
{
    private $security;
    private $codePostRepository;

    public function __construct(Security $security, CodePostRepository $codePostRepository)
    {
        $this->security = $security;
        $this->codePostRepository = $codePostRepository;
    }

    #[Route("/", name: "home", methods: ["GET", "POST"])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $query = $this->codePostRepository->getAllPostsQuery()
            ->orderBy('c.createdAt', 'DESC');

        if ($request->query->get('title')) {
            $query = $this->codePostRepository->getPostsByTitleQuery($request->query->get('title'));
        }

        $posts = $query->getQuery()->getResult();

        return $this->render("code_post/index.html.twig", ["posts" => $posts]);
    }

    #[Route('/my-posts', name: 'app_my_posts')]
    public function myPosts(EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $query = $this->codePostRepository->getPostsByUserQuery($user);

        $posts = $query->getQuery()->getResult();

        return $this->render('code_post/my_posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route("/{id}/edit", name: "edit_post", methods: ["GET", "POST"])]
    public function edit(Request $request, CodePost $post, EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();

        if ($post->getAuthor() !== $user) {
            $this->addFlash('error', 'Вы не можете редактировать этот пост.');
            return $this->redirectToRoute('home');
        }

        if ($request->isMethod('POST')) {
            $post->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description'))
                ->setContent($request->request->get('content'));

            $em->flush();
            $this->addFlash('success', 'Пост успешно обновлен!');
            return $this->redirectToRoute('home');
        }

        return $this->render("code_post/edit.html.twig", ["post" => $post]);
    }

    #[Route("/{id}/delete", name: "delete_post", methods: ["POST"])]
    public function delete(CodePost $post, EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();

        if ($post->getAuthor() !== $user) {
            $this->addFlash('error', 'Вы не можете удалить этот пост.');
            return $this->redirectToRoute('home');
        }

        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Пост успешно удален!');

        return $this->redirectToRoute('home');
    }

    #[Route("/create", name: "create_post", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $post = new CodePost();
            $post->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description'))
                ->setContent($request->request->get('content'))
                ->setAuthor($user);

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Новый пост успешно создан!');
            return $this->redirectToRoute('home');
        }

        return $this->render('code_post/create.html.twig');
    }
}
