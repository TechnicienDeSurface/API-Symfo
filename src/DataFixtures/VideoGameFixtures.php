<?php

namespace App\DataFixtures;

use App\Entity\VideoGame;
use App\Entity\Editor;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VideoGameFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $videoGames = [
            ['title' => 'The Legend of Zelda: Breath of the Wild', 'releaseDate' => '2017-03-03', 'description' => 'Open-world action-adventure game set in Hyrule', 'coverImage' => 'zelda.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Nintendo', Editor::class)],
            ['title' => 'Red Dead Redemption 2', 'releaseDate' => '2018-10-26', 'description' => 'Epic tale of life in America at the dawn of the modern age', 'coverImage' => 'rdr2.jpg', 'category' => $this->getReference('Adventure', Category::class), 'editor' => $this->getReference('Rockstar Games', Editor::class)],
            ['title' => 'The Witcher 3: Wild Hunt', 'releaseDate' => '2015-05-19', 'description' => 'Story-driven open world RPG set in a visually stunning fantasy universe', 'coverImage' => 'witcher3.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('CD Projekt', Editor::class)],
            ['title' => 'God of War', 'releaseDate' => '2018-04-20', 'description' => 'Kratos and his son Atreus embark on a journey through Norse mythology', 'coverImage' => 'godofwar.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Elden Ring', 'releaseDate' => '2022-02-25', 'description' => 'Action RPG developed by FromSoftware and published by Bandai Namco', 'coverImage' => 'eldenring.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Bandai Namco', Editor::class)],
            ['title' => 'Cyberpunk 2077', 'releaseDate' => '2020-12-10', 'description' => 'Open-world RPG set in Night City, a megalopolis obsessed with power', 'coverImage' => 'cyberpunk2077.jpg', 'category' => $this->getReference('Adventure', Category::class), 'editor' => $this->getReference('CD Projekt', Editor::class)],
            ['title' => 'Minecraft', 'releaseDate' => '2011-11-18', 'description' => 'Sandbox game where players can build and explore infinite worlds', 'coverImage' => 'minecraft.jpg', 'category' => $this->getReference('Sandbox', Category::class), 'editor' => $this->getReference('Microsoft Gaming', Editor::class)],
            ['title' => 'Grand Theft Auto V', 'releaseDate' => '2013-09-17', 'description' => 'Action-adventure game set in the fictional state of San Andreas', 'coverImage' => 'gtav.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Rockstar Games', Editor::class)],
            ['title' => 'Dark Souls III', 'releaseDate' => '2016-04-12', 'description' => 'Action RPG known for its challenging gameplay and dark atmosphere', 'coverImage' => 'darksouls3.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Bandai Namco', Editor::class)],
            ['title' => 'Horizon Zero Dawn', 'releaseDate' => '2017-02-28', 'description' => 'Post-apocalyptic action RPG featuring robotic creatures', 'coverImage' => 'horizon.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Super Mario Odyssey', 'releaseDate' => '2017-10-27', 'description' => '3D platformer where Mario travels across various kingdoms', 'coverImage' => 'marioodyssey.jpg', 'category' => $this->getReference('Platformer', Category::class), 'editor' => $this->getReference('Nintendo', Editor::class)],
            ['title' => 'Bloodborne', 'releaseDate' => '2015-03-24', 'description' => 'Action RPG set in the gothic city of Yharnam', 'coverImage' => 'bloodborne.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Spider-Man', 'releaseDate' => '2018-09-07', 'description' => 'Open-world action game featuring the iconic Marvel superhero', 'coverImage' => 'spiderman.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Final Fantasy VII Remake', 'releaseDate' => '2020-04-10', 'description' => 'Reimagining of the 1997 classic JRPG', 'coverImage' => 'ff7remake.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Square Enix', Editor::class)],
            ['title' => 'Sekiro: Shadows Die Twice', 'releaseDate' => '2019-03-22', 'description' => 'Action-adventure game set in Sengoku-era Japan', 'coverImage' => 'sekiro.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Bandai Namco', Editor::class)],
            ['title' => 'Ghost of Tsushima', 'releaseDate' => '2020-07-17', 'description' => 'Action-adventure game set in feudal Japan during Mongol invasion', 'coverImage' => 'ghost.jpg', 'category' => $this->getReference('Adventure', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Death Stranding', 'releaseDate' => '2019-11-08', 'description' => 'Action game with unique gameplay mechanics focused on connection', 'coverImage' => 'deathstranding.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Hades', 'releaseDate' => '2020-09-17', 'description' => 'Rogue-like dungeon crawler inspired by Greek mythology', 'coverImage' => 'hades.jpg', 'category' => $this->getReference('Rogue-like', Category::class), 'editor' => $this->getReference('Devolver Digital', Editor::class)],
            ['title' => 'The Last of Us Part II', 'releaseDate' => '2020-06-19', 'description' => 'Action-adventure survival horror game set in post-apocalyptic America', 'coverImage' => 'tlou2.jpg', 'category' => $this->getReference('Horror', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Assassin\'s Creed Valhalla', 'releaseDate' => '2020-11-10', 'description' => 'Action RPG set during the Viking invasion of Britain', 'coverImage' => 'valhalla.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Ubisoft', Editor::class)],
            ['title' => 'Control', 'releaseDate' => '2019-08-27', 'description' => 'Action-adventure game with supernatural elements', 'coverImage' => 'control.jpg', 'category' => $this->getReference('Action', Category::class), 'editor' => $this->getReference('Focus Entertainment', Editor::class)],
            ['title' => 'Resident Evil Village', 'releaseDate' => '2021-05-07', 'description' => 'Survival horror game set in a mysterious European village', 'coverImage' => 'revillage.jpg', 'category' => $this->getReference('Horror', Category::class), 'editor' => $this->getReference('Capcom', Editor::class)],
            ['title' => 'Forza Horizon 5', 'releaseDate' => '2021-11-09', 'description' => 'Open-world racing game set in Mexico', 'coverImage' => 'forza5.jpg', 'category' => $this->getReference('Racing', Category::class), 'editor' => $this->getReference('Microsoft Gaming', Editor::class)],
            ['title' => 'Demon\'s Souls', 'releaseDate' => '2020-11-12', 'description' => 'Remake of the classic action RPG for PlayStation 5', 'coverImage' => 'demonsouls.jpg', 'category' => $this->getReference('Role-Playing (RPG)', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
            ['title' => 'Returnal', 'releaseDate' => '2021-04-30', 'description' => 'Rogue-like third-person shooter with psychological horror elements', 'coverImage' => 'returnal.jpg', 'category' => $this->getReference('Rogue-like', Category::class), 'editor' => $this->getReference('Sony Interactive', Editor::class)],
        ];

        foreach ($videoGames as $gameData) {
            $game = new VideoGame();
            $game->setTitle($gameData['title']);
            $game->setReleaseDate(new \DateTime($gameData['releaseDate']));
            $game->setDescription($gameData['description']);
            $game->setCoverImage($gameData['coverImage']);
            $game->setCategory($gameData['category']);
            $game->setEditor($gameData['editor']);

            $manager->persist($game);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            EditorFixtures::class,
        ];
    }
}