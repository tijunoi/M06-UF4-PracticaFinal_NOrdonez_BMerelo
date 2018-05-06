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
use Src\Entity\Notes;

class PublicController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * @var AuthController
     */
    public $auth;

    /**
     * PublicController constructor.
     * @param \Doctrine\ORM\EntityManager $em
     * @param AuthController $auth
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, AuthController $auth)
    {
        $this->em = $em;
        $this->auth = $auth;
    }


    public function getAllAction(Request $request, Response $response, array $args){

        $currentUser = $this->auth->getAuthenticatedUser($request);

        $repo = $this->em->getRepository('Src\Entity\Notes');

        $notes = $repo->findBy(array("user" => $currentUser));


        $data = array();
        /** @var Notes $note */
        foreach ($notes as $note){

            $new = array();
            $new["id"] = $note->getId();
            $new["title"] = $note->getTitle();
            $new["content"] = $note->getContent();
            $new["private"] = $note->getPrivate();
            $new["tag1"] = $note->getTag1();
            $new["tag2"] = $note->getTag2();
            $new["tag3"] = $note->getTag3();
            $new["tag4"] = $note->getTag4();
            $new["book"] = $note->getBook();
            $new["createData"] = $note->getCreatedata();
            $new["lastModificationData"] = $note->getLastmodificationdata();
            $data[] = $new;
        }
        $code = 200;

        if (empty($data)) {
            $data["msg"] = "No notes found!";
            $code = 204;
        }
        return $response->withJson($data,$code);
    }


}