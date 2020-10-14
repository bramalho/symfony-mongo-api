<?php

namespace App\Response;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(title="Category", required={"name"})
 */
class CategoryResponse
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
}
