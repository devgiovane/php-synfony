<?php


namespace App\Factory;


abstract class PasswordCrypto
{
    public static function encrypt(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verify(string $password, string  $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function createAlias(string $name, int $id): string
    {
        $name = iconv("UTF-8", "ASCII//TRANSLIT", $name);
        $alias = (str_replace(" ", ".", strtolower(preg_replace("/[^\w\s]/", "", $name ))));
        return $alias . '.' . $id;
    }
}
