<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Permission;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

/**
 * Permission controller.
 *
 * @Route("permissions")
 */
class PermissionController extends Controller
{
    /**
     * Lists all permission entities.
     *
     * @Route("/", name="permissions_index")
     * @Method("GET")
     */
    public function indexAction()
    {
       
        $em = $this->getDoctrine()->getManager();

        $permission = $em->getRepository('AppBundle:Permission')->findAll();

          $response = new Response($this->serialize(['type'=>"Permission List",'code'=>1,'data'=>$permission]), Response::HTTP_CREATED);
             return $this->setBaseHeaders($response);
    }

    /**
     * Creates a new permission entity.
     *
     * @Route("/", name="permissions_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
         $em = $this->getDoctrine()->getManager();
        //      var_dump($request->request->get('array'));
        // die;

        $permission = new Permission();
        $permission->setDate(new \DateTime($request->request->get('date')));
        
        $permission->setAccepted(false);
         $permission->setUser($em->getRepository('AppBundle:User')->findOneById($request->request->get('user')));




        try {
            $em->persist($permission);
            $em->flush();

            $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$permission]), Response::HTTP_CREATED);
        } catch (Exception $e) {
                  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
        }
              return $this->setBaseHeaders($response);
    }

    /**
     * Finds and displays a permission entity.
     *
     * @Route("/{id}", name="permissions_show")
     * @Method("GET")
     */
    public function showAction(Permission $permission)
    {
         $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$permission]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response);
    }

    /**
     * Displays a form to edit an existing permission entity.
     *
     * @Route("/{id}", name="permissions_edit")
     * @Method("PUT")
     */
    public function editAction(Request $request, Permission $permission)
    {

$em = $this->getDoctrine()->getManager();
         $permission->setDate(new \DateTime($request->request->get('date'))); 
        $permission->setAccepted($request->request->get('accepted'));
         $permission->setUser($em->getRepository('AppBundle:User')->findOneById($request->request->get('user')));

      
        try {
            $em->flush();

            $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$permission]), Response::HTTP_CREATED);
        } catch (Exception $e) {
                  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
        }
              return $this->setBaseHeaders($response);

    }
     /**
     * Displays a form to edit an existing permission entity.
     *
     * @Route("/{id}/accept", name="permissions_accept")
     * @Method("PUT")
     */
    public function acceptAction(Request $request, Permission $permission)
    {

         $em = $this->getDoctrine()->getManager();
       
          $permission->setAccepted(true);
        try {
            $em->flush();

            $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$permission]), Response::HTTP_CREATED);
        } catch (Exception $e) {
                  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
        }
              return $this->setBaseHeaders($response);
    }

    /**
     * Deletes a permission entity.
     *
     * @Route("/{id}", name="permissions_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Permission $permission)
    {
         $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($permission);
            $em->flush();
    
            $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$permission]), Response::HTTP_CREATED);
        } catch (Exception $e) {
                  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
        }
              return $this->setBaseHeaders($response);
    }

          
    private function serialize($data)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        return $this->get('jms_serializer')->serialize($data, 'json', $context);
    }

    private function setBaseHeaders(Response $response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
