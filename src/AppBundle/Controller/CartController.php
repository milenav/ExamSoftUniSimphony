<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


use AppBundle\Repository\CartRepository;

/**
 * Class CartController
 * @package AppBundle\Controller
 * @Route("/cart")
 */
class CartController extends Controller
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @Route("/add/{id}", name="cart_add")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);

        if (empty($product))
            throw new NotFoundHttpException('Product not found.');

        $this->cartRepository->set($product);
        return $this->redirectToRoute('user_cart');
    }


    /**
     * @Route("/remove/{id}", name="cart_remove")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (empty($product))
            throw new NotFoundHttpException('Product not found or already removed.');
        $this->cartRepository->remove($product);
        return $this->redirectToRoute('user_cart');
    }



}