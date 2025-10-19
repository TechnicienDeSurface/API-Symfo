<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EditorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $editors = [
            ['name' => 'Electronic Arts', 'country' => 'USA'],
            ['name' => 'Ubisoft', 'country' => 'France'],
            ['name' => 'Nintendo', 'country' => 'Japan'],
            ['name' => 'Sony Interactive', 'country' => 'Japan'],
            ['name' => 'Microsoft Gaming', 'country' => 'USA'],
            ['name' => 'Activision Blizzard', 'country' => 'USA'],
            ['name' => 'Take-Two Interactive', 'country' => 'USA'],
            ['name' => 'Bandai Namco', 'country' => 'Japan'],
            ['name' => 'Square Enix', 'country' => 'Japan'],
            ['name' => 'Capcom', 'country' => 'Japan'],
            ['name' => 'Sega', 'country' => 'Japan'],
            ['name' => 'Konami', 'country' => 'Japan'],
            ['name' => 'CD Projekt', 'country' => 'Poland'],
            ['name' => 'Riot Games', 'country' => 'USA'],
            ['name' => 'Valve Corporation', 'country' => 'USA'],
            ['name' => 'Epic Games', 'country' => 'USA'],
            ['name' => 'Bethesda Softworks', 'country' => 'USA'],
            ['name' => 'Rockstar Games', 'country' => 'USA'],
            ['name' => 'Paradox Interactive', 'country' => 'Sweden'],
            ['name' => 'Focus Entertainment', 'country' => 'France'],
            ['name' => 'Koch Media', 'country' => 'Germany'],
            ['name' => 'Embracer Group', 'country' => 'Sweden'],
            ['name' => 'Devolver Digital', 'country' => 'USA'],
            ['name' => 'Warner Bros Games', 'country' => 'USA'],
            ['name' => 'Tencent Games', 'country' => 'China'],
        ];

        foreach ($editors as $editorData) {
            $editor = new Editor();
            $editor->setName($editorData['name']);
            $editor->setCountry($editorData['country']);
            $this->addReference($editorData['name'], $editor);
            
            $manager->persist($editor);
        }

        $manager->flush();
    }
}
