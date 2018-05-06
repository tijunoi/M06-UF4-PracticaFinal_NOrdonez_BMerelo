<?php
/**
 * Created by PhpStorm.
 * User: nil
 * Date: 6/5/18
 * Time: 14:59
 */

namespace Src\Controllers;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Slim\Http\Request;
use Slim\Http\Response;
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
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getTokenAction(Request $request, Response $response, array $args){
        $username = $request->getParsedBodyParam('username');

        $data = array();
        $code = 200;
        if (!is_null($username)){

            /** @var Usuari $user */
            $user = $this->em->getRepository('Src\Entity\Usuari')->findOneBy(array('nom' => $username));

            if (!is_null($user)) {
                //generate token
                $token = $this->generateToken($user);
                $data['token'] = $token;
                $data['token_type'] = "Bearer";
                $data['expires_in'] = "1h";
            } else {
                //usernotfound
                $data["msg"] = "Username not found";
                $code = 204;
            }


        } else {
            //return error
            $data["msg"] = "Parameter username missing";
            $code = 400;
        }

        return $response->withJson($data,$code);

    }

    /**
     * @param Usuari $user
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function generateToken(Usuari $user) {
        $lastLogin = new \DateTime();
        $sentenceToHash = 'L$n0t3' . $user->getNom() . $lastLogin->getTimestamp();
        $token = hash('sha512',$sentenceToHash);
        $user->setClau($token);
        $user->setLastLogin($lastLogin);
        $this->em->persist($user);
        $this->em->flush();
        return $token;
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

            $date = $user->getLastLogin();

            $now = new \DateTime('now');
            $diff = $date->getTimestamp() - $now->getTimestamp();

            if (abs($diff) < 3600){ //less than 60 minutes since last login
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