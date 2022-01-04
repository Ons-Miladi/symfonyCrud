<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoriesController extends Controller
{
    /**
     * @Route("/categ", name="view_categories_route")
     */
   
     public function viewCatgeories(){
     $categories=$this->getDoctrine()->getRepository("AppBundle:Category")->findAll();
  
      return $this->render('pages/categories/indexCateg.html.twig',['categories'=>$categories]);
  
      }
     
    /**
     * @Route("/categ/create",name="createCateg")
     */
    public function CreateCateg(Request $request){
      $categorie=new Category();
      $form=$this->createFormBuilder($categorie)
      ->add('titre',TextType::Class,array('attr'=>array('class'=>'form-control')))
      ->add('description',TextareaType::Class,array('attr'=>array('class'=>'form-control')))
      ->add('save',SubmitType::Class,array('label'=>'create','attr'=>array('class'=>'btn btn-primary')))
      ->getForm();
     $form->handleRequest($request);//permet de gérer le traitement de la saisie du formulaire
     if($form->isSubmitted() && $form->isValid()){
         $titre=$form['titre']->getData();
         $description=$form['description']->getData();
         $categorie->setTitre($titre);
         $categorie->setDescription($description);
         $em=$this->getdoctrine()->getManager();
         $em->persist($categorie);
         $em->flush();
         $this->addFlash('message','save Successfully');
         return $this->redirectToRoute('view_categories_route');
         
     }


        return $this->render('pages/categories/create.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/categ/update/{id}",name="updateCateg")
     */
    public function updateCateg(Request $request,$id){
        $categ=$this->getdoctrine()->getRepository("AppBundle:Category")->find($id);
        $categ->setTitre($categ->getTitre());
        $categ->setDescription($categ->getTitre());
        $form=$this->createFormBuilder($categ)
        ->add('titre',TextType::Class,array('attr'=>array('class'=>'form-control')))
        ->add('description',TextareaType::Class,array('attr'=>array('class'=>'form-control')))
        ->add('save',SubmitType::Class,array('label'=>'create','attr'=>array('class'=>'btn btn-primary')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $titre=$form['titre']->getData();
          $description=$form['description']->getData();
          $em=$this->getdoctrine()->getManager();
          $categ=$em->getRepository("AppBundle:Category")->find($id);
          $categ->setTitre($titre);
          $categ->setDescription($description);
          $em->flush();
          $this->addFlash('message','save Successfully');
          return $this->redirectToRoute('view_categories_route');
        }
        
        return $this->render('pages/categories/update.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/categ/delete/{id}",name="deleteCateg")
     */
    public function deleteCateg($id){
        $em=$this->getdoctrine()->getManager();
        $categ=$em->getRepository('AppBundle:Category')->find($id);
        $em->remove($categ);
        $em->flush();
        $this->addFlash('message','delete successfully');

        return $this->redirectToRoute('view_categories_route');
    }
    /**
     * @Route("/categ/view/{id}",name="viewcateg")
     */
    public function viewCateg($id){
        $categ=$this->getdoctrine()->getRepository('AppBundle:Category')->find($id);
  
        return $this->render('pages/categories/view.html.twig',['categ'=>$categ]);
    }
}
