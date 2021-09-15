<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use App\Entity\Purchase;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Product($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $users = [];
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            // $user->setPassword("password");
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setPassword($hash);
            $user->setFullName($faker->name);
            $user->setEmail("user$u@gmail.com");
            $manager->persist($user);
            $users[] = $user;
        }

        $admin = new User();
        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setFullName("Admin");
        $admin->setEmail("admin@gmail.com");
        $admin->setPassword($hash);
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);



        $products = [];



        for ($i = 0; $i < 3; $i++) {

            $category = new Category();
            $category->setName($faker->name);
            $category->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($x = 0; $x < 15; $x++) {
                $product = new Product();
                $product
                    ->setName($faker->sentence())
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDecription($faker->text())
                    ->setMainPicture($faker->imageUrl(300, 300, true));

                $products[] = $product;
                
                $manager->persist($product);
            }
        }


        for($p = 0; $p < 10; $p++){
            $purchase = new Purchase();
            $purchase->setFullName($faker->name);
            $purchase->setAddress($faker->streetAddress);
            $purchase->setPostalCode($faker->postcode);
            $purchase->setCity($faker->city);
            if($faker->boolean(80)){
                $purchase->setStatus(Purchase::STATUS_PAID);
            }  
            $purchase->setUser($faker->randomElement($users));    
            $purchase->setTotal(mt_rand(4000, 20000));  
            $purchase->setPurchasedAt($faker->dateTimeBetween('-6 months'));

            $selectedProducts = $faker->randomElements($products, mt_rand(1, 3 ));

            foreach($selectedProducts as $product){
                $purchase->addProduct($product);
            }

            
            $manager->persist($purchase);
        }
        $manager->flush();
    }
}
