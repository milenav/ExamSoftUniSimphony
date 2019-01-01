<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends Controller
{
    /**
     * @Route("/author", name="product_author")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('product_new');
        }
        return $this->render('product/author.html.twig', array(
            'form' =>$form->createView()
        ));
    }
}
