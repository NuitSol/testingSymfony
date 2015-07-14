<?php

namespace ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ApplicationBundle\Entity\PostComment;
use ApplicationBundle\Form\PostCommentType;

/**
 * PostComment controller.
 *
 * @Route("/postcomment")
 */
class PostCommentController extends Controller
{

    /**
     * Lists all PostComment entities.
     *
     * @Route("/", name="postcomment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationBundle:PostComment')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new PostComment entity.
     *
     * @Route("/(post_id)", name="postcomment_create")
     * @Method("POST")
     * @Template("ApplicationBundle:PostComment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new PostComment();
        $postId = $request->get('post_id');
        $post = $this->getDoctrine()->getRepository('ApplicationBundle:Post')->find($postId);
        $entity->setPost($post);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();


        }

        return $this->redirect($this->generateUrl('post_show', array('id' => $entity->getPost()->getId())));
    }

    /**
     * Creates a form to create a PostComment entity.
     *
     * @param PostComment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PostComment $entity)
    {
        $form = $this->createForm(new PostCommentType(), $entity, array(
            'action' => $this->generateUrl('postcomment_create', array('post_id'=>$entity->getPost()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PostComment entity.
     *
     * @Route("/new", name="postcomment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PostComment();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a PostComment entity.
     *
     * @Route("/{id}", name="postcomment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationBundle:PostComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PostComment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PostComment entity.
     *
     * @Route("/{id}/edit", name="postcomment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationBundle:PostComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PostComment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a PostComment entity.
    *
    * @param PostComment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PostComment $entity)
    {
        $form = $this->createForm(new PostCommentType(), $entity, array(
            'action' => $this->generateUrl('postcomment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PostComment entity.
     *
     * @Route("/{id}", name="postcomment_update")
     * @Method("PUT")
     * @Template("ApplicationBundle:PostComment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationBundle:PostComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PostComment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('postcomment_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a PostComment entity.
     *
     * @Route("/{id}", name="postcomment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationBundle:PostComment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PostComment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('postcomment'));
    }

    /**
     * Creates a form to delete a PostComment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postcomment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
