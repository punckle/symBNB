<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker;
use  Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr-FR');

        for ($i = 1; $i <=30; $i++) {
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageURL(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join ('</p><p>', $faker->paragraphs(5)) .'</p>'; //On va avoir 5 paragraphes formatés dans des balises p HTML

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200)) //Permet de donner un nombre random entre 40 et 200
                ->setRooms(mt_rand(1, 6));

            for($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // $product = new Product();
            // $manager->persist($product);

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
