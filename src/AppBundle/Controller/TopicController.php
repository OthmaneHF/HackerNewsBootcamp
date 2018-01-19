<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Topic;
use AppBundle\Entity\Upvote;
use AppBundle\Form\CommentFormType;
use AppBundle\Form\TopicFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicController extends Controller
{
    /**
     * @Route("/topic/new", name="new_topic")
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
    	$newTopicForm = $this->createForm(TopicFormType::class);

    	$newTopicForm->handleRequest($request);

    	if($newTopicForm->isSubmitted() && $newTopicForm->isValid())
    	{
    		$newTopic = $newTopicForm->getData();
    		$newTopic->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTopic);
            $em->flush();

            $this->addFlash("success","Your topic has ben successfully created.");

            return $this->redirectToRoute('homepage');
    	}
    	return $this->render('topic/new.html.twig',[
    		'newTopicForm' => $newTopicForm->createView()
    	]);
    }


    /**
     * @Route("/topic/{id}", name="view_topic")
     * @Security("has_role('ROLE_USER')")
     */
    public function viewAction($id)
    {
        
        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->find($id);

        if(!$topic)
        {
            $this->addFlash("danger","This topic does not exist");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('topic/view.html.twig',[
            'topic' => $topic
        ]);
    }

    /**
     * @Route("/topic/upvote/{id}")
     * @Security("has_role('ROLE_USER')")
     */
    public function upvoteAction($id, Request $request)
    {
        
        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->find($id);

        if(!$topic)
        {
            $this->addFlash("danger","This topic does not exist");

            return $this->redirectToRoute('homepage');
        }

        $existingUpvote =  $this->getDoctrine()
            ->getRepository(Upvote::class)
            ->findOneBy(
                array('user' => $this->getUser()->getId(), 'topic' => $id)
            );

        if(!$existingUpvote)
        {

            $newUpvote = new Upvote;
            $newUpvote->setUser($this->getUser());
            $newUpvote->setTopic($topic);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newUpvote);
            $em->flush();

            $this->addFlash("success","Your upvote has been registered.");

            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash("danger","Your have already upvoted this topic.");

        return $this->redirect($request->headers->get('referer'));

    }
  /**
     * @Route("/topic/{id}/comment/", name="new_comment")
     * @Security("has_role('ROLE_USER')")
     */
    public function commentAction($id, Request $request)
    {
        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->find($id);

        if(!$topic)
        {
            $this->addFlash("danger","This topic does not exist");

            return $this->redirectToRoute('homepage');
        }

        $comment = new Comment;
        $comment->setTopic($topic);

        $newCommentForm = $this->createForm(CommentFormType::class, $comment);

        $newCommentForm->handleRequest($request);

        if($newCommentForm->isSubmitted() && $newCommentForm->isValid())
        {
            $comment = $newCommentForm->getData();
            $comment->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash("success","Your commented has ben successfully added.");

            return $this->redirectToRoute('view_topic',['id' => $id]);
        }
        return $this->render('topic/comment.html.twig',[
            'newCommentForm' => $newCommentForm->createView()
        ]);
    }

}
