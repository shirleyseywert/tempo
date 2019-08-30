<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function listUsersAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:User');
        
        $listUsersQuery = $repository->findAll();
             
        return $this->render('UserBundle::listUsers.html.twig', array('listUsers' => $listUsersQuery));
    }
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function promoteUserAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($id);
        $currentRole = $user->getHigherRole();
        $newRole = $user->upRole();

        $userManager = $this->get('fos_user.user_manager');
        $user->removeRole($currentRole);
        $user->addRole($newRole);
        $userManager->updateUser($user);
        
        if($currentRole == $newRole)
        {
            $this->addFlash('success', 'Utilisateur a déjà tous les droits');
        }
        else
        {
            $this->addFlash('success', 'Utilisateur promu');            
        }
        
        Return $this->redirectToRoute('listusers');        
    }
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function demoteUserAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($id);
        $currentRole = $user->getHigherRole();
        $newRole = $user->downRole();
        
        $userManager = $this->get('fos_user.user_manager');
        $user->removeRole($currentRole);
        $user->addRole($newRole);
        $userManager->updateUser($user);
        
        if($currentRole == $newRole)
        {
            $this->addFlash('success', 'Utilisateur est déjà au minimum de droits');
        }
        else
        {
            $this->addFlash('success', 'Utilisateur destitué');            
        }
        
        Return $this->redirectToRoute('listusers');        
    }
    
    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function activateUserAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($id);
        $locked = $user->isEnabled();
        
        if($locked == 0)
        {
            $user->setEnabled(1);
            $this->addFlash('success', 'L\'utilisateur est actif');
        }
        else
        {
            $user->setEnabled(0);
            $this->addFlash('success', 'L\'utilisateur est bloqué');
        }
        
        $userManager = $this->get('fos_user.user_manager');
        $userManager->updateUser($user);
        
        return $this->redirect($this->generateUrl('listusers'));
    }   
    
}