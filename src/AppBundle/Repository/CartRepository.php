<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use \Doctrine\Common\Util\Debug;

class CartRepository
{
    private $session;

    public function __construct(SessionInterface $session) 
    {
        $this->session = $session;
    }

    public function get(Product $product)
    {
        return $this->session->get($product->getId());
    }

    public function set(Product $product)
    {
        $existingProduct = $this->get($product);

        if (!$this->session->has($product->getId())) {
            $this->session->set($product->getId(), ['product' => $product, 'count' => 1]);
        }
        else {
            $count = intval($existingProduct['count']) + 1;
            $this->session->set($product->getId(), ['product' => $product, 'count' => $count]);
        }
    }

    public function remove(Product $product)
    {
        $this->session->remove($product->getId());
    }

    public function getTotal()
    {
        return array_sum(array_map(
            [$this, 'getTotalCartProductPrice'],
            $this->all()
        ));
    }
    
    public function all()
    {
        $products = [];
        foreach($this->session->all() as $sessionItem) {
            if (!empty($sessionItem['product'])) {
                $products[] = $sessionItem;
            }
        }
        return $products;
    }

    private function getTotalCartProductPrice(array $cartProduct)
    {
        return $cartProduct['product']->getPrice() * $cartProduct['count'];
    }
}
