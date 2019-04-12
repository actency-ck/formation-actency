<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskOld;
use App\Form\TaskType;
use App\Repository\UserRepository;
use App\Services\SayHello;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\{Request, Response, Session\SessionInterface};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DemoController extends AbstractController
{
    /**
     * @Route("/details/{name}", name="details")
     */
    public function index($name, SessionInterface $session)
    {
        $session->set('name_in_session', $name);

        return $this->render('demo/index.html.twig', [
            'name'=>$name
        ]);
    }

    /**
     * @Route("/list/{_locale}", name="list", requirements={"_locale:en"},
     *     defaults={"_locale":"en"})
     */
    public function listTask(Request $request, UserRepository $repository)
    {

        return $this->render('demo/list.html.twig', [
            'tasks' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $manager)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task, ['classFromController' => 'Symfony is Great']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $file = $task->getFile();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('files_directory'),
                    $fileName
                );

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $task->setImage($fileName);

            $user = $this->getUser();
            $task->setUser($user);
            
            $manager->persist($task);
            $manager->flush();
             $this->addFlash('success', sprintf("Task %s Created!", $task->getName()));
             return $this->redirectToRoute('show', [
                 'id' => $task->getId(),
             ]);
        }

        return $this->render('demo/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Task $task, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(TaskType::class, $task, ['classFromController' => 'Symfony is Great']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $file = $task->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('files_directory'),
                $fileName
            );
            $task->setImage($fileName);

            $manager->flush();
            $this->addFlash('success', sprintf("Task %s Created!", $task->getName()));
            return $this->redirectToRoute('show', [
                'id' => $task->getId(),
            ]);
        }
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Task $task)
    {
        return $this->render('demo/show.html.twig', [
            'task' => $task
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Task $task, EntityManagerInterface $manager)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $name = $task->getName();
        $manager->remove($task);
        $manager->flush();

        $this->addFlash('danger', sprintf("Task %s Deleted!", $name));

        return $this->redirectToRoute('list');
    }


    /**
     * @Route("/mail", name="mail")
     */
    public function mail(\Swift_Mailer $mailer)
    {
        $swift = new \Swift_Message('Hello From Symfony');
        $message = $swift
            ->setFrom('noreply@monsite.com')
            ->setTo('smaine.milianni@gmail.com')
            ->setBody(
                "<h1>Hello from Symfony mailer</h1>",
                'text/html'
            );

        $mailer->send($message);

        return new Response("<body>Email sent</body>");
    }
}
