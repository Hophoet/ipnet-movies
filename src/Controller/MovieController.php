<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\LikeRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movies")
     */
    public function index(Request $request)
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
       /* $commentContent = $request->request->get('comment');
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
        */
        
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

    /**
     * @Route("/create/", name="create-movie")
     */
    public function create(Request $request)
    {
        //create a new movie object
        $movie = new Movie;
        //building of the form of the movie creation
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
         
        //
        if ($form->isSubmitted()){
            //get post data
            //building image file name
            $file = $request->files->get('movie')['image'];
            $movies_dir = $this->getParameter('movies_dir');
            $filename  = md5(uniqid()) . '.'. $file->guessExtension();
            $file->move(
                $movies_dir,
                $filename
            );
            $post = $request->request;
            $title =  $form['title']->getData();
            $description = $form['description']->getData();
            $voteAverage = $form['voteAverage']->getData();
            $releaseDate = $form['releaseDate']->getData();
            $runningTime = $form['runningTime']->getData();

            //set data to the movie object
            $movie->setImage($filename);
            $movie->setDescription($description);
            $movie->setVoteAverage($voteAverage);
            $movie->setReleaseDate($releaseDate);
            $movie->setRunningTime($runningTime);

            //echo "<pre>";
            //var_dump($movie); die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
            //redirection
            return $this->redirectToRoute('movies');  
        }

        return $this->render(
            'movies_admin/create-movie.html.twig',
            array('form' => $form->createView() )
        );

    }


    /**
     * like or unlike movie
     * 
     * @Route("/movie/{id}/like", name="movie_like")
     * @param Movie $movie
     * @param ObjectManager $manager
     * @param LikeRepository $likeRepository
     * @return void
     */
    public function movielike(Movie $movie, LikeRepository $likeRepository):
    HttpFoundationResponse
    {
        
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ]);

        if($movie->isLikedByUser($user)){
            $like = $likeRepository->findOneBy([
                'movie' => $movie,
                'user' => $user,
            ]);
            $manager->remove($like); 
            $manager->flush();
            $isLiked = $movie->isLikedByUser($user);
            return $this->json([
                'code' => 200,
                'message' => 'like remove',
                'likes' => $likeRepository->count(['movie' => $movie]),
                'isliked' => false
            ], 200);
            
        }

        $like = new Like;
        $like->setMovie($movie)
        ->setUser($user);
        
        $isLiked = $movie->isLikedByUser($user);
        $manager->persist($like);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Like is add',
            'likes' => $likeRepository->count(['movie' => $movie]),
            'isliked' => true
        ], 200); 

    }


      /**
     * like or unlike movie
     * 
     * @Route("/movie/{id}/comment", name="movie_comment")
     * @param Movie $movie
     * @param ObjectManager $manager
     * @param LikeRepository $likeRepository
     * @return void
     */
    public function movieComment(Request $request,  Movie $movie, LikeRepository $likeRepository):
    HttpFoundationResponse
    {
        
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ]);

        
        $commentContent = $request->request->get('commentContent');

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
            return $this->json([
                'code' => 200,
                'message' => 'comment added successfully!',
                'comment' => [
                    'user' => $user->getUsername(), 
                    'content' => $commentContent,
                    'date' => $date->format('Y-m-d'),
                    'movie' => $movie->getTitle()
                ]
            ], 200);

        }
        else{
            
        }
        return $this->json([
            'code' => 400,
            'message' => 'comment',
            'movie' => $movie->getTitle(),
            'query' => $request
        ], 200);
    }




}
