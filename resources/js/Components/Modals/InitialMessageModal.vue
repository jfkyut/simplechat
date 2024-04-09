<script setup>
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import TextInput from '../TextInput.vue';
import { usePage, router } from '@inertiajs/vue3';

defineProps({ user: Object })

const page = usePage();

const showInitialMessageModal = ref(false);

const textInputRef = ref(null);

const form = ref({
    text: null,
    participants: [],
})

const openModal = (user) => {
    showInitialMessageModal.value = true;
    
    form.value.participants.push(page.props.auth.user)
    form.value.participants.push(user) // target user

    setTimeout(() => {
        textInputRef.value.focus();
    }, 200);
}

const closeModal = () => showInitialMessageModal.value = false;

const sendChat = () => {
    router.post('/chat', form.value);
}

</script>

<template>
    <div>
        <button @click="openModal(user)" class="hover:bg-gray-200 px-4 py-2 rounded">
            <i class="fa fa-message"></i>
        </button>
        <Modal :show="showInitialMessageModal" @close="closeModal">
            <form @submit.prevent="sendChat" class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Static modal
                    </h3>
                    <button @click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <input type="text" class="w-full" ref="textInputRef" v-model="form.text" />
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="static-modal" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                    <button @click="closeModal" data-modal-hide="static-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                </div>
            </form>
        </Modal>
    </div>
</template>