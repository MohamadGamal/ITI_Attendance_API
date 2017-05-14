<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Branch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
use Exception;
/**
 * Branch controller.
 *
 * @Route("branches")
 */
class BranchController extends Controller
{
    /**
     * Lists all branch entities.
     *
     * @Route("/", name="branches_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $branches = $em->getRepository('AppBundle:Branch')->findAll();

          $response = new Response($this->serialize(['type'=>"Branch List",'code'=>1,'data'=>$branches]), Response::HTTP_CREATED);
             return $this->setBaseHeaders($response);
    }

    /**
     * Creates a new branch entity.
     *
     * @Route("/", name="branches_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
           $em = $this->getDoctrine()->getManager();
        //      var_dump($request->request->get('array'));
        // die;

        $branch = new Branch();
        $branch->setName($request->request->get('name'));
        $branch->setCode($request->request->get('code'));
        $branch->setAddress($request->request->get('address'));
try{
$em->persist($branch);
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$branch]), Response::HTTP_CREATED);

}
catch(Exception $e){

  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
}
              return $this->setBaseHeaders($response);
       
    
    }

    /**
     * Finds and displays a branch entity.
     *
     * @Route("/{id}", name="branches_show")
     * @Method("GET")
     */
    public function showAction(Branch $branch)
    {
           $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$branch]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response);
    }

    /**
     * Displays a form to edit an existing branch entity.
     *
     * @Route("/{id}", name="branches_edit")
     * @Method("PUT")
     */
    public function editAction(Request $request, Branch $branch)
    {
       
       $branch->setName($request->request->get('name'));
        $branch->setCode($request->request->get('code'));
        $branch->setAddress($request->request->get('address')); 
 var_dump($branch);
        
        die();
        try{
$em->persist($branch);
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$branch]), Response::HTTP_CREATED);

}
catch(Exception $e){

  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
}
              return $this->setBaseHeaders($response);
       
    }

    /**
     * Deletes a branch entity.
     *
     * @Route("/{id}", name="branches_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Branch $branch)
    {
     
            $em = $this->getDoctrine()->getManager();
          try{
            $em->remove($branch);
            $em->flush();
    
              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$branch]), Response::HTTP_CREATED);

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
