<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Faker;
use  Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr-FR');

        //Nous générons un rôle ADMIN pour un utilisateur
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');

        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Manon')
                    ->setLastName('Baillet')
                    ->setEmail('manon.baillet@symfony.fr')
                    ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                    ->setPicture('https://via.placeholder.com/140x140')
                    ->setIntroduction($faker->sentence)
                    ->setDescription('<p>' . join ('</p><p>', $faker->paragraphs(3)) .'</p>')
                    ->addUserRole($adminRole);

        $manager->persist($adminUser);


        //Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++)
        {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $hash = $this->encoder->encodePassword($user, 'password');

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $user -> setFirstName($faker->firstName($genres))
                -> setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join ('</p><p>', $faker->paragraphs(3)) .'</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        //Nous gérons les annonces
        for ($i = 1; $i <=30; $i++) {
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageURL(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join ('</p><p>', $faker->paragraphs(5)) .'</p>'; //On va avoir 5 paragraphes formatés dans des balises p HTML

            $user = $users[mt_rand(0, count($users)-1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200)) //Permet de donner un nombre random entre 40 et 200
                ->setRooms(mt_rand(1, 6))
                ->setAuthor($user);

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
