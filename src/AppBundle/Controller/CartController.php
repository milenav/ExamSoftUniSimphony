<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use AppBundle\Repository\CartRepository;

class CartController extends Controller
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
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


}