<?php

namespace Survos\SimpleDatatables\Model;

class Column
{
    public function __construct(
        public string $name,
        public ?string $title = null,
        public ?string $twigTemplate = null, // the actual twig
        public ?string $block = null, // reuse the blocks even if the data changes
        public ?string $route = null,
        public ?string $prefix = null,
        public ?array $actions = null,
        public bool $modal = false,
        public bool $searchable = false,
        public bool $inSearchPane = false,
        public bool $translateValue = false,
        public ?string  $domain = null, // null is default, false blocks translation
        public bool $sortable = false,
        public bool|string $locale=false,
        public bool $condition = true
    ) {
        if (empty($this->title)) {
            $this->title = $this->name; // when dealing with raw csv, this is confusing 
//            $this->title = ucwords($this->name);
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
