<?php

namespace App\DataFixtures;

use App\Entity\Style;
use App\Entity\Tricks;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
            $dataWikipedia=[
                [
                    'title'=>'Les grabs',
                    'style'=>'Les grabs',
                    'description'=>'Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »'
                ],
                [
                    'title'=>'Les rotations',
                    'style'=>'Les rotations',
                    'description'=>'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal.'
                ],
                [
                    'title'=>'Les flips',
                    'style'=>'Les flips',
                    'description'=>'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.'
                ],
                [
                    'title'=>'Les slides',
                    'style'=>'Les slides',
                    'description'=>'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.'
                ],
                [
                    'title'=>'Les one foot tricks',
                    'style'=>'Les one foot tricks',
                    'description'=>'Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé. Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise réception.'
                ],
                [
                    'title'=>'Old school',
                    'style'=>'Old school',
                    'description'=>'Le terme old school désigne un style de freestyle caractérisée par en ensemble de figure et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990 (par opposition à new school)'
                ],
            ];
            /**
             * Add user
             */
            $user = new User();
        
            $user->setEmail("admin@gmail.com");
            $user->setName("Admin");
            $user->setRoles(["ROLE_ADMIN"]);
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPassword($password);
            $user->setImageName('default-user.png');
            $manager->persist($user);
            
            $user = new User();
        
            $user->setEmail("user@gmail.com");
            $user->setName("User");
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPassword($password);
            $user->setImageName('default-user.png');
            $manager->persist($user);

            foreach ($dataWikipedia as $data) {
                /**
                 * Add style
                 */
                $style = new Style();

                $style->setName($data['style']);
                $manager->persist($style);
            
                /**
                 * Add tricks
                 */
                $trick = new Tricks();

                $trick->setTitle($data['title']);
                $trick->setDescription($data['description']);
                $trick->setCreateDate(new \DateTime());
                $trick->setUpdateDate(new \DateTime());
                $trick->setStyleId($style);
                $manager->persist($trick);
            }
            

            $manager->flush();
    }
}
