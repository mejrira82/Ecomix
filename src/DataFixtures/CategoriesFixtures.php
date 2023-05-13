<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}
    
    public function load(ObjectManager $manager): void
    {
        //Computers
        $parent = $this->createCategory('Computers', null, $manager);

        $this->createCategory('Laptop', $parent, $manager);
        $this->createCategory('Gaming Laptop', $parent, $manager);
        $this->createCategory('Desktop Comupter', $parent, $manager);
        $this->createCategory('Gaming Desktop Computer', $parent, $manager);

        //Accessories
        $parent = $this->createCategory('Accessories', null, $manager);

        $this->createCategory('Screen', $parent, $manager);
        $this->createCategory('Keyboard', $parent, $manager);
        $this->createCategory('Mouse', $parent, $manager);
        $this->createCategory('Headphones', $parent, $manager);

        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        return $category;
    }
}