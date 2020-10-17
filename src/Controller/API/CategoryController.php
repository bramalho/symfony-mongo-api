<?php

namespace App\Controller\API;

use App\Document\Product;
use App\Response\CategoryResponse;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Document\Category;
use App\Response\ProductResponse;

/**
 * @Route("/api", name="api_")
 */
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
     * @Route("/categories", name="categories", methods={"GET"})
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
    public function getCategories()
    {
        return $this->json(
            $this->mapper->mapMultiple(
                $this->documentManager->getRepository(Category::class)->findAll(),
                CategoryResponse::class
            )
        );
    }

    /**
     * @Route("/categories/{uid}", name="category", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Get Category by UID",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(ref=@Model(type=CategoryResponse::class))
     *     )
     * )
     * @OA\Tag(name="categories")
     */
    public function getCategoryByUID(string $uid)
    {
        return $this->json(
            $this->mapper->map(
                $this->documentManager->getRepository(Category::class)->findOneBy(['uid' => $uid]),
                CategoryResponse::class
            )
        );
    }

    /**
     * @Route("/categories/{uid}/products", name="categoryProducts", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Get Category Products by Category UID",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Property(ref=@Model(type=ProductResponse::class))
     *     )
     * )
     * @OA\Tag(name="categories")
     */
    public function getCategoryProductsByUID(string $uid)
    {
        $category = $this->documentManager->getRepository(Category::class)->findOneBy(['uid' => $uid]);

        $query = $this->documentManager
            ->getRepository(Product::class)
            ->createQueryBuilder()
            ->field('category')
            ->references($category)
            ->getQuery();
        $products = $query->execute()->toArray();

        return $this->json($this->mapper->mapMultiple($products, ProductResponse::class));
    }
}
