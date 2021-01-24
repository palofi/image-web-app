<?php

namespace App\Controller;


use App\Entity\Image;
use App\Entity\User;
use App\Services\Image\ImageService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("image/")
 */
class ImageController extends AbstractController
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
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ImageService $imageService,EntityManagerInterface $entityManager)
    {
        $this->imageService = $imageService;
        $this->entityManager = $entityManager;

    }

    /**
     * @Route("{image}/", name="image_index")
     * @param Image $image
     * @return Response
     */
    public function index(Image $image): Response
    {
        return $this->render('image/index.html.twig', [
            'image' => $image
        ]);
    }

    /**
     * @Route("{image}/delete", name="image_delete")
     * @param Image $image
     * @return RedirectResponse
     */
    public function delete(Image $image): RedirectResponse
    {
        $this->entityManager->remove($image);
        $this->entityManager->flush();

        return $this->redirectToRoute('gallery_index');
    }

    /**
     * @Route("/{image}/share/{user}", name="image_share")
     * @param Image $image
     * @param User $user
     * @return RedirectResponse
     */
    public function share(Image $image, User $user): RedirectResponse
    {

        $this->imageService->shareWithUser($image,$user);
        return $this->redirectToRoute('image_index', ['image' => $image]);
    }


    /**
     * @Route("public/{image}", name="view_public_image")
     * @param Image $image
     * @return Response
     */
    public function publicView(Image $image): Response
    {
        return $this->render('image/public.html.twig', [
            'image' => $image
        ]);
    }


}
