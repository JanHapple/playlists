<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private readonly string $targetDirectory,
        private readonly SluggerInterface $slugger
    )
    {
    }

    public function handleImageUpload(UploadedFile $file): string
    {
         $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
         $safeFilename = $this->slugger->slug($originalFilename);
         $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

         try {
             $file->move($this->getTargetDirectory(), $fileName);
         } catch (FileException $e) {
             throw new FileException("The file couldn't be processed " . "(" . $e->getMessage() . ")");
         }

         return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}