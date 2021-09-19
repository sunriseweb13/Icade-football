<?php

declare(strict_types=1);

namespace App\Controller;

//use App\Api\ApiSearch;
use App\Api\ApiSearchInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private ApiSearchInterface $search;

    public function __construct(ApiSearchInterface $search)
    {
        $this->search = $search;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request): Response
    {
		$filter = [];
		$filter['leagues'] = $this->search->search('leagues', ['country' => 'France']);

		$q = $request->query->all();
	
		if(0 === count($q)) {
			$q = [
				'league' => reset($filter['leagues'])['league']['id'],
				'season' => date('Y'),
			];
		}
		
		if (isset($q['league']) && isset($q['season'])) {
            $filter['rounds'] = $this->search->search('fixtures/rounds', $q);
        }
		
		if(!isset($q['round'])) {
			$q['round'] = reset($filter['rounds']);
		}

		$results = $this->search->search('fixtures', $q);
		
		$data = [];
	
		foreach($results as $response) {
			if($q['round'] === $response['league']['round']) {
				$data[] = $response;
			}
		}
   
		usort($data, static function ($a, $b){
			$t1 = $a['fixture']['timestamp'];
			$t2 = $b['fixture']['timestamp'];
			
			return $t1 - $t2;
		});
		
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
			'results' => $data,
			'filter' => $filter
        ]);
    }
	
	/**
     * @Route("/details/{id<\d+>}", name="app_details", requirements={"id"="\d+"})
     */
    public function details(int $id): Response
    {
		$results = $this->search->search('fixtures', ['id' => $id]);

        return $this->render('app/details.html.twig', [
			'result' => reset($results),
        ]);
	}
}
