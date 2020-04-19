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
    public function uploadImage(UploadedFile $file)
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
    public function getPhotoDirectory()
    {
        return $this->_photoDirectory;
    }
    /**
     * Upload Image
     * 
     * @param string $file 
     * 
     * @return void 
     */
    public function removeImage(string $file)
    {
        $filesystem = new Filesystem();
        $filesystem->remove([$this->getPhotoDirectory().'/'.$file]);
    }
}
