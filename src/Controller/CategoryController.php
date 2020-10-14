<?php

namespace App\Controller;

use App\Response\CategoryResponse;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Document\Category;

class CategoryController extends AbstractController
{
    /** @var DocumentManager */
    private $documentManager;

    /** @var AutoMapperInterface */
    private $mapper;

    public function __construct(DocumentManager $documentManager, AutoMapperInterface $mapper)
    {
        $this->documentManager = $documentManager;
        $this->mapper = $mapper;
    }

    /**
     * @Route("/api/categories", name="categories", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="List of Categories",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=CategoryResponse::class))
     *     )
     * )
     * @OA\Tag(name="categories")
     */
    public function index()
    {
        return $this->json(
            $this->mapper->mapMultiple(
                $this->documentManager->getRepository(Category::class)->findAll(),
                CategoryResponse::class
            )
        );
    }
}
