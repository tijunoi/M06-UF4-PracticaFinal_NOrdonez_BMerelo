<?php
/**
 * Created by PhpStorm.
 * User: nil
 * Date: 6/5/18
 * Time: 14:59
 */

namespace Src\Controllers;


use Doctrine\ORM\EntityManager;
use Src\Auth\UnauthorizedException;
use Src\Entity\Usuari;

class AuthController
{
    /**
     * @param string $token
     * @param EntityManager $em
     * @return Usuari
     * @throws UnauthorizedException
     */
    public static function validateToken($token, EntityManager $em){

        /** @var Usuari $user */
        $user = $em->getRepository('Src\Entity\Usuari')->findOneBy(array('clau' => $token));

        if (!is_null($user)) {
            if ($user->getLastLogin()->diff(new \DateTime())->h < 1){ //less than 60 minutes since last login
                return $user;
            }
        }

        throw new UnauthorizedException('Invalid Token');
    }
}