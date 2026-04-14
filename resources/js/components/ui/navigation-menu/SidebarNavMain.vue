<script setup lang="ts">

import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { type NavItem } from '@/types';
import NavigationMenuLink from './NavigationMenuLink.vue';


defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>
<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarMenu>
            <SidebarMenuItem v-for="(item, idx) in items" :key="`nav-${idx}`">
                <SidebarGroupLabel v-if="!item.url">{{ item.title }}</SidebarGroupLabel>
                <SidebarMenuButton v-else :is-active="isCurrentUrl(item.url)" as-child :tooltip="item.title">
                    <NavigationMenuLink :item="item" />
                </SidebarMenuButton>

                <SidebarMenuItem v-for="(childItem, idx) in item.children ?? []" :key="`nav-${idx}`">
                    <SidebarGroupLabel v-if="!childItem.url">{{ childItem.title }}</SidebarGroupLabel>
                    <SidebarMenuButton v-else :is-active="isCurrentUrl(childItem.url)" as-child :tooltip="childItem.title">
                        <NavigationMenuLink :item="childItem" />
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
