<?php


namespace App\Services\Register;


use App\Entity\Account;
use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\User;
use App\Services\Register\Exceptions\RegisterException;
use App\Services\Security\Credentials;
use Doctrine\ORM\EntityManagerInterface;

class RegisterService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Security constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(Credentials $credentials): ?User
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $credentials->getEmail()]);

        if ($user !== null) {
          return null;
        }

        $newUser = new User();
        $newUser->setPassword($credentials->getPassword())->hashPassword();
        $newUser->setEmail($credentials->getEmail());
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        return $newUser;

    }
}