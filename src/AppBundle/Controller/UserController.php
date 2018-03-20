<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * List users route
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/users", name="user_list")
     * @Method({"GET"})
     */
    public function listAction()
    {
        // Fetch all users and render user_list
        return $this->render(
            'user/list.html.twig',
            ['users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll()]
        );
    }

    /**
     * Create user route
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/users/create", name="user_create")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        // Create User and UserForm
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // Handle the request
        $form->handleRequest($request);

        // If form submitted
        if($form->isSubmitted()){
            // Check if form values are valids
            if ($form->isValid()) {

                // Get manager and persist new user
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // Add flash message
                $this->addFlash('success', "L'utilisateur a bien été ajouté.");

                // Redirect to user_list
                return $this->redirectToRoute('user_list');
            }
        }

        // If form non-submitted or non-valid, render user_create
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit user route
     *
     * @param User $user        User related to {id}
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/users/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(User $user, Request $request)
    {
        // Create Form with autowired User
        $form = $this->createForm(UserType::class, $user);

        // Handle request
        $form->handleRequest($request);

        // Check if form was submitted
        if($form->isSubmitted()) {
            // Check if datas are valid
            if ($form->isValid()) {

                // Flush modifications
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                // Add success flash message
                $this->addFlash('success', "L'utilisateur a bien été modifié");

                // Redirect to user_list
                return $this->redirectToRoute('user_list');
            }
        }

        // If form non-submitted or non-valid, render user_edit with hydrated form
        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user
            ]
        );
    }
}
