<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploaderHelper
{
    private $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger=$slugger;
    }

    public function upload(UploadedFile $uploadedFile,$location="upload"):string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $uploadedFile->move(
                $location, $newFilename
            );
        } catch (FileException $e) {
            dd($e);
        }

        return $newFilename;
    }
}
