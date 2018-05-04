<?php
/**
 * Created by PhpStorm.
 * User: nil
 * Date: 4/5/18
 * Time: 1:38
 */

namespace Src\Controllers;
use Slim\Http\Request;
use Slim\Http\Response;

class PublicController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * PublicController constructor.
     * @param $em \Doctrine\ORM\EntityManager
     */
    public function __construct($em)
    {
        $this->em = $em;
    }


    public function getAllAction(Request $request, Response $response, array $args){

        $data = array();

        $data["hola"] = "adeu";
        return $response->withJson($data,200);
    }


}