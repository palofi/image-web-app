<?php


namespace App\Services\Image;



use App\Entity\Image;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


class ImageService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    private $targetDirectory = 'gallery/';

    /**
     * Security constructor.
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     */
    public function __construct(EntityManagerInterface $entityManager,SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger =$slugger;
    }

    /**
     * @param $file
     * @return string|null
     */
    public function upload($file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
         return null;
        }


        return $this->getTargetDirectory().$fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function shareWithUser(Image $image, User $user)
    {
        $user->addSharedImage($image);
        $this->entityManager->persist($image);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}