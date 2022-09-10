<?php

use AlexBarnsley\Paginator\Paginator;
use PHPUnit\Framework\TestCase;

class PaginateTest extends TestCase
{
    public function testHandlesBasicImplementation()
    {
        $pagination = new Paginator([1, 2, 3]);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => true],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testPerPage()
    {
        $pagination = (new Paginator([1, 2, 3]))
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => true],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testManyPagesWhileOnPageOne()
    {
        $pagination = (new Paginator(range(1, 47)))
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => true],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testManyPagesWhileOnLastPage()
    {
        $pagination = (new Paginator(range(1, 47)))
            ->onPage(47)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => true],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testManyPagesWhileOnMiddlePage()
    {
        $pagination = (new Paginator(range(1, 47)))
            ->onPage(25)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 24, 'isCurrent' => false],
            ['page' => 25, 'isCurrent' => true],
            ['page' => 26, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testShowingMorePagesEitherSide()
    {
        $pagination = (new Paginator(range(1, 47)))
            ->showPagesEitherSide(3)
            ->onPage(25)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 22, 'isCurrent' => false],
            ['page' => 23, 'isCurrent' => false],
            ['page' => 24, 'isCurrent' => false],
            ['page' => 25, 'isCurrent' => true],
            ['page' => 26, 'isCurrent' => false],
            ['page' => 27, 'isCurrent' => false],
            ['page' => 28, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testShowingMorePagesAtEnds()
    {
        $pagination = (new Paginator(range(1, 47)))
            ->showPagesAtEnds(5)
            ->onPage(25)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['page' => 4, 'isCurrent' => false],
            ['page' => 5, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 24, 'isCurrent' => false],
            ['page' => 25, 'isCurrent' => true],
            ['page' => 26, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => '...'],
            ['page' => 43, 'isCurrent' => false],
            ['page' => 44, 'isCurrent' => false],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generate()->stepsAsArray());
    }

    public function testOverridingDelimiter()
    {
        $pagination = (new Paginator(range(1, 47)))
            ->withDelimiter(' - ')
            ->onPage(25)
            ->perPage(1);

        $this->assertEquals([
            ['page' => 1, 'isCurrent' => false],
            ['page' => 2, 'isCurrent' => false],
            ['page' => 3, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => ' - '],
            ['page' => 24, 'isCurrent' => false],
            ['page' => 25, 'isCurrent' => true],
            ['page' => 26, 'isCurrent' => false],
            ['isDelimiter' => true, 'content' => ' - '],
            ['page' => 45, 'isCurrent' => false],
            ['page' => 46, 'isCurrent' => false],
            ['page' => 47, 'isCurrent' => false],
        ], $pagination->generate()->stepsAsArray());
    }
}
