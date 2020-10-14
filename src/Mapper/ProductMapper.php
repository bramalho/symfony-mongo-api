<?php

namespace App\Mapper;

use App\Document\Product;
use App\Response\CategoryResponse;
use App\Response\ProductResponse;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class ProductMapper implements AutoMapperConfiguratorInterface
{
    /** @var AutoMapperInterface */
    private $mapper;

    public function __construct(AutoMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(Product::class, ProductResponse::class)
            ->forMember('category', function (Product $product) {
                return $this->mapper->map($product->getCategory(), CategoryResponse::class);
            });
    }
}
