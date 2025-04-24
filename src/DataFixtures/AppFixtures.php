<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Commande;
use Faker\Factory;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
{
    $faker = Factory::create();

    $categories = ['Cookies', 'Brownies', 'Cheesecake'];
    $catEntities = [];

    foreach ($categories as $nom) {
        $cat = new Categorie();
        $cat->setNomCategorie($nom);
        $manager->persist($cat);
        $catEntities[] = $cat;
    }

    for ($i = 0; $i < 20; $i++) {
        $produit = new Produit();
        $produit->setNomProduit($faker->word());
        $produit->setPrix($faker->randomFloat(2, 2, 10));
        $produit->setCategorie($faker->randomElement($catEntities));
        $manager->persist($produit);
    }

    $manager->flush();
}}
