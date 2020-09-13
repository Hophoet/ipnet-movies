<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use DateTime;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movies")
     */
    public function index( Request $request)
    {
        //get all movies
        $movies = $this->getDoctrine()->getRepository('App:Movie')->findAll();

        //get current user
        $user = $this->getUser();
        if($user){
            //user connected case
            $username = $user->getUsername();
        }
        else{
            //user not connected case
            return $this->redirectToRoute('fos_user_security_login');
        }
       
        
    
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }
     /**
     * @Route("/movie/{id}/", name="movie")
     */
    public function movie(Request $request, $id)
    {   
        //ge the movie
        $movie = $this->getDoctrine()->getRepository('App:Movie')->find($id);
        if($movie == null){
            return $this->render('movie/404.html.twig', []);
        }
        //get current user
        $user = $this->getUser();
        if($user){
            //user connected case
            $username = $user->getUsername();
        }
        else{
            //user not connected case
            return $this->redirectToRoute('fos_user_security_login');
        }
        $commentContent = $request->request->get('comment');
        if($commentContent){
            //print_r($comment);
            //print_r($user);
            $date = new DateTime();
            $likeNumber = 0;
            $dislikeNumber = 0;
            
            $comment = new Comment;
            $comment->setUser($user);
            $comment->setContent($commentContent);
            $comment->setDate($date);
            $comment->setLikeNumber($likeNumber);
            $comment->setDislikeNumber($dislikeNumber);
            $comment->setMovie($movie);

            //get manager
            $manager = $this->getDoctrine()->getManager();
            //saving in to the database
            $manager->persist($comment);
            $manager->flush();


        }
        else{
            
        }
        
        //$methods = get_class_methods($movie->getReleaseDate()) ;
        $releaseDate = $movie->getReleaseDate()->format('Y-m-d');

        return $this->render('movie/movie.html.twig', [
            'movie' => $movie,
            'releaseDate' => $releaseDate,
        ]);
    }

    /**
     * @Route("/searchMovies/", name="searchMovies")
     */
    public function searchMovies( Request $request)
    {
        $query = $request->request->get('query');
        if($query){
            $searchMovies =  $this->getDoctrine()->getRepository('App:Movie')->findBy(['title'=>$query]);
            if($searchMovies){
                return $this->render('movie/search.html.twig', ['searchMovies' => $searchMovies]);
            }
            else{
               //movies not found with the query
            }

        }
        else{
            //query not send 
        }

        return $this->render('movie/404.html.twig', []);
        
    }

}
