<?php

namespace App\Controller;
use App\Repository\ProduitRepository;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
final class CommandeController extends AbstractController{
    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/checkout', name: 'app_commande_checkout', methods: ['GET', 'POST'])]
public function checkout(Request $request, EntityManagerInterface $em, ProduitRepository $produitRepository): Response
{
    // 1. Récupérer le panier stocké en session (exemple)
    $session = $request->getSession();
    $panier = $session->get('panier', []); // tableau [produitId => quantité]

    if (empty($panier)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('app_produit_index');
    }

    // 2. Créer une nouvelle commande
    $commande = new Commande();

    // 3. Ajouter les produits à la commande
    foreach ($panier as $produitId => $quantite) {
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            continue;
        }
        for ($i = 0; $i < $quantite; $i++) {
            $commande->addProduit($produit);
        }
    }

    // 4. Sauvegarder la commande
    $em->persist($commande);
    $em->flush();

    // 5. Vider le panier
    $session->remove('panier');

    $this->addFlash('success', 'Commande passée avec succès !');

    return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
}


}
