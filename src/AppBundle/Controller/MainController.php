<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Topic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    
    /**
     * @Route("/home", name="homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function homeAction(Request $request)
    {
        
        $latest_topics = $this->getDoctrine()
                        ->getRepository(Topic::class)
                        ->findByCreatedAtOrder('ASC');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $latest_topics,
            $request->query->getInt('page', 1),
            2/*limit per page*/
        );

        return $this->render('inside/home.html.twig', [
            'topics' => $pagination,
        ]);
    }
    
    /**
     * @Route("/top", name="top_rated")
     * @Security("has_role('ROLE_USER')")
     */
    public function topRatedAction(Request $request)
    {
        
        $top_topics = $this->getDoctrine()
                        ->getRepository(Topic::class)
                        ->getTopicsOrderedByNumberOfUpvotes();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $top_topics,
            $request->query->getInt('page', 1),
            2/*limit per page*/
        );

        return $this->render('inside/home.html.twig', [
            'topics' => $pagination,
        ]);
    }
}
