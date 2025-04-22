<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Enum\Difficulty;
use App\Enum\TrickType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $hashedPassword = $this->hasher->hashPassword($user, 'password');
            $user->setUsername($faker->unique()->userName)
                ->setEmail($faker->unique()->email)
                ->setPassword($hashedPassword);

            $manager->persist($user);
            $users[] = $user;
        }

        $tricks = [];
        for ($i = 0; $i < 10; $i++) {
            $trick = new Trick();
            $trick->setName($faker->unique()->word)
                ->setSlug($faker->unique()->slug)
                ->setDescription($faker->paragraph)
                ->setDifficulty($faker->randomElement([Difficulty::EASY, Difficulty::MEDIUM, Difficulty::HARD]))
                ->setTrickType($faker->randomElement([
                    TrickType::GRABS,
                    TrickType::SPINS,
                    TrickType::FLIPS,
                    TrickType::SLIDES_GRINDS,
                    TrickType::BUTTER_TRICKS,
                    TrickType::ONE_FOOT_TRICKS,
                    TrickType::FREESTYLE_AERIAL_TRICKS
                ]))
                ->setUser($faker->randomElement($users));

            $manager->persist($trick);
            $tricks[] = $trick;
        }

        for ($i = 0; $i < 20; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->sentence)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUser($faker->randomElement($users))
                ->setTrick($faker->randomElement($tricks));

            $manager->persist($comment);
        }

        for ($i = 0; $i < 30; $i++) {
            $media = new Media();
            $media->setType($faker->randomElement(['image', 'video']))
                ->setUrl($faker->imageUrl())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setTrick($faker->randomElement($tricks));

            $manager->persist($media);
        }

        $manager->flush();
    }
}
