<?php
/** 
 * The file is for token
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
/** 
 * The class is for token
 * 
 * @category Tools
 * @package  Tools
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class Token
{
    /**
     * Generate a token
     * 
     * @param int $numberCharacter 
     * 
     * @return string 
     */
    public function generator($numberCharacter)
    {
        $character = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        shuffle($character);
        $rand = '';
        foreach (array_rand($character, $numberCharacter) as $k) { 
            $rand .= $character[$k];
        }
        return $rand;
    }
}
