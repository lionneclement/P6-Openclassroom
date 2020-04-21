<?php
/** 
 * The file is for file
 * 
 * PHP version 7.3.5
 * 
 * @category Tools
 * @package  Tools
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
namespace App\Tools;

use App\Entity\Photo;
use App\Entity\Tricks;
use App\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/** 
 * The class is for file
 * 
 * @category Tools
 * @package  Tools
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class File
{
    private $_photoDirectory;
    /**
     * Construct
     * 
     * @param string $photoDirectory 
     */
    public function __construct($photoDirectory)
    {
        $this->_photoDirectory = $photoDirectory;
    }
    /**
     * Upload Image
     * 
     * @param object $file 
     * 
     * @return string 
     */
    public function uploadImage(UploadedFile $file): string
    {
        $fileName = 'image'.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getPhotoDirectory(), $fileName);
        } catch (FileException $e) {
        }

        return $fileName;
    }
    /**
     * Get photo directory 
     * 
     * @return string 
     */
    public function getPhotoDirectory(): string
    {
        return $this->_photoDirectory;
    }
    /**
     * Remove Image
     * 
     * @param string $file 
     * 
     * @return void 
     */
    public function removeImage(string $file): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove([$this->getPhotoDirectory().'/'.$file]);
    }
    /**
     * Add multiple images
     * 
     * @param object $images  
     * 
     * @return void
     */
    public function uploadMultipleImage(object $images): void
    {
        foreach ($images as $image) {
            $imageFileName = $this->uploadImage($image->getFile());
            $image->setName($imageFileName);
        }
    }
    /**
     * Update multiple images
     * 
     * @param object $images  
     * 
     * @return void
     */
    public function updateMultipleImage(object $images): void
    {
        foreach ($images as $image) {
            if ($image->getFile()) {
                if ($image->getId()) {
                    $this->removeImage($image->getName());
                }
                $imageFileName = $this->uploadImage($image->getFile());
                $image->setName($imageFileName);
            }
        }
    }
    /**
     * Remove multiple old image
     * 
     * @param object $images 
     * @param object $trick 
     * 
     * @return void
     */
    public function removeMultipleOldImage(object $images,Tricks $trick): void
    {
        foreach ($images as $image) {
            if (!$trick->getPhotos()->contains($image)) {
                $this->removeImage($image->getName());
            }
        }
    }
    /**
     * Remove multiple image
     * 
     * @param object $images 
     * 
     * @return void
     */
    public function removeMultipleImage(array $images): void
    {
        foreach ($images as $image) {
            $this->removeImage($image->getName());
        }
    }
    /**
     * Update user profile image
     * 
     * @param object $image 
     * @param object $user 
     * 
     * @return void
     */
    public function updateProfileImage(object $image,User $user): void
    { 
        if ($image) {
            if ($user->getImageName() != 'default-user.png') {
                $this->removeImage($user->getImageName());
            }
            $imageFileName = $this->uploadImage($image);
            $user->setImageName($imageFileName);
        }
    }
}
