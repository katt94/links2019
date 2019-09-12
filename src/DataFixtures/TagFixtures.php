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

        for ($i = 0; $i < $this->faker->numberBetween(20, 1000); ++$i) {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->text(20));

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
