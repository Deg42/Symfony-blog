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
        // Obtenemos el gestor de entidades de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // obtenemos todas las apuestas
        $noticias = $entityManager->getRepository(Noticia::class)->findAll();

        return $this->render('blog/index.html.twig', array(
            'noticias' => $noticias,
        ));
    }

    function verNoticia($id)
    {

        if ((string)(int) $id != $id) {
            return $this->redirectToRoute('index');
        }
        // Obtenemos el gestor de entidades de Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        
        /* Obtenenemos el repositorio de Noticias y
           buscamos en el usando la id de la noticia */
        $noticia = $entityManager->getRepository(Noticia::class)->find($id);

        // Si la apuesta no existe lanzamos una excepción.
        if (!$noticia) {
            throw $this->createNotFoundException(
                'No existe ninguna noticia con id ' . $id
            );
        }

        return $this->render('blog/noticia.html.twig', [
            'noticia' => $noticia,
        ]);
    }

    function nuevaNoticia(Request $request)
    {
        $noticia = new Noticia();
        $form = $this->createFormBuilder($noticia)
            ->add('titulo', TextType::class)
            ->add('entradilla', TextareaType::class, array('required' => false))
            ->add('cuerpo', TextareaType::class, array('required' => false))
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'Añadir Noticia')
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // De esta manera podemos rellenar la variable
            // $noticia con los datos del formulario.
            $noticia = $form->getData();

            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();

            // Le decimos a doctrine que nos gustaría almacenar
            // el objeto de la variable en la base de datos
            $entityManager->persist($noticia);

            // Ejecuta las consultas necesarias
            $entityManager->flush();

            //Redirigimos a una página de confirmación.
            return $this->redirectToRoute('noticiaCreada');
        }


        return $this->render('blog/nuevaNoticia.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function noticiaCreada()
    {
        return $this->render('blog/noticiaCreada.html.twig');
    }

}
