<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('test%d@test.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'test'));
            return $user;
        });
        
        $this->createMany(2, 'admin_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@test.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setRoles(['ROLE_ADMIN']);
            
            $user->setPassword($this->passwordEncoder->encodePassword($user,'admin'));
            return $user;
        });
            $manager->flush();
    }
}
