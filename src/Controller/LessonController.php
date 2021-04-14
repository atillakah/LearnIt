<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Comment;
use App\Form\CommentType;
use DateTime;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use Doctrine\Common\Collections\Expr\Value;
use PhpParser\Builder\Class_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lesson")
 */
class LessonController extends AbstractController
{
    /**
     * @Route("/", name="lesson_index", methods={"GET"})
     */
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessonRepository->findBy([],['id' => 'DESC']),
        ]);
    }






    /**
     * @Route("/mylessons", name="lesson_me", methods={"GET"})
     */
    public function mylessons(LessonRepository $lessonRepository): Response
    {
       $value = $this->getUser()->getId(); // je met dans $value l'id de luser connected
        return $this->render('lesson/mylessons.html.twig', [
            'lessons' => $lessonRepository->mylessons($value),
        ]);
    }


    /**
     * @Route("/category/{name}", name="bytag", methods={"GET"})
     */
    public function lessonbytag(Request $request, LessonRepository $lessonRepository): Response
    {
       $value = $request->get('name'); // cette valeur doit etre variable 
        return $this->render('lesson/indexbytag.html.twig', [
            'lessons' => $lessonRepository->findByTag($value),
            'tagg'=>$value,
        ]); // il faut changer le nom de la vue et de la route 
    }













    /**
     * @Route("/new", name="lesson_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);


       //  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setCreatedAt(new DateTime());
            $user = $this->getUser();
            $lesson->setUser($user); // je set le user;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirectToRoute('lesson_index');
        }

        return $this->render('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lesson_show", methods={"GET", "POST"})
     */
    public function show(Lesson $lesson, Request $request): Response
    {
        
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $user = $this->getUser();
            $rget = $request->get('id');
            $comment->setUser($user); // je set le user;
            $comment->setLesson($lesson);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('lesson_show', ['id' => $rget]);
            }

            $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy([
                'lesson' => $lesson,
            ],['createdAt' => 'asc']

        );


        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lesson_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lesson $lesson): Response
    {


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setUpdatedAt(new DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lesson_index');
        }

        return $this->render('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lesson_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lesson $lesson): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lesson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lesson_index');
    }
}
