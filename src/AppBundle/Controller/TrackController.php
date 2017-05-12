<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Track;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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

          $response = new Response($this->serialize(['type'=>"track List",'code'=>1,'tracks'=>$tracks]), Response::HTTP_CREATED);
     
    }

    /**
     * Creates a new track entity.
     *
     * @Route("/", name="tracks_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        var_dump($request);
        die;
        $track = new Track();
        $form = $this->createForm('AppBundle\Form\TrackType', $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($track);
            $em->flush();

            return $this->redirectToRoute('tracks_show', array('id' => $track->getId()));
        }

        return $this->render('track/new.html.twig', array(
            'track' => $track,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a track entity.
     *
     * @Route("/{id}", name="tracks_show")
     * @Method("GET")
     */
    public function showAction(Track $track)
    {
        $deleteForm = $this->createDeleteForm($track);

        return $this->render('track/show.html.twig', array(
            'track' => $track,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing track entity.
     *
     * @Route("/{id}/edit", name="tracks_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Track $track)
    {
        $deleteForm = $this->createDeleteForm($track);
        $editForm = $this->createForm('AppBundle\Form\TrackType', $track);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tracks_edit', array('id' => $track->getId()));
        }

        return $this->render('track/edit.html.twig', array(
            'track' => $track,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a track entity.
     *
     * @Route("/{id}", name="tracks_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Track $track)
    {
        $form = $this->createDeleteForm($track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($track);
            $em->flush();
        }

        return $this->redirectToRoute('tracks_index');
    }

    /**
     * Creates a form to delete a track entity.
     *
     * @param Track $track The track entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Track $track)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tracks_delete', array('id' => $track->getId())))
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
