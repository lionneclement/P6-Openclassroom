<?php

namespace App\Tools;

class Token
{
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
