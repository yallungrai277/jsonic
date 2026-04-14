<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import NavMain from '@/components/ui/navigation-menu/SidebarNavMain.vue';
import NavUser from '@/components/ui/user/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { index as conversationIndex } from '@/routes/conversation';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
type PageProps = {
    navMenus?: NavItem[];
};

const navMenus: NavItem[] = usePage<PageProps>().props?.navMenus || [];
</script>

<template>
    <Sidebar collapsible="icon" variant="sidebar">
        <SidebarHeader>
            <div class="flex w-full items-center justify-between">
                <SidebarMenu class="flex-1 overflow-hidden group-data-[collapsible=icon]:hidden">
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" as-child class="px-2">
                            <Link :href="conversationIndex()">
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
                <div class="flex items-center justify-center p-2 group-data-[collapsible=icon]:w-full group-data-[collapsible=icon]:p-0">
                    <SidebarTrigger class="text-muted-foreground hover:text-foreground" />
                </div>
            </div>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navMenus" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
