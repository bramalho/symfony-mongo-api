<?php

namespace App\Response;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(title="Product", required={"name"})
 */
class ProductResponse
{
    /**
     * @OA\Property()
     * @var string
     */
    public $uid;

    /**
     * @OA\Property()
     * @var string
     */
    public $name;

    /**
     * @OA\Property()
     * @var int
     */
    public $price;

    /**
     * @OA\Property()
     * @var CategoryResponse
     */
    public $category;
}
