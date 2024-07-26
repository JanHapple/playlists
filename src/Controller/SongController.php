<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Form\SongType;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SongController extends AbstractController
{
    #[Route('/song/new/{id}', name: 'app_add_song', methods: ['GET', 'POST'])]
    public function new(Playlist $playlist, Request $request,EntityManagerInterface $em): Response
    {
        $song = new Song();
        $song->addPlaylist($playlist);

        $form = $this->createForm(SongType::class, $song, [
            'playlist' => $playlist
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($song);
            $em->flush();

            return $this->redirectToRoute('app_playlists');
        }

        return $this->render('song/_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_delete_song', methods: 'POST')]
    public function delete(Request $request, Song $song, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {
            $em->remove($song);
            $em->flush();
        }

        return $this->redirectToRoute('app_playlists');
    }
}
