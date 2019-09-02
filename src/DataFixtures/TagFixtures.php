<?php
/**
 * Tag fixtures file.
*/

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);

        for ($i = 0; $i < 10; ++$i) {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->monthName());

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
