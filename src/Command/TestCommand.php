<?php

namespace App\Command;

use App\Document\Category;
use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';

    /** @var DocumentManager */
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct();

        $this->documentManager = $documentManager;
    }

    protected function configure()
    {
        $this->setDescription('Test command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Testing...');

//        $categories = [];
//        for ($i = 0; $i <= 1; $i++) {
//            $category = new Category();
//            $category->setUid(Uuid::uuid4()->toString());
//            $category->setName('Category ' . $i);
//            $this->documentManager->persist($category);
//            $categories[] = $category;
//        }
//
//        for ($i = 1; $i <= 10; $i++) {
//            $product = new Product();
//            $product->setUid(Uuid::uuid4()->toString());
//            $product->setName('Product ' . $i);
//            $product->setPrice(rand(100, 1000));
//            $product->setCategory($categories[$i % 2 == 0 ? 0 : 1]);
//            $this->documentManager->persist($product);
//        }

        $this->documentManager->flush();

        $io->writeln('Categories');
        $table = new Table($output);
        $table->setHeaders(['ID', 'UID', 'Name']);
        $categories = $this->documentManager->getRepository(Category::class)->findAll();
        foreach ($categories as $category) {
            $table->addRow([$category->getId(), $category->getUid(), $category->getName()]);
        }
        $table->render();

        $io->writeln('Products');
        $table = new Table($output);
        $table->setHeaders(['ID', 'UID', 'Name', 'Price', 'Category']);
        $products = $this->documentManager->getRepository(Product::class)->findAll();
        foreach ($products as $product) {
            $table->addRow([
                $product->getId(),
                $product->getUid(),
                $product->getName(),
                $product->getPrice(),
                $product->getCategory()->getName()
            ]);
        }
        $table->render();

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
