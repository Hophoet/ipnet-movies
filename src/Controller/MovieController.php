<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\Movie1Type;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie-admin")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="movie_new")
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
            return $this->redirectToRoute('movie_index');  
        }

        return $this->render(
            'movie/new.html.twig',
            array('form' => $form->createView() )
        );

    }




















    /**
     * @Route("/{id}/show", name="movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }





    
    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Movie $movie): Response
    {
       
        //building of the form of the movie $
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
            return $this->redirectToRoute('movie_index');  
        }

        return $this->render(
            'movie/edit.html.twig',
            array('form' => $form->createView(), 'movie' => $movie )
        );

    }





    /**
     * @Route("/{id}/delete", name="movie_delete", methods={"GET"})
     */
    public function delete(Request $request, Movie $movie): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movie);
            $entityManager->flush();
    

        return $this->redirectToRoute('movie_index');
    }
}
