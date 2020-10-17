<?php

namespace App\Controller\API;

use App\Response\ProductResponse;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Document\Product;

/**
 * @Route("/api", name="api_")
 */
class ProductController extends AbstractController
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
     * @Route("/products", name="products", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="List of Products",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=ProductResponse::class))
     *     )
     * )
     * @OA\Tag(name="productss")
     */
    public function getCategories()
    {
        return $this->json(
            $this->mapper->mapMultiple(
                $this->documentManager->getRepository(Product::class)->findAll(),
                ProductResponse::class
            )
        );
    }

    /**
     * @Route("/products/{uid}", name="product", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Get Product by UID",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(ref=@Model(type=ProductResponse::class))
     *     )
     * )
     * @OA\Tag(name="productss")
     */
    public function getProductByUID(string $uid)
    {
        return $this->json(
            $this->mapper->map(
                $this->documentManager->getRepository(Product::class)->findOneBy(['uid' => $uid]),
                ProductResponse::class
            )
        );
    }
}
