<?php

namespace App\Services\Menu;

use App\Contracts\NavigationBuilder;

class NavigationMenuBuilder implements NavigationBuilder
{
    protected array $menu = [];

    /**
     * Merge items to a menu.
     */
    public function merge(array $items): void
    {
        $this->menu = array_merge($this->menu, $items);
    }

    /**
     * Get menu.
     */
    public function get(): array
    {
        return $this->menu;
    }

    /**
     * Build menu from config.
     */
    public function build(array $items = []): self
    {
        $navMenus = collect($items)
            ->sortBy(fn ($item) => isset($item['weight']) ? (int) $item['weight'] : 0)
            ->map(function ($item) {
                if (empty($item['title'])) {
                    return null;
                }

                if (! empty($item['children_callback']) && is_callable($item['children_callback'])) {
                    $item['children'] = $item['children_callback']();
                    unset($item['children_callback']);
                }

                return [
                    'title' => $item['title'],
                    'url' => ! empty($item['route']) ? route($item['route'], $item['route_params'] ?? []) : '',
                    'icon' => $item['icon'] ?? '',
                    'class' => $item['class'] ?? '',
                    'children' => $item['children'] ?? [],
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        $this->menu = array_merge($this->menu, $navMenus);

        return $this;
    }
}
