<?php
/**
 * Abstract base fixture file.
*/

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class BaseFixture.
 */
class AbstractBaseFixture extends Fixture
{
    /**
     * Faker generator.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Object manager.
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
    }
}
