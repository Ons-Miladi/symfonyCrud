<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use AppBundle\Entity\Category;
class PostController extends Controller
{
   
     /**
     * @Route("/post", name="view_post_route")
     */
    public function viewPostAction(){
        $posts=$this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
       
      
        return $this->render('pages/index.html.twig',['posts'=>$posts]);
    }
/**
 * @Route("/post/create",name="create_post_route")
 */
    public function createPostAction(Request $request){
        $post=new Post;
        $form=$this->createFormBuilder($post)
        ->add('title',TextType::Class,array('attr'=>array('class'=>'form-control')))
        ->add('description',TextareaType::Class,array('attr'=>array('class'=>'form-control')))
        ->add('save',SubmitType::Class,array('label'=>'create','attr'=>array('class'=>'btn btn-primary')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $title=$form['title']->getData();
            $description=$form['description']->getData();
         
            $post->setTitle($title);
            $post->setDescription($description);
            $em=$this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('message','save sucesfully!');
            return $this->redirectToRoute('view_post_route');
        }
        return $this->render('pages/create.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/post/update/{id}",name="update_post_route")
     */
    public function updatePostAction($id,Request $request){
       $post=$this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
       $post->setTitle($post->getTitle());
       $post->setDescription($post->getDescription());
       $form=$this->createFormBuilder($post)
       ->add('title',TextType::Class,array('attr'=>array('class'=>'form-control')))
       ->add('description',TextareaType::Class,array('attr'=>array('class'=>'form-control')))
        ->add('save',SubmitType::Class,array('label'=>'Update','attr'=>array('class'=>'btn btn-primary')))
       ->getForm();
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
           $title=$form['title']->getData();
           $description=$form['description']->getData();
          
         
           $em=$this->getDoctrine()->getManager();
           $post=$em->getRepository('AppBundle:Post')->find($id);
           $post->setTitle($title);
           $post->setDescription($description);
           
           $em->flush();
           $this->addFlash('message','save sucesfully!');
           return $this->redirectToRoute('view_post_route');
       }
        return $this->render('pages/update.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/post/view/{id}",name="show_post_route")
     */
    public function showPostAction($id){
        $post=$this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
       
     return $this->render('pages/view.html.twig',['post'=>$post]);
    }
    /**
     * @Route("/post/delete/{id}",name="delete_post_route")
     */
    public function deletePostAction($id){
        $em=$this->getDoctrine()->getManager();
        $post=$em->getRepository("AppBundle:Post")->find($id);
        $em->remove($post);
        $em->flush();
        $this->addFlash('message','Deleted succesfully');
        return $this->redirectToRoute('view_post_route');
     
    }
     
}
