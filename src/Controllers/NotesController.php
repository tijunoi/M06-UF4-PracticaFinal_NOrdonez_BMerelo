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
            $new["createData"] = $note->getCreateData();
            $new["lastModificationData"] = $note->getLastmodificationdata();
            $data[] = $new;
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
            $new["user_id"] = $note->getUser()->getId();
            $new["user_name"] = $note->getUser()->getNom();
            $new["createData"] = $note->getCreateData();
            $new["lastModificationData"] = $note->getLastmodificationdata();
            $data[] = $new;
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insertAction(Request $request, Response $response, array $args){

        $currentUser = $this->auth->getAuthenticatedUser($request);

        $title = $request->getParsedBodyParam('title');
        $content = $request->getParsedBodyParam('content');
        $private = $request->getParsedBodyParam('private');
        $tag1 = $request->getParsedBodyParam('tag1');
        $tag2 = $request->getParsedBodyParam('tag2');
        $tag3 = $request->getParsedBodyParam('tag3');
        $tag4 = $request->getParsedBodyParam('tag4');
        $book = $request->getParsedBodyParam('book');

        if (!is_null($title) && !is_null($content) && !is_null($private)) {
            //insert
            $note = new Notes();
            $note->setTitle($title);
            $note->setContent($content);
            if (strcmp($private,"true") == 0 ){
                $note->setPrivate(true);
            } else {
                $note->setPrivate(false);
            }
            $note->setTag1($tag1);
            $note->setTag2($tag2);
            $note->setTag3($tag3);
            $note->setTag4($tag4);
            $note->setBook($book);
            $note->setUser($currentUser);
            $note->setCreateData(new \DateTime());

            $this->em->persist($note);
            $this->em->flush();
            $data["msg"] = "The note has been created successfully";
            $code = 201;

        } else {
            $data["msg"] = "Missing parameters title, content, or private";
            $code = 400;
        }

        return $response->withJson($data,$code);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(Request $request, Response $response, array $args){

        $currentUser = $this->auth->getAuthenticatedUser($request);

        $id = $request->getParsedBodyParam('id');

        if (!is_null($id)){

            $repo = $this->em->getRepository('Src\Entity\Notes');

            $note = $repo->find($id);

            if (!is_null($note)){
                //check if owned
                if ($note->getUser()->getId() == $currentUser->getId()) {
                    $this->em->remove($note);
                    $this->em->flush();
                    $data["msg"] = "Note has been deleted successfully.";
                    $code = 200;
                } else {
                    $data["msg"] = "You're not authorized to delete this note.";
                    $code = 403;
                }
            } else {
                $data["msg"] = "Note with given id not found";
                $code = 404;
            }


            //remove
        } else {
            $data["msg"] = "Missing parameter id.";
            $code = 400;
        }

        return $response->withJson($data,$code);


    }

    public function getAllWithTagAction(Request $request, Response $response, array $args){

        $repo = $this->em->getRepository('Src\Entity\Notes');

        $orderArray = null;

        $orderBy = $request->getQueryParam('order');
        $tag = $request->getQueryParam('tag');

        if (!is_null($tag)) {
            //search
           $builder =$repo->createQueryBuilder('note');
           $builder->select('note')
               ->where($builder->expr()->like('note.tag1',':tag'))
               ->orWhere($builder->expr()->like('note.tag2','tag'))
               ->orWhere($builder->expr()->like('note.tag3',':tag'))
               ->orWhere($builder->expr()->like('note.tag4',':tag'))
               ->setParameter('tag',$tag);



            if (!is_null($orderBy)) {
                if (strcmp($orderBy, "titol") == 0) {
                    $builder->orderBy('note.title','ASC');
                } else if (strcmp($orderBy, "data") == 0) {
                    $builder->orderBy('note.createData', 'DESC');
                }
            }

            $notes = $builder->getQuery()->getResult();

            $data = array();
            /** @var Notes $note */
            foreach ($notes as $note) {

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
                $new["createData"] = $note->getCreateData();
                $new["lastModificationData"] = $note->getLastmodificationdata();
                $data[] = $new;
            }
            $code = 200;

            if (empty($data)) {
                $data["msg"] = "No notes found!";
                $code = 204;
            }


        } else {
            $data["msg"] = "Missing query parameter tag";
            $code = 400;
        }
        return $response->withJson($data, $code);
    }


    public function testAction(Request $request, Response $response, array $args)
    {


        $mibool = $request->getParsedBodyParam('private');

        var_dump($mibool);

        return var_dump($mibool);
    }


}