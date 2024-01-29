<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Book;
use Utils;

class DataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Génération des auteurs
        $auteurs = [];
        $AUTHORS_COUNT = 50;
        $uuids = Utils::generateUniqueUUIDs($AUTHORS_COUNT);

        for ($i = 0; $i < 50; $i++) {
            $isDead = $faker->boolean(75);
            $birthDate = $faker->dateTimeBetween("-200 years", "now");
            
            // Calculate the maximum possible age based on the birthDate
            $maxAge = new DateTimeImmutable();
            $maxAge->sub($birthDate->diff(new DateTimeImmutable()));
            
            // Convert DateTimeImmutable objects to strings
            $birthDateString = $birthDate->format('Y-m-d H:i:s');
            $maxAgeString = $maxAge->format('Y-m-d H:i:s');
            
            // Generate deathDate after birthDate
            $deathDate = !$isDead ? null : $faker->dateTimeBetween($birthDateString, $maxAgeString);
                        
            $auteur = (new Author())->setId($uuids[$i])
                                    ->setFirstname($faker->firstName())
                                    ->setLastname($faker->lastName())                                     
                                    ->setPhotoLink("https://picsum.photos/360/360?image=".($i+1))
                                    ->setBirthDate($birthDate)
                                    ->setDeathDate($deathDate)
                                    ->setDescription($faker->paragraph())
                                    ->setNationality($faker->country());
                                    ;

            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        $manager->flush();

        # Create Book Categories
        $categories_name = ["Littérature", "Essais", "Policier", "Science-fiction", "Fantastique", "Jeunesse", "BD", "Mangas", "Cuisine", "Voyage", "Histoire", "Biographie", "Autre"];
        $CATEGORY_COUNT = count($categories_name);
        $uuids = Utils::generateUniqueUUIDs($CATEGORY_COUNT);

        $categories = [];

        for ($i = 0; $i < $CATEGORY_COUNT; $i++) {
            $category = (new Category())->setName($categories_name[$i])
            ->setId($uuids[$i])
            ->setDescription($faker->paragraph(2));

            $categories[] = $category;
            $manager->persist($category);
        }

        $manager->flush();

        $langues = ["Français", "Anglais", "Allemand", "Espagnol", "Italien", "Portugais", "Chinois", "Japonais", "Russe", "Arabe", "Autre"];

        # Create Books
        $BOOKS_COUNT = 100;
        $uuids = Utils::generateUniqueUUIDs($BOOKS_COUNT);
        $livres = [];

 
        for( $i = 0; $i < 100; $i++ ) {
            $livre = (new Book())->setId($uuids[$i])
            ->setCoverLink("https://picsum.photos/360/360?image=".($i+1))
            ->setReleaseDate($faker->dateTime())
            ->setTitle($faker->sentence(3))
            ->setSummary($faker->paragraph(2))
            ->setLanguage($faker->randomElement($langues))
            ->setCategories($faker->randomElements($categories, $faker->numberBetween(1, 3)))
            ->setAuthors($faker->randomElements($auteurs, $faker->numberBetween(1,2)))
            ;

        $livres[] = $livre;
        $manager->persist($livre);
        }

        $manager->flush();
    }
}