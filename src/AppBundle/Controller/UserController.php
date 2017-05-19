<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Track;
use \AppBundle\Entity\Absence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
     * Lists all users entities.
     *
     * @Route("/me", name="user_self")
     * @Method("GET")
     */
public function getselfAction()
{
             
      

    $response = new Response($this->serialize(['type'=>"current user",'code'=>1,'user'=>$this->getUser()]), Response::HTTP_CREATED);
     
    return $this->setBaseHeaders($response);
}
  /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="s_user_show")
     * @Method("GET")
     */
public function showAction(User $user)
{
    $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);
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
   //  var_dump((new \ReflectionClass('AppBundle\Entity\User'))->getConstants());
 //die;
    $user->setRoles( array($request->request->get('role')=='admin'?'ROLE_ADMIN':User::ROLE_DEFAULT) ) ;
   
   
    try {
         $em->persist($user);
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);
    } catch (Exception $e) {
          $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
    }
      return $this->setBaseHeaders($response);
}
/**
     * Creates a new User entity.
     *
     * @Route("/{id}", name="users_new")
     * @Method("PUT")
     */
public function editAction(Request $request,User $user)
{
   // $um = $this->container->get('fos_user.user_manager');
    $em = $this->getDoctrine()->getManager();
    $user->setEmail($request->request->get('email')) ;
    $user->setUsername($request->request->get('username')) ;
    $user->setPlainPassword($request->request->get('password')) ;
    $user->setTrack($em->getRepository('AppBundle:Track')->findOneById($request->request->get('track')));
    $user->setEnabled(true) ;
   //  var_dump((new \ReflectionClass('AppBundle\Entity\User'))->getConstants());
 //die;
    $user->setRoles( array($request->request->get('role')=='admin'?'ROLE_ADMIN':User::ROLE_DEFAULT) ) ;
   
   
    try {
        
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);
    } catch (Exception $e) {
          $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
    }
      return $this->setBaseHeaders($response);
}
   
    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}/absence", name="user_absence")
     * @Method("GET")
     */
public function getabsenceAction(User $user)
{
        
    $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user->getAbsencetable()]), Response::HTTP_CREATED);
      return $this->setBaseHeaders($response);
}
      /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}/submitqr", name="user_submitqr")
     * @Method("POST")
     */
public function submitqrAction(Request $request,User $user)
{
    
    $cache = new FilesystemAdapter();
    $trackqrcode = $cache->getItem('qrcode.'. $user->getTrack()->getId());
    if ($trackqrcode->isHit() && $request->request->get('code')==$trackqrcode->get()['code']) {
        $em = $this->getDoctrine()->getManager();
        $abs = $em->getRepository('AppBundle:Absence')
                    
        ->createQueryBuilder('p')
        ->where('  p.date= :date and p.user = :user')   
        ->setParameter('date', (new \DateTime())->format('Y-m-d'))
        ->setParameter('user', $user->getId())
        ->getQuery()
        ->getResult();
        if (count($abs)>0) {
            $em->remove($abs[0]);
            $em->flush();
             $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>'scanned code is ok']), Response::HTTP_CREATED);
       
        }
        else
        {$response = new Response($this->serialize(['type'=>"sucess",'code'=>3,'data'=>'scanned code before']), Response::HTTP_CREATED);
    }   
    }
    else
        
       { $response = new Response($this->serialize(['type'=>"sucess",'code'=>2,'data'=>'error']), Response::HTTP_CREATED);}
       
        return $this->setBaseHeaders($response);
    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}/marks", name="users_show")
     * @Method("GET")
     */
    public function marksAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
         $rules = $em->getRepository('AppBundle:Rules')->findBy([], ['days' => 'ASC']);
        $absdays=count ($user->getAbsencetable());
        $zot=$total=$rules[count($rules)-1]->getMarks();
        $i=0;
        if ($absdays==0) {
            $total=$total;
        } elseif ($absdays>= $total) {
            $total=0;
        } else {
            while ($absdays>$rules[$i++]->getDays()) {
            }
            $total-=$rules[--$i]->getMarks();
            $total-=($rules[$i]->getMarks()-$rules[$i-1]->getMarks())/($rules[$i]->getDays()-$rules[$i-1]->getDays()) * ($absdays-$rules[$i]->getDays()) ;
        }
        $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>["days"=>$absdays,"total"=>$total,"fullmark"=>$zot]]), Response::HTTP_CREATED);
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
        try {
            $em->remove($user);
            $em->flush();
    
            $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$user]), Response::HTTP_CREATED);
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
