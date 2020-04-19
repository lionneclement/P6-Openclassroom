<?php 
/** 
 * The file is for slugger
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

use Symfony\Component\String\Slugger\AsciiSlugger;
/** 
 * The class is for slugger
 * 
 * @category Tools
 * @package  Tools
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class Slugify
{
    private $slugger;

    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
    }

    public function sluggerLowerCase(string $word)
    {
        $slug = $this->slugger->slug($word)->lower();
        return $slug;
    }
}
