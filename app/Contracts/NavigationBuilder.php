<?php

namespace App\Contracts;

interface NavigationBuilder
{
    /**
     * Merge into menu.
     */
    public function merge(array $items): void;

    /**
     * Get menu.
     */
    public function get(): array;

    /**
     * Build from items.
     */
    public function build(array $items = []): self;
}
