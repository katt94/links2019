<?php
/**
 * Tag fixtures file.
 */

namespace App\DataFixtures;

use App\Entity\Link;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LinkFixtures.
 */
class LinkFixtures extends AbstractBaseFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);

        /** @var User $users */
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'user@user.pl']);

        /** @var Tag[] $tags */
        $tags = $manager->getRepository(Tag::class)->findAll();

        for ($i = 0; $i < $this->faker->numberBetween(200, 1000); ++$i) {
            $link = new Link();
            $url = $this->faker->unique()->url;

            $link->setCreatedAt($this->faker->dateTimeThisMonth());
            $link->setUpdatedAt($this->faker->dateTimeThisMonth());
            $link->setUser($user);
            $link->setUrl($url);

            $hash = base_convert(
                intval(
                    substr(
                        md5($link->getUrl().$this->faker->unique()->numberBetween(strtotime(date('c')))),
                        0,
                        16
                    ),
                    16
                ),
                7,
                16
            );

            $link->setHash($hash);

            for ($j = 0; $j < $this->faker->randomDigitNotNull; ++$j) {
                $link->addTag($this->faker->randomElement($tags));
            }

            $manager->persist($link);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
