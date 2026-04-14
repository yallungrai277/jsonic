<script setup lang="ts">
import { Ellipsis } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';

// @todo add icon
</script>
<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Ellipsis class="absolute right-2 size-8 z-10 dropdown-menu-trigger" />
        </DropdownMenuTrigger>

        <DropdownMenuContent align="right" class="min-w-40">
            <div class="flex items-center gap-4 px-1 py-1.5 text-center text-sm">
                <component is="Settings" class="size-4" />
                <span>Edit</span>
            </div>
            <DropdownMenuSeparator />
            <div class="flex items-center gap-4 px-1 py-1.5 text-center text-sm">
                <component is="ArrowUpDown" class="size-4"/>
                <span>Delete</span>
            </div>
        </DropdownMenuContent>
    </DropdownMenu>
</template>