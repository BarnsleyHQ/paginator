<?php

namespace AlexBarnsley\Paginator;

class Paginator
{
    public $items;

    public $currentPage = 1;

    public $perPage = 10;

    public $eitherSide = 1;

    public $pagesAtEnds = 3;

    public $delimiter = '...';

    public function __construct($items)
    {
        if (is_array($items) || $items instanceof ArrayAccess) {
            $this->items = $items;
        }
    }

    public function onPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function showPagesEitherSide(int $eitherSide): self
    {
        $this->eitherSide = $eitherSide;

        return $this;
    }

    public function showPagesAtEnds(int $pagesAtEnds): self
    {
        $this->pagesAtEnds = $pagesAtEnds;

        return $this;
    }

    public function withDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function generate(): PageHandler
    {
        $pages      = array_chunk($this->items, $this->perPage);
        $totalPages = count($pages);

        $blueprint = new PageHandler($pages);
        foreach (array_keys($pages) as $pageNumber) {
            $pageNumber++;

            if ($this->hidePageNumber($pageNumber, $totalPages)) {
                if ($this->showDelimiter($pageNumber, $totalPages)) {
                    $blueprint->addDelimiter($this->delimiter);
                }

                continue;
            }

            if ($pageNumber !== $this->currentPage) {
                $blueprint->addPage($pageNumber);
            } else {
                $blueprint->addPage($pageNumber, true);
            }
        }

        return $blueprint;
    }

    private function hidePageNumber(int $pageNumber, int $totalPages): bool
    {
        if ($pageNumber <= $this->pagesAtEnds) {
            return false;
        }

        if ($pageNumber >= ($this->currentPage + ($this->eitherSide + 1)) && $pageNumber <= $totalPages - $this->pagesAtEnds) {
            return true;
        }

        if ($pageNumber > $totalPages - $this->pagesAtEnds) {
            return false;
        }

        return $pageNumber <= ($this->currentPage - ($this->eitherSide + 1)) && $pageNumber > $this->pagesAtEnds;
    }

    private function showDelimiter(int $pageNumber, int $totalPages): bool
    {
        if ($pageNumber === ($this->currentPage + ($this->eitherSide + 1))) {
            return true;
        }

        if ($this->currentPage > ($totalPages - $this->pagesAtEnds) && $pageNumber === ($totalPages - ($this->pagesAtEnds + 1))) {
            return true;
        }

        if ($this->currentPage <= $this->pagesAtEnds && $pageNumber === $this->pagesAtEnds + 1) {
            return true;
        }

        return $pageNumber === ($this->currentPage - ($this->eitherSide + 1));
    }
}
