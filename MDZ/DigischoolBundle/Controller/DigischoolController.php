<?php

namespace MDZ\DigischoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MDZ\DigischoolBundle\Entity\{
	User,
	Movie,
	UserMovie
};
use Symfony\Component\{
	HttpFoundation\Response,
	HttpFoundation\Request,
	HttpFoundation\RedirectResponse,
	HttpFoundation\JsonResponse
};

class DigischoolController extends Controller
{
	
	/**
	* Vote for a movie by an user..
	*
	* @param object $request
	* @param int $userId user's id
	* @param int $movieId movie's id
	*/
	final public function movieVoteAction(Request $request, int $userId, int $movieId)
	{
		if($request->isMethod('POST')){
			$data = $this->getUserAndMovie(userId, movieId);
			$voteCounter = $this->getDoctrine()
				->getManager()
				->getRepository('MDZDigischoolBundle:MovieUser')
				->find(['id'=>$userId])
			$numberOfVote = $voteCounter->voteCounter(userId);
			if($numberOfVote < 3){
				$this->registerUserVote(data['user'], $data['movie']);
				$request->getSession()->getFlashBag()
					->add('notice', 'Votre vote a bien été pris en compte.');
			}
		}
		
		return new JSONResponse(['user'=>$data['user'], 'movie'=>$data['movie']]);
	}
	
	/**
	* register an user's vote
	*
	* @param object $user User instance
	* @param object $movie Movie instance
	* @return void
	*/
	final private function registerUserVote(User $user, Movie, $movie) :void{
		$vote = new UserMovie();
		$vote->setUser($user);
		$vote->setMovie($movie);
		$movie->setScore($movie->getScore() + 1); //Increment movie score 
		$em = $this->getDoctrine()->getManager();
		$em->persist($vote);
		$em->persist($movie);
		$em->flush();
	}
	
	/**
	* Delete an user's vote
	*
	* @param object $request
	* @param int $userId user's id
	* @param int $movieId movie's id
	*/
	final public function movieDeleteAction(Request $request, int $userId, int $movieId)
	{
		if($request->isMethod('DELETE')){
			$data = $this->getUserAndMovie(userId, movieId);
			$userMovie = $this->getDoctrine()
				->getManager()
				->getRepository('MDZDigischoolBundle:UserMovie')
				->findOneBy(['user_id'=>$userId, 'movie_id'=>$movieId]);
			$em = $this->getDoctrine()->getManager();
			$em->remove($userMovie);
			$movie->setScore($movie->getScore() - 1); //remove 1 to the movie's score
			$em->persist($movie);
			$em->flush();
		}
		
		return new JSONResponse(['user'=>$data['user'], 'movie'=>$data['movie']]);
	}
	
	/**
	* Return a list of Movie upvoted by a specific User
	*
	* @param object $request
	* @param int $userId user's id
	*/
	final public function movieListAction(Request $request, int $userId)
	{
		if($request->isMethod('GET')){
			$userMovie = $this->getDoctrine()
				->getManager()
				->getRepository('MDZDigischoolBundle:UserMovie')
				->getMoviesByUser();
		}
		
		return new JsonResponse(['user_movie'=>$userMovie]);
	}	
	
	/**
	* Return a list of User that have upvoted a specific movie
	*
	* @param object $request
	* @param int $movieId movie's id
	*/
	final public function userListAction(Request $request, int $movieId)
	{
		if($request->isMethod('GET')){
			$userMovie = $this->getDoctrine()
				->getManager()
				->getRepository('MDZDigischoolBundle:UserMovie')
				->getUsersByMovie();
		}
		
		return new JsonResponse(['user_movie'=>$userMovie]);
	}
	
	/**
	* Return User and Movie entity find by given ids
	*
	* @param int $userId user's id
	* @param int $movieId movie's id
	* @return array
	*/
	final private function getUserAndMovie(int $userId, int $movieId):array {
		$user = $this->getDoctrine()
			->getManager()
			->getRepository('MDZDigischoolBundle:User')
			->findOneBy(['id'=>$userId]);
		$movie = $this->getDoctrine()
			->getManager()
			->getRepository('MDZDigischoolBundle:Movie')
			->findOneBy(['id'=>$movieId]);
			
		return ['user' => $user, 'movie' => $movie];
	}
	
	/**
	* Return the movie that has got the much number of vote
	*/
	final private function getPreferedMovie(){
		$movie = $this->getDoctrine()
			->getManager()
			->getRepository('MDZDigischoolBundle:Movie')
			->getMaxVotes();
			
		return new JSONResponse(['movie'=>$movie]);
	}

}
