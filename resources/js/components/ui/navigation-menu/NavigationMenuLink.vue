<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { type NavItem } from '@/types';
import ContextDropDown from '@/components/ui/dropdown-menu/ContextDropDown.vue';

defineProps<{
  item: NavItem;
}>();

</script>

<template>
  <Link :href="item.url ?? '#'" class="relative nav-menu-link">
      <component v-if="item.icon" :is="item.icon" />
      <span class="w-50 truncate">{{ item.title }}</span>
      <ContextDropDown />
  </Link>
</template>