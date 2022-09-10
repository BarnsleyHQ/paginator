<?php

use AlexBarnsley\Paginate;
use PHPUnit\Framework\TestCase;

class PaginateTest extends TestCase
{
    // private $collection;

    // protected function setUp()
    // {
    //     $this->collection = new Paginate([
    //         'test1',
    //         'test2',
    //         'test3',
    //         'test4',
    //         'test5',
    //     ]);
    // }

    public function testHandlesBasicImplementation()
    {
        $pagination = new Paginate([1, 2, 3]);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => true],
        ], $pagination->generateAsArray());
    }

    public function testPerPage()
    {
        $pagination = (new Paginate([1, 2, 3]))
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => true],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
        ], $pagination->generateAsArray());
    }

    public function testManyPagesWhileOnPageOne()
    {
        $pagination = (new Paginate(range(1, 47)))
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => true],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generateAsArray());
    }

    public function testManyPagesWhileOnLastPage()
    {
        $pagination = (new Paginate(range(1, 47)))
            ->onPage(47)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => true],
        ], $pagination->generateAsArray());
    }

    public function testManyPagesWhileOnMiddlePage()
    {
        $pagination = (new Paginate(range(1, 47)))
            ->onPage(25)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true],
            ['page' => 24, 'isCurrent' => false],
            ['page' => 25, 'isCurrent' => true],
            ['page' => 26, 'isCurrent' => false],
            ['isDelimiter' => true],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generateAsArray());
    }

    public function testShowingMorePagesEitherSide()
    {
        $pagination = (new Paginate(range(1, 47)))
            ->showingXPagesEitherSide(3)
            ->onPage(25)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true],
            ['page' => 22, 'isCurrent' => false],
            ['page' => 23, 'isCurrent' => false],
            ['page' => 24, 'isCurrent' => false],
            ['page' => 25, 'isCurrent' => true],
            ['page' => 26, 'isCurrent' => false],
            ['page' => 27, 'isCurrent' => false],
            ['page' => 28, 'isCurrent' => false],
            ['isDelimiter' => true],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generateAsArray());
    }
}
