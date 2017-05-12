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

        return $this->render('branch/index.html.twig', array(
            'branches' => $branches,
        ));
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

              $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'branch'=>$branch]), Response::HTTP_CREATED);

}
catch(Exception $e){

  $response = new Response($this->serialize(['type'=>"failed",'code'=>2,"message"=>$e->getMessage()]), Response::HTTP_CREATED);
}
              return $this->setBaseHeaders($response);
       
     

        return $this->render('branch/new.html.twig', array(
            'branch' => $branch,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a branch entity.
     *
     * @Route("/{id}", name="branches_show")
     * @Method("GET")
     */
    public function showAction(Branch $branch)
    {
        $deleteForm = $this->createDeleteForm($branch);

        return $this->render('branch/show.html.twig', array(
            'branch' => $branch,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing branch entity.
     *
     * @Route("/{id}/edit", name="branches_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Branch $branch)
    {
        $deleteForm = $this->createDeleteForm($branch);
        $editForm = $this->createForm('AppBundle\Form\BranchType', $branch);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('branches_edit', array('id' => $branch->getId()));
        }

        return $this->render('branch/edit.html.twig', array(
            'branch' => $branch,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a branch entity.
     *
     * @Route("/{id}", name="branches_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Branch $branch)
    {
        $form = $this->createDeleteForm($branch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($branch);
            $em->flush();
        }

        return $this->redirectToRoute('branches_index');
    }

    /**
     * Creates a form to delete a branch entity.
     *
     * @param Branch $branch The branch entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Branch $branch)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('branches_delete', array('id' => $branch->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
