<?php

namespace App\Mapper;

use App\Response\CategoryResponse;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use App\Document\Category;

class CategoryMapper implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(Category::class, CategoryResponse::class);
    }
}
