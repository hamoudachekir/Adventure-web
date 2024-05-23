<?php

namespace App\Controller;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Entity\Avis;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Form\AvisType;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $blogs=$blogRepository->findAll();
        $blogs = $paginator->paginate($blogs, $request->query->getInt('page', 1),3);
        return $this->render('blog/blog.html.twig', [
            'blogs' => $blogs,
        ]);
    }
    #[Route('/admin/', name: 'admin_blog_index')]
    public function indexAdmin(Request $request, BlogRepository $blogRepository, EntityManagerInterface $entityManager): Response
    {

        $blog = new Blog();

        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blog);
            $entityManager->flush();
            return $this->redirectToRoute('admin_blog_index');
        }
        return $this->render('admin_dashboard/blog_admin.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/{id}', name: 'admin_blog_show', methods: ['GET', 'POST'])]
    public function showAdmin(Blog $blog,Request $request,EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_dashboard/blog_show.html.twig', [
            'blog' => $blog,
        ]);
    }

    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image= $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $blog->setImage($newFilename);
            }

            $blog->setCreatedAt();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show')]
    public function show(Blog $blog,Request $request,EntityManagerInterface $entityManager, UserRepository $urep, $id): Response
    {
        // Comments form
        $comment = new Avis();
        $formC = $this->createForm(AvisType::class, $comment);
        $formC->handleRequest($request);
        $comment->setBlog($blog);

        //temp
        //$comment->setClient($urep->findAll()[0]);



        if ($formC->isSubmitted() && $formC->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            /*$comment = new Comment();
            $formC = $this->createForm(CommentType::class, $comment);
            $this->addFlash('success', 'commentaire ajouté');*/
            return $this->redirectToRoute('app_blog_show', ['id' => $id]);

        }
        return $this->render('blog/blog_details.html.twig', [
            'blog' => $blog,
            'form' => $formC->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->renderForm('admin_dashboard/blog_edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_blog_delete')]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($blog);
        $entityManager->flush();

        return $this->redirectToRoute('admin_blog_index');
    }


    private function uploadImages(Blog $blog, array $image): void
    {
        $destination = $this->getParameter('kernel.project_dir') . '/public/assets/uploads/blogs';
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $slugger = new AsciiSlugger();
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
            $image->move($destination, $newFilename);
            $blog->setImage($newFilename);
    }
}
