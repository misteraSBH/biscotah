<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager )
    {
        $user = new User();
        $user->setEmail("michael@mistera.fr");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "123"));
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $manager->persist($user);

        for($i=1;$i<=20;$i++) {
            $user = new User();
            $user->setEmail("visiteur" . $i . "@test.com");
            $user->setPassword($this->passwordEncoder->encodePassword($user, "123"));

            $manager->persist($user);
        }

        $manager->persist($user);

    }
}
