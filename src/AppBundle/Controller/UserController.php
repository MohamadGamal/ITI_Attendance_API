<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Post controller.
 *
 * @Route("users")
 */
class UserController extends Controller
{
    
    /**
     * Lists all users entities.
     *
     * @Route("/", name="posts_index")
     * @Method("GET")
     */
    public function indexAction()
    {
             $this->getUser();
        $em = $this->getDoctrine()->getManager();
     $users = $em->getRepository('AppBundle:User')
                ->createQueryBuilder('u')
                ->select('u.id,u.username,u.email,u.enabled,u.lastLogin')
                ->getQuery()
                ->getResult();

         $response = new Response($this->serialize(['type'=>"user List",'code'=>1,'users'=>$users]), Response::HTTP_CREATED);
     
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
