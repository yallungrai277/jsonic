<script setup lang="ts">
import { ref, watch, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import {
  ToastClose,
  ToastDescription,
  ToastProvider,
  ToastRoot,
  ToastTitle,
  ToastViewport
} from 'reka-ui'
import {
  CheckCircle2,
  AlertCircle,
  Info,
  AlertTriangle,
  X
} from 'lucide-vue-next'
import type { ToastItem, ToastType } from '@/types/toast'

const MAX_TOASTS = 5
const AUTO_DISMISS_MS = 5000
const page = usePage<{ flash?: Record<string, string> }>()
const toasts = ref<ToastItem[]>([])
let counter = 0
const timeouts = new Map<number, number>()

function addToast(message: string, type: ToastType = 'info') {
  if (!message) return

  const id = counter++

  if (toasts.value.length >= MAX_TOASTS) {
    removeToast(toasts.value[0].id)
  }

  const toast: ToastItem = {
    id,
    message,
    type,
    open: true
  }

  toasts.value.push(toast)
  const timeoutId = window.setTimeout(() => removeToast(id), AUTO_DISMISS_MS)
  timeouts.set(id, timeoutId)
}

function removeToast(id: number) {
  const index = toasts.value.findIndex(t => t.id === id)
  if (index === -1) return

  toasts.value[index].open = false

  window.setTimeout(() => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }, 350) // match animation duration

  const timeout = timeouts.get(id)
  if (timeout) {
    clearTimeout(timeout)
    timeouts.delete(id)
  }
}

watch(
  () => page.props.flash,
  flash => {
    if (! flash) return

    const entries: [ToastType, string | undefined][] = [
      ['success', flash.success],
      ['error', flash.error],
      ['warning', flash.warning],
      ['info', flash.info]
    ]

    for (const [type, message] of entries) {
      if (message) addToast(message, type)
    }
  },
  { immediate: true }
)

function showToast(message?: string, type: ToastType = 'info') {
  addToast(message ?? 'Notification', type)
}

if (typeof window !== 'undefined') {
  (window as any).toast = showToast
}

defineExpose({ showToast, addToast })

onUnmounted(() => {
  timeouts.forEach(timeout => clearTimeout(timeout))
  timeouts.clear()
})

const variantStyles: Record<ToastType, string> = {
  success:
    'bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200',
  error:
    'bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200',
  warning:
    'bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200',
  info:
    'bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200'
}

const iconColors: Record<ToastType, string> = {
  success: 'text-green-600 dark:text-green-400',
  error: 'text-red-600 dark:text-red-400',
  warning: 'text-yellow-600 dark:text-yellow-400',
  info: 'text-blue-600 dark:text-blue-400'
}
</script>

<template>
<ToastProvider>
  <ToastRoot
    v-for="toast in toasts"
    :key="toast.id"
    v-model:open="toast.open"
    @update:open="val => !val && removeToast(toast.id)"
    class="group relative flex flex-shrink-0 min-h-[72px] w-[90vw] max-w-lg md:max-w-xl items-start justify-between gap-4 overflow-hidden rounded-xl border p-4 pr-6 shadow-xl backdrop-blur-sm transition-all duration-350 ease-[cubic-bezier(0.16,1,0.3,1)]
      data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:slide-in-from-bottom-3
      data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:slide-out-to-bottom-3
      data-[swipe=move]:translate-x-[var(--reka-toast-swipe-move-x)]
      data-[swipe=cancel]:translate-x-0
      data-[swipe=end]:animate-out data-[swipe=end]:slide-out-to-bottom-3"
    :class="variantStyles[toast.type]"
  >
    <div class="flex items-start gap-4 w-full">
      <!-- Icon -->
      <div class="shrink-0 mt-0.5">
        <CheckCircle2 v-if="toast.type==='success'" class="h-5 w-5" :class="iconColors[toast.type]" />
        <AlertCircle v-else-if="toast.type==='error'" class="h-5 w-5" :class="iconColors[toast.type]" />
        <AlertTriangle v-else-if="toast.type==='warning'" class="h-5 w-5" :class="iconColors[toast.type]" />
        <Info v-else class="h-5 w-5" :class="iconColors[toast.type]" />
      </div>

      <!-- Text -->
      <div class="flex flex-col gap-1 w-full">
        <ToastTitle class="font-semibold text-sm capitalize">{{ toast.type }}</ToastTitle>
        <ToastDescription class="text-sm opacity-90 leading-relaxed">{{ toast.message }}</ToastDescription>
      </div>
    </div>

    <!-- Close -->
    <ToastClose class="absolute right-2 top-2 rounded-md p-1 opacity-0 transition-opacity hover:opacity-75 focus:opacity-100 focus:outline-none focus:ring-2 focus:ring-current group-hover:opacity-100 dark:text-gray-300">
      <span class="sr-only">Close</span>
      <X class="h-4 w-4" />
    </ToastClose>
  </ToastRoot>

  <!-- Centered Bottom Viewport -->
  <ToastViewport
    class="fixed bottom-4 left-1/2 z-[100] flex flex-col-reverse items-center overflow-y-auto p-4 gap-2 outline-none -translate-x-1/2"
  />
</ToastProvider>
</template>