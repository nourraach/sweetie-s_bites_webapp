<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\Repository\CommandeRepository;

#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(
        ProduitRepository $produitRepository,
        UserRepository $userRepository,
        CommandeRepository $commandeRepository
    ): Response {
        $produits = $produitRepository->findAll();
        $users = $userRepository->findAll();
        $commandes = $commandeRepository->findAll();

        $userCount = count($users);
        $productCount = count($produits);
        $orderCount = count($commandes);

        $totalSales = 0;
        foreach ($commandes as $commande) {
            foreach ($commande->getProduits() as $produit) {
                $totalSales += $produit->getPrix();
            }
        }

        // Get 5 most recent orders (you can sort and limit in your repo for better performance)
        $recentOrders = array_slice($commandes, 0, 5);

        // Get 5 popular products (replace with your own logic if needed)
        $popularProducts = $produitRepository->findBy([], [], 5);

        return $this->render('admin_dashboard/dashboard.html.twig', [
            'user_count' => $userCount,
            'product_count' => $productCount,
            'order_count' => $orderCount,
            'total_sales' => $totalSales,
            'recent_orders' => $recentOrders,
            'popular_products' => $popularProducts,
        ]);
    }
}
