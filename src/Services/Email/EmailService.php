<?php


namespace App\Services\Email;



use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @ORM\Column(type="string")
     */
    private $mailer;


    /**
     * Security constructor.
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     */
    public function __construct(EntityManagerInterface $entityManager,MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

    }

    public function sendConfirmEmail(string $email)
    {
        $email = (new Email())
            ->from('palofiser@gmail.com')
            ->to($email)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

    }
}