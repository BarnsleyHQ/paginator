<?php

namespace AlexBarnsley\Paginator;

class PagesBlueprint
{
    public $pages;

    public $pageSteps;

    public function __construct(array $pages)
    {
        $this->pages = $pages;
    }

    public function addPage(int $pageNumber, bool $isCurrent = false): self
    {
        $this->pageSteps[] = [
            'page'      => $pageNumber,
            'isCurrent' => $isCurrent,
        ];

        return $this;
    }

    public function addDelimiter(string $content = '...'): self
    {
        $this->pageSteps[] = [
            'isDelimiter' => true,
            'content'     => $content,
        ];

        return $this;
    }

    public function stepsAsArray(): array
    {
        return $this->pageSteps;
    }
}
