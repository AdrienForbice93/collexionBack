<?php

namespace App\Controller\Api;

use App\Entity\MyCollection;
use App\Entity\User;
use App\Form\MyCollectionType;
use App\Repository\MyCollectionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// root URL for all routes from MyCollectionController
#[Route('/api')]
class MyCollectionController extends AbstractController
{
    /**
     * list all collections
     *
     * @param MyCollectionRepository $myCollectionRepository
     * @return Response
     */
    #[Route('/collections', name: 'api_my_collection_list',methods: ['GET'])]
    public function list(MyCollectionRepository $myCollectionRepository): Response
    {
        // retrieve all collections
        $collections = $myCollectionRepository->findAll();
        
        // check if $myCollection doesn't exist
        if (!$collections) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }

        // return json
        return $this->json(
            // what I want to show
            $collections,
            // status code
            200,
            // header
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_collections']
        );
    }

    /**
     * list one collection by its id
     *
     * @param MyCollectionRepository $myCollectionRepository
     * @return Response
     */
    #[Route('/collection/{id}', name: 'api_my_collection_show',methods: ['GET'])]
    public function show(MyCollection $myCollection): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }
        // return json
        return $this->json(
            // what I want to show
            $myCollection,
            // status code
            200,
            // header
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_collections']
            );
    } 
    
    /**
    * create one collection 
    *
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
   #[Route('/collection/create', name: 'api_my_collection_create',methods: ['POST'])]
   public function create(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
   {

    // retrieve user
    $user = $userRepository->find(5);

       $collection = new MyCollection();
       $collection->setUser($user);
       $collection->setName("Name");
       $collection->setImage("Image");
       $collection->setDescription("Description");
       $collection->setRating(3);

       $entityManager->persist($collection);
       $entityManager->flush();
       return $this->json([201, ['message' => 'create successful']]);

   }

    /**
    * update one collection
    *
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
    #[Route('/collection/update/{id}', name: 'api_my_collection_update',methods: ['PUT'])]
    public function update(MyCollection $myCollection = null, EntityManagerInterface $entityManager): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }
 
        $myCollection->setName("hrthsrths");
        $myCollection->setImage("hsrthsrth");
        $myCollection->setDescription("thsrthsrthszrth");
        $myCollection->setRating(5);
        $entityManager->flush();
 
        return $this->json(['message' => 'updated successful', 200]);
 
    }

    /**
    * delete one collection
    * 
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
    #[Route('/collection/delete/{id}', name: 'api_my_collection_delete', methods: ['DELETE'])]
    public function delete(MyCollection $myCollection = null, EntityManagerInterface $manager): Response
    {
         // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                ['message' => 'collection inexistant'],
                404,
                );
        }

        $manager->remove($myCollection);
        $manager->flush();

        return $this->json(['message' => 'delete successful', 200]);
       
    }
    
    // #[Route('/collection/random', name: 'api_my_collection_random', methods: ['GET'])]
    // public function random(MyCollectionRepository $myCollectionRepository)
    // {
    //     // retrieve a random collection
    //     $collection = $myCollectionRepository->getRandomMovie();
    //     return $this->json(
    //         $collection,
    //         200,
    //         [],
    //         ['groups' => ['get_collections']]
    //     );
    // }
    
}