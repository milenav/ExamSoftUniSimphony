<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Repository\CartRepository;

class UserController extends Controller
{
    
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $role = $this
                ->getDoctrine()
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_USER']);

            $user->addRole($role);

            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');
        }
        return $this->render('user/register.html.twig');
    }

    /**
     * @Route("/cart", name="user_cart")
     */
    public function profile(){
        $userId = $this->getUser()->getId();
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        $products = $this
            ->cartRepository
            ->all();

        return $this->render("user/cart.html.twig",
            ['products' => $products,
                'user' => $user]);
    }

    /**
     * @Route("/contact", name="user_contact")
     */
    public function contact()
    {
        return $this->render("user/contact.html.twig");
    }
}
