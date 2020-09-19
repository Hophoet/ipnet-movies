<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
   * @Route("/admin")
 */
class MoviesAdminController extends AbstractController
{
    /**
     * @Route("/index/", name="movies_admin")
     */
    public function index()
    {
        return $this->render('movies_admin/index.html.twig', [
            'controller_name' => 'MoviesAdminController',
        ]);
    }

    
}

