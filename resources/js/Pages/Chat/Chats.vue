<script setup>
import InitialMessageModal from '@/Components/Modals/InitialMessageModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ chats: Object })

</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
        </template>

        <div v-for="(chat, index) in chats" :key="index" class="max-w-4xl mx-auto">
            <div class="bg-gray-300 p-4 m-4">
                <!-- label -->
                <Link :href="route('chat.show', chat.id)">
                    <strong>
                        <span>
                            {{ chat.participants.filter(
                                    participant => participant.id !== $page.props.auth.user.id
                                ).map(participant => participant.name).join(', ') 
                            }}
                        </span>
                    </strong>
                    <div>
                        {{ chat?.messages[0]?.text }}
                    </div>
                </Link>
                <!-- {{ chat.participants }} -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
