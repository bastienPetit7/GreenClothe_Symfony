<?php

namespace App\Controller\Account;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 
    }

    /**
     * @Route("/account/order", name="account_order")
     */
    public function index(): Response
    {

        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser()); 


        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }
}
