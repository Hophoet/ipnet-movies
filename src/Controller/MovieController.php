<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;


class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movies")
     */
    public function index( Request $request)
    {
        //get all movies
        $movies = $this->getDoctrine()->getRepository('App:Movie')->findAll();
      
        
    
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }
     /**
     * @Route("/movie/{id}/", name="movie")
     */
    public function movie(Request $request, $id)
    {   
        $movie = $this->getDoctrine()->getRepository('App:Movie')->find($id);
        return $this->render('movie/movie.html.twig', [
            'movie' => $movie
        ]);
    }
}
