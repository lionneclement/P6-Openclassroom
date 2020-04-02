<?php

namespace App\Tools;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
  private $photoDirectory;

  public function __construct($photoDirectory)
  {
      $this->photoDirectory = $photoDirectory;
  }

  public function uploadImage(UploadedFile $file)
  {
    $fileName = 'image'.'-'.uniqid().'.'.$file->guessExtension();

    try {
      $file->move($this->getPhotoDirectory(), $fileName);
    } catch (FileException $e) {
    }

    return $fileName;
  }

  public function getPhotoDirectory()
  {
      return $this->photoDirectory;
  }

  public function removeImage($file)
  {
      $filesystem = new Filesystem();
      $filesystem->remove(['symlink', $this->getPhotoDirectory(), $file]);
  }
}