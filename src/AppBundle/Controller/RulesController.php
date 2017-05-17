<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rules;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
/**
 * Rule controller.
 *
 * @Route("rules")
 */
class RulesController extends Controller
{
    /**
     * Lists all rule entities.
     *
     * @Route("/", name="rules_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rule = $em->getRepository('AppBundle:Rules')->findAll();

          $response = new Response($this->serialize(['type'=>"Rules List",'code'=>1,'data'=>$rule]), Response::HTTP_CREATED);
             return $this->setBaseHeaders($response);
    }

    /**
     * Creates a new rule entity.
     *
     * @Route("/", name="rules_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $rule = new Rules();
        $em = $this->getDoctrine()->getManager();
        //      var_dump($request->request->get('array'));
        // die;

    
        $rule->setDays($request->request->get('days'));
         $rule->setMarks($request->request->get('marks'));
      



         try{
$em->persist($rule);
            $em->flush();

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$rule]), Response::HTTP_CREATED);

}
catch(Exception $e){

  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
}
              return $this->setBaseHeaders($response);
       
    }
    /**
     * Finds and displays a rule entity.
     *
     * @Route("/{id}", name="rules_show")
     * @Method("GET")
     */
    public function showAction(Rules $rule)
    {
         $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$rule]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response);
    }

    /**
     * Displays a form to edit an existing rule entity.
     *
     * @Route("/{id}/edit", name="rules_edit")
     * @Method("PUT")
     */
    public function editAction(Request $request, Rules $rule)
    {
       
    }

    /**
     * Deletes a rule entity.
     *
     * @Route("/{id}", name="rules_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Rules $rule)
    {
      $em = $this->getDoctrine()->getManager();
          try{
            $em->remove($rule);
            $em->flush();
    
              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$rule]), Response::HTTP_CREATED);

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
