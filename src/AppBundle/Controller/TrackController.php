<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Track;

use AppBundle\Entity\Branch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
/**
 * Track controller.
 *
 * @Route("tracks")
 */
class TrackController extends Controller
{
    /**
     * Lists all track entities.
     *
     * @Route("/", name="tracks_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tracks = $em->getRepository('AppBundle:Track')->findAll();

          $response = new Response($this->serialize(['type'=>"track List",'code'=>1,'data'=>$tracks]), Response::HTTP_CREATED);
        return $this->setBaseHeaders($response);
    }

    /**
     * Creates a new track entity.
     *
     * @Route("/", name="tracks_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        
               $em = $this->getDoctrine()->getManager();

        //      var_dump($request->request->get('array'));
        // die;
        //
       

        $track = new Track();
       
        $track->setName($request->request->get('name'));
        $track->setCode($request->request->get('code'));
        $track->setBranch($em->getRepository('AppBundle:Branch')->findOneById($request->request->get('branch')));
          
try{
$em->persist($track);
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$track]), Response::HTTP_CREATED);

}
catch(Exception $e){

  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
}
              return $this->setBaseHeaders($response);
       
    
    }

    /**
     * Finds and displays a track entity.
     *
     * @Route("/{id}", name="tracks_show")
     * @Method("GET")
     */
    public function showAction(Track $track)
    {
       $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$track]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response); 
    }

    /**
     * Displays a form to edit an existing track entity.
     *
     * @Route("/{id}/edit", name="tracks_edit")
     * @Method("PUT")
     */
    public function editAction(Request $request, Track $track)
    {
       
    }

    /**
     * Deletes a track entity.
     *
     * @Route("/{id}", name="tracks_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Track $track)
    {


            $em = $this->getDoctrine()->getManager();
          try{
            $em->remove($track);
            $em->flush();
    
              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$track]), Response::HTTP_CREATED);

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
