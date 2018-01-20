<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Topic;
use AppBundle\Entity\Upvote;
use AppBundle\Form\CommentFormType;
use AppBundle\Form\TopicFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
            $newTopic =  $this->getDoctrine()
            ->getRepository(Topic::class)
            ->createNewTopicFromForm($this->getUser(),$newTopicForm->getData());

            $this->addFlash("success","Your topic has ben successfully created.");

            return $this->redirectToRoute('homepage');
    	}

    	return $this->render('topic/new.html.twig',[
    		'newTopicForm' => $newTopicForm->createView()
    	]);
    }


    /**
     * @Route("/topic/{id}", name="view_topic")
     * @ParamConverter("topic", class="AppBundle:Topic")
     * @Security("has_role('ROLE_USER')")
     */
    public function viewAction(Topic $topic)
    {
        return $this->render('topic/view.html.twig',[
            'topic' => $topic
        ]);
    }

    /**
     * @Route("/topic/upvote/{id}")
     * @ParamConverter("topic", class="AppBundle:Topic")
     * @Security("has_role('ROLE_USER')")
     */
    public function upvoteAction(Topic $topic, Request $request)
    {

        $addUpvote =  $this->getDoctrine()
            ->getRepository(Upvote::class)
            ->AddIfNotExists($topic, $this->getUser());

        if(!$addUpvote)
        {
            $this->addFlash("danger","Your have already upvoted this topic.");

            return $this->redirect($request->headers->get('referer'));

        }

        $this->addFlash("success","Your upvote has been registered.");

        return $this->redirect($request->headers->get('referer'));
    }


  /**
     * @Route("/topic/{id}/comment/", name="new_comment")
     * @ParamConverter("topic", class="AppBundle:Topic")
     * @Security("has_role('ROLE_USER')")
     */
    public function commentAction(Topic $topic, Request $request)
    {
        $comment = new Comment;
        $comment->setTopic($topic);

        $newCommentForm = $this->createForm(CommentFormType::class, $comment);

        $newCommentForm->handleRequest($request);

        if($newCommentForm->isSubmitted() && $newCommentForm->isValid())
        {
            $newComment =  $this->getDoctrine()
            ->getRepository(Comment::class)
            ->createNewCommentFromForm($this->getUser(),$newCommentForm->getData());

            $this->addFlash("success","Your commented has ben successfully added.");

            return $this->redirectToRoute('view_topic',['id' => $topic->getId()]);
        }
        return $this->render('topic/comment.html.twig',[
            'newCommentForm' => $newCommentForm->createView()
        ]);
    }

}
