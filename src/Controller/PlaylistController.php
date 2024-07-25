<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaylistController extends AbstractController
{
    #[Route('/playlists', name: 'app_playlists')]
    public function index(): Response
    {
        $playlists = $this->getUser()->getPlaylists();

        return $this->render('playlist/index.html.twig', [
            'playlists' => $playlists,
        ]);
    }

    #[Route('/playlist/new', name: 'app_create_playlist')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $playlist = new Playlist();
        $playlist->setUser($this->getUser());

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($playlist);
            $em->flush();

            return $this->redirectToRoute('app_playlists');
        }

        return $this->render('playlist/_form.html.twig', [
            'form' => $form,
        ]);
    }
}
