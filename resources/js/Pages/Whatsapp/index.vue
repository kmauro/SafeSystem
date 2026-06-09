<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { NativeSelect, NativeSelectOption } from '@/Components/ui/native-select'
import { MessageCircle, RefreshCw, Send, ShieldCheck } from 'lucide-vue-next'
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'

defineOptions({
    layout: AppLayout,
})

defineProps({
    title: {
        type: String,
        default: 'Conversaciones',
    },
})

const statusOptions = [
    { value: 'BOT', label: 'BOT' },
    { value: 'HUMAN_PENDING', label: 'HUMAN_PENDING' },
    { value: 'HUMAN', label: 'HUMAN' },
]

const filterOptions = [
    { value: '', label: 'Todos' },
    { value: 'HUMAN_PENDING', label: 'Pendientes' },
    { value: 'HUMAN', label: 'Humano' },
    { value: 'BOT', label: 'Bot' },
]

const conversations = ref([])
const selectedConversation = ref(null)
const selectedId = ref('')
const search = ref('')
const statusFilter = ref('')
const statusValue = ref('BOT')
const reply = ref('')
const adminKey = ref('')
const errorMessage = ref('')
const loadingConversations = ref(false)
const loadingConversation = ref(false)
const savingStatus = ref(false)
const sendingReply = ref(false)
const messagesContainer = ref(null)

let searchTimer = null
let refreshTimer = null

const adminKeyStorageKey = 'botiAdminKey'

const messages = computed(() => selectedConversation.value?.messages ?? [])

const activeConversationName = computed(() => {
    if (!selectedConversation.value) {
        return 'Selecciona una conversacion'
    }

    return conversationName(selectedConversation.value)
})

const chatMeta = computed(() => {
    if (!selectedConversation.value) {
        return ''
    }

    const conversation = selectedConversation.value
    const lastDate = conversation.lastMessageAt ?? conversation.createdAt

    return [conversation.waId, formatDate(lastDate)].filter(Boolean).join(' - ')
})

watch(search, () => {
    window.clearTimeout(searchTimer)
    searchTimer = window.setTimeout(() => {
        loadConversations().catch(setError)
    }, 250)
})

watch(statusFilter, () => {
    loadConversations().catch(setError)
})

onMounted(() => {
    adminKey.value = window.localStorage.getItem(adminKeyStorageKey) ?? ''
    selectedId.value = new URLSearchParams(window.location.search).get('conversation') ?? ''

    loadConversations().catch(setError)
    if (typeof window !== 'undefined') {
        window.dispatchEvent(new CustomEvent('sidebar:set', { detail: { open: false } }))
    }
    refreshTimer = window.setInterval(() => {
        loadConversations().catch(() => {})
    }, 7000)
})

onBeforeUnmount(() => {
    window.clearTimeout(searchTimer)
    window.clearInterval(refreshTimer)
})

function requestHeaders() {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken
    }

    if (adminKey.value) {
        headers['x-admin-key'] = adminKey.value
    }

    return headers
}

async function api(path, options = {}) {
    const response = await window.fetch(path, {
        ...options,
        headers: {
            ...requestHeaders(),
            ...(options.headers ?? {}),
        },
    })

    if (response.status === 401) {
        const key = window.prompt('Admin key')

        if (key) {
            adminKey.value = key
            window.localStorage.setItem(adminKeyStorageKey, key)

            return api(path, options)
        }
    }

    const responseText = await response.text()

    if (!response.ok) {
        throw new Error(parseErrorMessage(responseText, response.statusText))
    }

    if (!responseText) {
        return null
    }

    try {
        return JSON.parse(responseText)
    } catch {
        return responseText
    }
}

async function loadConversations() {
    loadingConversations.value = true
    errorMessage.value = ''

    try {
        const params = new URLSearchParams()

        if (statusFilter.value) {
            params.set('status', statusFilter.value)
        }

        if (search.value.trim()) {
            params.set('search', search.value.trim())
        }

        const query = params.toString()
        const payload = await api(query ? `/api/conversations?${query}` : '/api/conversations')

        conversations.value = normalizeCollection(payload)

        if (!selectedId.value && conversations.value[0]) {
            selectedId.value = conversations.value[0].id
        }

        if (selectedId.value) {
            await loadConversation(selectedId.value)
        } else {
            selectedConversation.value = null
        }
    } finally {
        loadingConversations.value = false
    }
}

async function loadConversation(id) {
    selectedId.value = id
    loadingConversation.value = true
    errorMessage.value = ''

    try {
        const payload = await api(`/api/conversations/${encodeURIComponent(id)}`)
        selectedConversation.value = normalizeResource(payload)
        statusValue.value = selectedConversation.value?.status ?? 'BOT'

        await nextTick()
        scrollMessagesToBottom()
    } finally {
        loadingConversation.value = false
    }
}

async function saveStatus() {
    if (!selectedId.value) {
        return
    }

    savingStatus.value = true
    errorMessage.value = ''

    try {
        await api(`/api/conversations/${encodeURIComponent(selectedId.value)}/status`, {
            method: 'PATCH',
            body: JSON.stringify({ status: statusValue.value }),
        })

        await loadConversations()
    } catch (error) {
        setError(error)
    } finally {
        savingStatus.value = false
    }
}

async function sendReply() {
    const body = reply.value.trim()

    if (!selectedId.value || !body) {
        return
    }

    sendingReply.value = true
    errorMessage.value = ''

    try {
        await api(`/api/conversations/${encodeURIComponent(selectedId.value)}/messages`, {
            method: 'POST',
            body: JSON.stringify({ body }),
        })

        reply.value = ''
        await loadConversations()
    } catch (error) {
        setError(error)
    } finally {
        sendingReply.value = false
    }
}

function normalizeCollection(payload) {
    if (Array.isArray(payload)) {
        return payload
    }

    return payload?.data ?? []
}

function normalizeResource(payload) {
    return payload?.data ?? payload
}

function conversationName(conversation) {
    return conversation?.displayName || conversation?.waId || 'Sin nombre'
}

function latestMessage(conversation) {
    return conversation?.messages?.[0]?.body || 'Sin mensajes'
}

function formatDate(value) {
    if (!value) {
        return ''
    }

    return new Date(value).toLocaleString()
}

function setError(error) {
    errorMessage.value = error?.message ?? 'No se pudo completar la accion.'
}

function parseErrorMessage(responseText, fallback) {
    if (!responseText) {
        return fallback || 'No se pudo completar la accion.'
    }

    try {
        const payload = JSON.parse(responseText)

        return payload.message ?? payload.error ?? responseText
    } catch {
        return responseText
    }
}

function scrollMessagesToBottom() {
    if (!messagesContainer.value) {
        return
    }

    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
}

function conversationRowClasses(conversation) {
    const active = conversation.id === selectedId.value

    return [
        'grid w-full gap-2 border-b px-3 py-3 text-left transition-colors',
        active ? 'bg-accent text-accent-foreground' : 'hover:bg-muted/70',
    ]
}

function statusBadgeClasses(status) {
    const statusClasses = {
        BOT: 'border-slate-200 bg-slate-100 text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200',
        HUMAN_PENDING: 'border-amber-200 bg-amber-100 text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200',
        HUMAN: 'border-blue-200 bg-blue-100 text-blue-800 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-200',
    }

    return [
        'shrink-0 rounded-full border px-2 py-0.5 text-xs font-semibold',
        statusClasses[status] ?? statusClasses.BOT,
    ]
}

function messageClasses(message) {
    const baseClasses = 'max-w-[min(680px,88%)] rounded-md border px-3 py-2 text-sm leading-relaxed shadow-xs'

    if (message.direction === 'OUTBOUND') {
        return [
            baseClasses,
            'self-end border-emerald-200 bg-emerald-50 text-emerald-950 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100',
        ]
    }

    if (message.direction === 'SYSTEM') {
        return [
            baseClasses,
            'self-center bg-muted text-muted-foreground',
        ]
    }

    return [
        baseClasses,
        'self-start bg-card text-card-foreground',
    ]
}
</script>

<template>
    <div class="flex h-[calc(100vh-8rem)] min-h-[560px] flex-col overflow-hidden rounded-md border bg-background text-foreground lg:flex-row">
        <aside class="flex min-h-[280px] flex-col border-b bg-card lg:h-full lg:w-[360px] lg:border-r lg:border-b-0">
            <div class="border-b p-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-semibold">
                            Conversaciones
                        </h1>
                        <p class="text-sm text-muted-foreground">
                            {{ conversations.length }} chats
                        </p>
                    </div>

                    <Button
                        type="button"
                        variant="outline"
                        size="icon"
                        title="Actualizar conversaciones"
                        :disabled="loadingConversations"
                        @click="loadConversations"
                    >
                        <RefreshCw :class="['size-4', loadingConversations ? 'animate-spin' : '']" />
                    </Button>
                </div>

                <div class="mt-3 grid gap-2 sm:grid-cols-[1fr_150px] lg:grid-cols-1 xl:grid-cols-[1fr_150px]">
                    <Input
                        v-model="search"
                        autocomplete="off"
                        placeholder="Buscar"
                    />

                    <NativeSelect
                        v-model="statusFilter"
                        class="w-full"
                    >
                        <NativeSelectOption
                            v-for="option in filterOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </NativeSelectOption>
                    </NativeSelect>
                </div>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto">
                <div
                    v-if="loadingConversations && !conversations.length"
                    class="grid h-full place-items-center p-6 text-sm text-muted-foreground"
                >
                    Cargando conversaciones...
                </div>

                <div
                    v-else-if="!conversations.length"
                    class="grid h-full place-items-center p-6 text-center text-sm text-muted-foreground"
                >
                    Todavia no hay conversaciones.
                </div>

                <button
                    v-for="conversation in conversations"
                    v-else
                    :key="conversation.id"
                    type="button"
                    :class="conversationRowClasses(conversation)"
                    @click="loadConversation(conversation.id)"
                >
                    <span class="flex min-w-0 items-center justify-between gap-3">
                        <span class="truncate font-semibold">
                            {{ conversationName(conversation) }}
                        </span>
                        <span :class="statusBadgeClasses(conversation.status)">
                            {{ conversation.status }}
                        </span>
                    </span>

                    <span class="truncate text-sm text-muted-foreground">
                        {{ latestMessage(conversation) }}
                    </span>

                    <span class="text-xs text-muted-foreground">
                        {{ formatDate(conversation.lastMessageAt || conversation.createdAt) }}
                    </span>
                </button>
            </div>
        </aside>

        <section class="flex min-w-0 flex-1 flex-col bg-muted/20">
            <div class="border-b bg-card p-4">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                    <div class="min-w-0">
                        <div class="mb-1 flex items-center gap-2 text-xs font-medium uppercase text-muted-foreground">
                            <MessageCircle class="size-4" />
                            Chat
                        </div>
                        <h2 class="truncate text-base font-semibold">
                            {{ activeConversationName }}
                        </h2>
                        <p class="truncate text-sm text-muted-foreground">
                            {{ chatMeta || 'Sin conversacion activa' }}
                        </p>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-[180px_auto] sm:items-end">
                        <div class="grid gap-2">
                            <Label for="conversation-status">Estado</Label>
                            <NativeSelect
                                id="conversation-status"
                                v-model="statusValue"
                                class="w-full"
                                :disabled="!selectedConversation"
                            >
                                <NativeSelectOption
                                    v-for="option in statusOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </NativeSelectOption>
                            </NativeSelect>
                        </div>

                        <Button
                            type="button"
                            variant="secondary"
                            :disabled="!selectedConversation || savingStatus"
                            @click="saveStatus"
                        >
                            <ShieldCheck class="size-4" />
                            Guardar estado
                        </Button>
                    </div>
                </div>
            </div>

            <div
                ref="messagesContainer"
                class="min-h-0 flex-1 overflow-y-auto p-4"
            >
                <div
                    v-if="errorMessage"
                    class="mx-auto max-w-xl rounded-md border border-destructive/30 bg-destructive/10 p-3 text-sm text-destructive"
                >
                    {{ errorMessage }}
                </div>

                <div
                    v-else-if="!selectedConversation && !loadingConversation"
                    class="grid h-full place-items-center text-center text-sm text-muted-foreground"
                >
                    No hay una conversacion seleccionada.
                </div>

                <div
                    v-else-if="loadingConversation"
                    class="grid h-full place-items-center text-sm text-muted-foreground"
                >
                    Cargando mensajes...
                </div>

                <div
                    v-else
                    class="flex flex-col gap-3"
                >
                    <div
                        v-for="message in messages"
                        :key="message.id ?? `${message.direction}-${message.createdAt}-${message.body}`"
                        :class="messageClasses(message)"
                    >
                        <p class="whitespace-pre-wrap break-words">
                            {{ message.body }}
                        </p>
                        <small class="mt-2 block text-xs text-muted-foreground">
                            {{ message.direction }} - {{ formatDate(message.createdAt) }}
                        </small>
                    </div>
                </div>
            </div>

            <form
                class="border-t bg-card p-4"
                @submit.prevent="sendReply"
            >
                <div class="grid gap-2 md:grid-cols-[1fr_auto] md:items-end">
                    <textarea
                        v-model="reply"
                        class="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 min-h-20 w-full resize-y rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Responder como vendedor"
                        :disabled="!selectedConversation || sendingReply"
                    />

                    <Button
                        type="submit"
                        :disabled="!selectedConversation || !reply.trim() || sendingReply"
                    >
                        <Send class="size-4" />
                        Enviar
                    </Button>
                </div>
            </form>
        </section>
    </div>
</template>
