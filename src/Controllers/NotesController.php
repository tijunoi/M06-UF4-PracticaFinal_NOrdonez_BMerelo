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

class NotesController
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


    public function getAllAction(Request $request, Response $response, array $args)
    {

        $currentUser = $this->auth->getAuthenticatedUser($request);

        $repo = $this->em->getRepository('Src\Entity\Notes');

        $orderArray = null;

        $orderBy = $request->getQueryParam('order');

        if (!is_null($orderBy)) {
            if (strcmp($orderBy, "titol") == 0) {
                $orderArray = ["title" => 'ASC'];
            } else if (strcmp($orderBy, "data") == 0) {
                $orderArray = ["createData" => 'DESC'];
            }
        }


        if (is_null($orderArray)) {
            $notes = $repo->findBy(array("user" => $currentUser));
        } else {
            $notes = $repo->findBy(array("user" => $currentUser), $orderArray);
        }


        $data = array();
        /** @var Notes $note */
        foreach ($notes as $note) {

            $data = array();
            $data["id"] = $note->getId();
            $data["title"] = $note->getTitle();
            $data["content"] = $note->getContent();
            $data["private"] = $note->getPrivate();
            $data["tag1"] = $note->getTag1();
            $data["tag2"] = $note->getTag2();
            $data["tag3"] = $note->getTag3();
            $data["tag4"] = $note->getTag4();
            $data["book"] = $note->getBook();
            $data["createData"] = $note->getCreateData();
            $data["lastModificationData"] = $note->getLastmodificationdata();
            $data[] = $data;
        }
        $code = 200;

        if (empty($data)) {
            $data["msg"] = "No notes found!";
            $code = 204;
        }
        return $response->withJson($data, $code);
    }

    public function getPublicAction(Request $request, Response $response, array $args)
    {

        $repo = $this->em->getRepository('Src\Entity\Notes');

        $orderArray = null;

        $orderBy = $request->getQueryParam('order');

        if (!is_null($orderBy)) {
            if (strcmp($orderBy, "titol") == 0) {
                $orderArray = ["title" => 'ASC'];
            } else if (strcmp($orderBy, "data") == 0) {
                $orderArray = ["createData" => 'DESC'];
            }
        }


        if (is_null($orderArray)) {
            $notes = $repo->findBy(array("private" => false));
        } else {
            $notes = $repo->findBy(array("private" => false), $orderArray);
        }


        $data = array();
        /** @var Notes $note */
        foreach ($notes as $note) {

            $data = array();
            $data["id"] = $note->getId();
            $data["title"] = $note->getTitle();
            $data["content"] = $note->getContent();
            $data["private"] = $note->getPrivate();
            $data["tag1"] = $note->getTag1();
            $data["tag2"] = $note->getTag2();
            $data["tag3"] = $note->getTag3();
            $data["tag4"] = $note->getTag4();
            $data["book"] = $note->getBook();
            $data["createData"] = $note->getCreateData();
            $data["lastModificationData"] = $note->getLastmodificationdata();
            $data[] = $data;
        }
        $code = 200;

        if (empty($data)) {
            $data["msg"] = "No notes found!";
            $code = 204;
        }
        return $response->withJson($data, $code);
    }

    public function getOneAction(Request $request, Response $response, array $args)
    {
        $currentUser = $this->auth->getAuthenticatedUser($request);

        $repo = $this->em->getRepository('Src\Entity\Notes');


        $id = $request->getQueryParam('id');

        if (is_null($id)) {
            $data["msg"] = "Missing parameter id";
            $code = 400;
        } else {
            /** @var Notes $note */
            $note = $repo->find($id);

            if (!is_null($note)) {
                if ($note->getUser()->getId() == $currentUser->getId() || $note->getPrivate() == false) {
                    $data = array();
                    $data["id"] = $note->getId();
                    $data["title"] = $note->getTitle();
                    $data["content"] = $note->getContent();
                    $data["private"] = $note->getPrivate();
                    $data["tag1"] = $note->getTag1();
                    $data["tag2"] = $note->getTag2();
                    $data["tag3"] = $note->getTag3();
                    $data["tag4"] = $note->getTag4();
                    $data["book"] = $note->getBook();
                    $data["user_id"] = $note->getUser()->getId();
                    $data["user_name"] = $note->getUser()->getNom();
                    $data["createData"] = $note->getCreateData();
                    $data["lastModificationData"] = $note->getLastmodificationdata();
                    $code = 200;
                } else {
                    $data["msg"] = "You're not authorized to retrieve this note";
                    $code = 403;
                }
            } else {
                $data["msg"] = "The note with id $id doesn't exists";
                $code = 204;
            }
        }

        return $response->withJson($data, $code);
    }

    public function testAction(Request $request, Response $response, array $args)
    {

        $orderBy = $request->getQueryParam('order');


        $orderArray = null;

        $orderBy = $request->getQueryParam('order');

        if (!is_null($orderBy)) {
            if (strcmp($orderBy, "titol") == 0) {
                $orderArray = ["title" => 'ASC'];
            } else if (strcmp($orderBy, "data") == 0) {
                $orderArray = ["createData" => 'DESC'];
            }
        }

        $data = array();

        $data["order"] = $orderBy;
        $data["array"] = $orderArray;

        return $response->withJson($data, 200);
    }


}