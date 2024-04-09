<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

const { messages, chat } = defineProps({ messages: Object, chat: Object })

const messagesList = ref(messages);

const chatChannel = Echo.private(`chat.${chat.id}`);

const form = ref({
    text: null
})

const send = () => {
    axios.post(`/message/${chat.id}`, form.value);

    form.value.text = null;
}

onMounted(() => {
    chatChannel.listen('MessageCreated', (e) => {
        messagesList.value.push(e.message);
    })
})

</script>

<template>

    <Head title="conv" />

    <AuthenticatedLayout>

        <div class="bg-white max-w-4xl mx-auto p-4 mt-4">
            {{ chat.participants
                .filter(participant => participant.id !== $page.props.auth.user.id)
                .map(participant => participant.name)
                .join(', ')
            }}
        </div>
        <div class="p-4 max-w-4xl mx-auto">
            <div
                class="my-5 flex" v-for="(message, index) in messagesList" :key="index"
                :class="$page.props.auth.user.id === message.sender.id ? 'justify-end' : 'justify-start'" >
                <div 
                    :class="$page.props.auth.user.id === message.sender.id ? 'bg-blue-400' : 'bg-gray-300'"
                    class="m-2 rounded p-4">
                    {{ message.text }}
                </div>
                
            </div>
        </div>
        <form @submit.prevent="send" class="bg-gray-300 fixed bottom-10 left-1/2 -translate-x-1/2">
            <input type="text" v-model="form.text">
            <button type="submit" class="px-4 py-2">
                Send
            </button>
        </form>
    </AuthenticatedLayout>
</template>