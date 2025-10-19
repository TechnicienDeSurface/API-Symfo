<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Action',
            'Adventure',
            'Role-Playing (RPG)',
            'Simulation',
            'Strategy',
            'Sports',
            'Racing',
            'Fighting',
            'Shooter',
            'Platformer',
            'Puzzle',
            'Horror',
            'Survival',
            'Open World',
            'MMORPG',
            'Battle Royale',
            'Stealth',
            'Sandbox',
            'Visual Novel',
            'Card Game',
            'Party Game',
            'Music/Rhythm',
            'Educational',
            'Indie',
            'Casual',
            'Rogue-like'
        ];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $this->addReference($categoryName, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}