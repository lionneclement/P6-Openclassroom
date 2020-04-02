<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFile
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
}