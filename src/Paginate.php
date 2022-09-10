<?php

namespace AlexBarnsley;

class Paginate
{
    public $items;

    public $currentPage = 1;

    public $perPage = 10;

    public $eitherSide = 1;

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

    public function showingXPagesEitherSide(int $eitherSide): self
    {
        $this->eitherSide = $eitherSide;

        return $this;
    }

    public function withDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function generateAsArray(): array
    {
        $pages      = array_chunk($this->items, $this->perPage);
        $totalPages = count($pages);

        $pageEntries = [];
        foreach (array_keys($pages) as $pageNumber) {
            $pageNumber++;

            if ($this->hidePageNumber($pageNumber, $totalPages)) {
                if ($this->showDelimiter($pageNumber)) {
                    $pageEntries[] = ['isDelimiter' => true];
                }

                continue;
            }

            if ($pageNumber !== $this->currentPage) {
                $pageEntries[] = [
                    'page'      => $pageNumber,
                    'isCurrent' => false,
                ];
            } else {
                $pageEntries[] = [
                    'page'      => $pageNumber,
                    'isCurrent' => true,
                ];
            }
        }

        return $pageEntries;
    }

    private function hidePageNumber(int $pageNumber, int $totalPages): bool
    {
        if ($pageNumber >= ($this->currentPage + ($this->eitherSide + 1)) && $pageNumber <= $totalPages - 3) {
            return true;
        }

        return $pageNumber <= ($this->currentPage - ($this->eitherSide + 1)) && $pageNumber > 3;
    }

    private function showDelimiter(int $pageNumber): bool
    {
        if ($pageNumber === ($this->currentPage + ($this->eitherSide + 1))) {
            return true;
        }

        return $pageNumber === ($this->currentPage - ($this->eitherSide + 1));
    }
}
