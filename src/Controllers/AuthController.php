<?php
/**
 * Created by PhpStorm.
 * User: nil
 * Date: 6/5/18
 * Time: 14:59
 */

namespace Src\Controllers;


use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Middleware\TokenAuthentication\TokenNotFoundException;
use Src\Auth\UnauthorizedException;
use Src\Entity\Usuari;

class AuthController
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * AuthController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param string $token
     * @param EntityManager $em
     * @return bool
     * @throws UnauthorizedException
     */
    public static function validateToken($token, EntityManager $em){

        /** @var Usuari $user */
        $user = $em->getRepository('Src\Entity\Usuari')->findOneBy(array('clau' => $token));

        if (!is_null($user)) {
            if ($user->getLastLogin()->diff(new \DateTime())->h < 1){ //less than 60 minutes since last login
                return true;
            }
        }

        throw new UnauthorizedException('Invalid Token');
    }

    /**
     * This method doesn't check for verification because it should only be called in route callbacks,
     * where the request has already past the auth middleware.
     * @param Request $request
     * @return Usuari
     */
    public function getAuthenticatedUser($request){

        $token = $this->findToken($request);

        /** @var Usuari $user */
        $user = $this->em->getRepository('Src\Entity\Usuari')->findOneBy(array('clau' => $token));
        return $user;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    private function findToken(Request $request){

        if ($request->hasHeader('Authorization')){
            $header = $request->getHeader('Authorization')[0];
            if (preg_match('/Bearer\s+(.*)$/i', $header,$matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}