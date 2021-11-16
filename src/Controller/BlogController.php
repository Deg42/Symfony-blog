<?php
// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Noticia;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    function index()
    {
        return $this->render('blog/index.html.twig');
    }

    function verNoticia($id)
    {

        if ((string)(int) $id != $id) {
            return $this->redirectToRoute('index');
        }

        return $this->render('blog/noticia.html.twig', [
            'id' => $id,
        ]);
    }

    function nuevaNoticia(){
        $noticia = new Noticia();
        $form = $this->createFormBuilder($noticia)
            ->add('titulo', TextType::class)
            ->add('entradilla', TextareaType::class, array('required' => false))
            ->add('cuerpo', TextareaType::class, array('required' => false))
            ->add('save', SubmitType::class,
                array('label' => 'Añadir Noticia'))
            ->getForm();

        return $this->render('blog/nuevaNoticia.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
