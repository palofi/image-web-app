<?php

namespace App\Controller;


use App\Entity\Image;
use App\Form\ImageFormType;
use App\Services\Image\ImageService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{


    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * GalleryController constructor.
     * @param ImageService $imageService
     * @param EntityManager $entityManager
     */
    public function __construct(ImageService $imageService,EntityManagerInterface $entityManager)
    {
        $this->imageService = $imageService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/gallery", name="gallery_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $image = new Image();
        $image->setOwner($this->getUser());
        $form = $this->createForm(ImageFormType::class,$image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {

                $imageFileName = $this->imageService->upload($imageFile);
                $image->setSrc($imageFileName);
                $this->entityManager->persist($image);
                $this->entityManager->flush();
            }
        }

        $imagesRepo = $this->entityManager->getRepository(Image::class);
        $images = $imagesRepo->findBy([
            'owner' => $this->getUser()
        ]);

        return $this->render('gallery/index.html.twig', [
            'form' => $form->createView(),
            'images' => $images
        ]);

    }






}
