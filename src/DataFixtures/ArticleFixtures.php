<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\CommentLike;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture
{

/**
 * @var UserPasswordEncoderInterface
 */
public function __construct(UserPasswordEncoderInterface $encoder)
{

    $this->encoder=$encoder;
}


    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
         for ($y=0; $y <10 ; $y++) { 
            $cat=new Category();
        $cat->setTitle($faker->sentence())
            ->setDescription($faker->paragraph());
        $manager->persist($cat);
         }
        

        for ($i=0; $i <20 ; $i++) 
        { 
            $user = new User();
            $user->setEmail($faker->email)
                 ->setUsername($faker->name)
                 ->setPassword($this->encoder->encodePassword($user,'nadim123'));
                 $users[]=$user;
            $manager->persist($user);

            $pr = new Profil();
                 $pr->setAdresse($faker->address)
                    ->setPhone($faker->phoneNumber)
                    ->setPicture($faker->imageUrl())
                    ->setUser($user);
                 $manager->persist($pr);

           for ($j=0; $j<mt_rand(5,8) ; $j++) { 
                $article = new Article();
                $article->setTitle($faker->sentence())
                        ->setContent($faker->text())
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('- 6 months '))
                        ->setCategory($cat)
                        ->setUser($user);
                $manager->persist($article);

            for ($k=0; $k <mt_rand(4,6) ; $k++) {
                    $comment=new Comment();
                    $now=new \DateTime();
                    $date=$now->diff($article->getCreatedAt());
                    $days=$date->days;
                    $comment->setAuthor($faker->name())
                            ->setContent($faker->text())
                            ->setCreatedAt($faker->dateTimeBetween('-'.$days.'days'))
                            ->setArticle($article)
                            ->setUser($user);
                    $manager->persist($comment);

                    for ($m=0; $m <mt_rand(4,6); $m++) { 
                        $like=new CommentLike();
                        $like->setComment($comment)
                             ->setUser($faker->randomElement($users));
                             $manager->persist($like);

                    } 

                }
            }
        }

        $manager->flush();
    }
}
