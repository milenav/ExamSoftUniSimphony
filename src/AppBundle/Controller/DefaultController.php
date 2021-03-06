<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="gallery_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $catId = $request->query->get('catId');

        if ($catId) {
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(['category' => $catId ]);
        } else {
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        }

        $products = array_slice($products, 0, min(4, count($products)));

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'products' => $products
        ]);
    }

}
