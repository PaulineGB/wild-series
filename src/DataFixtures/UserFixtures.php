<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Création d’un utilisateur de type “contributeur” (= auteur)
        $contributor = [
            [
                'email' => 'contributor@monsite.com',
                'username' => 'Con Tri',
                'password' => 'contributorpassword',
            ],
            [
                'email' => 'cmoi@yahoo.fr',
                'username' => 'Cé Moi',
                'password' => 'cemoi',
            ],
            [
                'email' => 'jpp@caramail.fr',
                'username' => 'JPP',
                'password' => 'jpp',
            ],
            [
                'email' => 'hello@wanadoo.fr',
                'username' => 'Hell Oh',
                'password' => 'hello',
            ],
            [
                'email' => 'mimi@cracra.com',
                'username' => 'Mimi Cracra',
                'password' => 'mimi',
            ],
        ];

        foreach ($contributor as $key => $data) {
            $contributor = new User();
            $contributor->setEmail($data['email']);
            $contributor->setUsername($data['username']);
            $contributor->setRoles(['ROLE_CONTRIBUTOR']);
            $contributor->setPassword($this->passwordEncoder->encodePassword($contributor, $data['password']));
            $this->setReference('user_1', $contributor);

            $manager->persist($contributor);
        }

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setUsername('Jean-Michel');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();

    }
}
