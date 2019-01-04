<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $catId = $request->query->get('catId');

        if ($catId) {
            $products = $em->getRepository('AppBundle:Product')->findBy(['category' => $catId ]);
        } else {
            $products = $em->getRepository('AppBundle:Product')->findAll();
        }

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $authors = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();

        if ($form->isSubmitted()) {
            $currentUser = $this->getUser();
            $product->setUser($currentUser);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig',
            /*'product' => $product,*/
            /*'categories' => $categories,*/
            ['form' => $form->createView(),
                'categories' => $categories,
                'authors' => $authors]);

    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}/show", name="product_show")
     * @Method("GET")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Product $product)
    {

        return $this->render('product/show.html.twig', array(
            'product' => $product
        ));
    }

     /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $authors = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();

        if ($product === null){
            return $this->redirectToRoute("product_index");
        }

        /**@var User $currentUser*/
        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($product) && !$currentUser->isAdmin()){
            return $this->redirectToRoute("product_index");
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            'authors' => $authors,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/delete/{id}", name="product_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $authors = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();


        /**@var User $currentUser*/
        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($product) && !$currentUser->isAdmin()){
            return $this->redirectToRoute("product_index");
        }

        if ($product === null){
            return $this->redirectToRoute("product_index");
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            return $this->redirectToRoute("product_index");
        }
        return $this->render('product/delete.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            'authors' => $authors,
            'form' => $form->createView()
        ));
    }



}
