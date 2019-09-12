<?php
/**
 * User fixtures file.
*/

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);

        $user = new User();
        $user->setEmail('user@user.pl');
        $user->setFirstName('User');
        $user->setRoles(['ROLE_USER', 'ROLE_LINK_EDITOR']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, 'test123')
        );

        $manager->persist($user);

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail($this->faker->unique()->freeEmail);
            $user->setFirstName($this->faker->firstName());
            $user->setRoles(['ROLE_USER', 'ROLE_LINK_EDITOR']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'test123')
            );

            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail('admin@admin.pl');
        $user->setFirstName('Admin');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, 'test123')
        );

        $manager->persist($user);

        for ($i = 0; $i < 3; ++$i) {
            $user = new User();
            $user->setEmail($this->faker->unique()->companyEmail);
            $user->setFirstName($this->faker->firstName());
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'test123'
                )
            );

            $manager->persist($user);
        }

        $manager->flush();
    }
}
