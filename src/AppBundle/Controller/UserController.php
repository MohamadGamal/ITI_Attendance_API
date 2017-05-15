<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use  AppBundle\Entity\Track;
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
                ->leftJoin('u.track', 'h')
                ->select('u.id,u.username,u.email,u.enabled,u.lastLogin,h.id AS trackid')
                ->getQuery()
                ->getResult();

         $response = new Response($this->serialize(['type'=>"user List",'code'=>1,'users'=>$users]), Response::HTTP_CREATED);
     
        return $this->setBaseHeaders($response);
    }


  
/**
     * Creates a new User entity.
     *
     * @Route("/", name="users_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
       // $um = $this->container->get('fos_user.user_manager');
        $em = $this->getDoctrine()->getManager();
        $user =new User();// $um->createUser();
         $user->setEmail($request->request->get('email')) ;
    $user->setUsername($request->request->get('username')) ;
    $user->setPlainPassword($request->request->get('password')) ;
     $user->setTrack($em->getRepository('AppBundle:Track')->findOneById($request->request->get('track')));
    $user->setEnabled(true) ;
   $user->setRoles( array($request->request->get('role')=='admin'?User::ROLE_SUPER_ADMIN:User::ROLE_DEFAULT) ) ;
  //  var_dump((new \ReflectionClass('AppBundle\Entity\User'))->getConstants());
   
try{
 $em->persist($user);
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);

}
catch(Exception $e){

  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
}
              return $this->setBaseHeaders($response);
       
    
    }
   /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="users_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
       $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response); 
    }

        /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="users_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {


            $em = $this->getDoctrine()->getManager();
          try{
            $em->remove($user);
            $em->flush();
    
              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);

}
catch(Exception $e){

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
