<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $cart = $session->get('cart', []);
        
        $cartWithData = [];
        $total = 0;
        
        foreach($cart as $id => $quantity) {
            $produit = $produitRepository->find($id);
            
            $cartWithData[] = [
                'produit' => $produit,
                'quantity' => $quantity
            ];
            
            $total += $produit->getPrix() * $quantity;
        }
        
        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function add($id, SessionInterface $session, Request $request): Response
    {
        $cart = $session->get('cart', []);
        
        if(!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        $session->set('cart', $cart);
        
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function remove($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        
        if(!empty($cart[$id])) {
            unset($cart[$id]);
        }
        
        $session->set('cart', $cart);
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/increase/{id}', name: 'app_cart_increase')]
    public function increase($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        
        if(!empty($cart[$id])) {
            $cart[$id]++;
        }
        
        $session->set('cart', $cart);
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        
        if(!empty($cart[$id])) {
            if($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        
        $session->set('cart', $cart);
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/clear', name: 'app_cart_clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('cart');
        
        return $this->redirectToRoute('app_cart_index');
    }
}
