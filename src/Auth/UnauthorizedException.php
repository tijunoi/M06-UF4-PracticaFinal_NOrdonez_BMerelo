<?php
/**
 * Created by PhpStorm.
 * User: nil
 * Date: 6/5/18
 * Time: 14:21
 */

namespace Src\Auth;


use Slim\Middleware\TokenAuthentication\UnauthorizedExceptionInterface;

/**
 * Class UnauthorizedException
 *
 * Esta clase es necesaria para devolver error cuando el token no se verifica.
 * Ya viene implementada, solo que hay que crear una propia que extienda e implemente.
 *
 * @package Src\Auth
 */
class UnauthorizedException extends \Exception implements UnauthorizedExceptionInterface
{

}