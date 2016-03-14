<?php

namespace AppBundle\Controller;

use AppBundle\Controller\APIRestBaseController;
use AppBundle\Entity\Posts;
use AppBundle\Form\PostsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends APIRestBaseController
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Posts')->findAll();

        return $this->apiResponse($posts)->groups(array('posts'))->response();
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = new Posts();
        //
        // $posts->setTitle('Titulo');
        // $posts->setSummary('Sumario');
        // $posts->setContent('Contenido');
        //
        // $em->persist($posts);
        // $em->flush();
        //
        // return new Response('Done!');
        $postsForm = $this->createForm(PostsType::class, $posts, array('csrf_protection' => false))->handleRequest($request);

        if($postsForm->isValid()){
            $em->persist($posts);
            $em->flush();

            return $this->apiResponse($posts)->groups(array('posts'))->response();
        }

        return $this->apiResponse($this->getErrorMessages($postsForm))->groups(array('posts'))->response();
    }

    public function oneByAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Posts')->findOneBy(array('id' => $id));

        return $this->apiResponse($posts)->groups(array('posts'))->response();
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Posts')->findOneBy(array('id' => $id));

        $em->remove($posts);
        $em->flush();

        return $this->apiResponse($posts)->groups(array('posts'))->response();
    }
}
