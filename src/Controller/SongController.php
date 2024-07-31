<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Form\SongType;
use App\Repository\PlaylistRepository;
use App\Repository\SongRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/song')]
class SongController extends AbstractController
{
    #[Route('/new/{id}', name: 'app_add_song', methods: ['GET', 'POST'])]
    public function new(
        Playlist $playlist,
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader
    ): Response
    {
        $song = new Song();
        $song->addPlaylist($playlist);

        $form = $this->createForm(SongType::class, $song, [
            'playlist' => $playlist
        ]);

        $imgFile = $form->get('image')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $form->handleRequest($request);

            if($imgFile) {
                $fileName = $fileUploader->handleImageUpload($imgFile);
                $song->setFilename($fileName);
            }

            $em->persist($song);
            $em->flush();

            $playlistName = $playlist->getName();

            $this->addFlash('success', "New song has been added to the playlist $playlistName");

            return $this->redirectToRoute('app_playlists');
        }

        return $this->render('song/_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{playlist}/edit/{id}', name: 'app_edit_song', methods: ['GET', 'POST'])]
    public function edit(
        Playlist $playlist,
        Song $song,
        Request $request,
        EntityManagerInterface $em,
        $targetDirectory,
        FileUploader $fileUploader
    ): Response
    {
        $form = $this->createForm(SongType::class, $song, [
            'playlist' => $playlist
        ]);

        if($song->getFilename()) {
            //$song->setFilename(new File($targetDirectory.'/'.$song->getFilename()));
            $form->get('image')->setData(new File($targetDirectory.'/'.$song->getFilename()));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('image')->getData();

            if($imgFile) {
                $fileName = $fileUploader->handleImageUpload($imgFile);
                $song->setFilename($fileName);
            }

            $em->persist($song);
            $em->flush();

            $songTitle = $song->getTitle();

            $this->addFlash('success', "The song '$songTitle' has been updated.");

            return $this->redirectToRoute('app_playlists');
        }

        return $this->render('song/_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_show_song', methods: ['GET'])]
    public function show(Song $song, SongRepository $songRepository)
    {
        $playlists = $song->getPlaylists();
        $filteredPlayLists = $playlists->filter(fn($playlist) => $playlist->getUser() === $this->getUser());

        return $this->render('song/show.html.twig', [
            'song' => $song,
            'filteredPlayLists' => $filteredPlayLists,
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
