<script setup>
import { ref } from 'vue';
import { useForm, Link } from "@inertiajs/vue3";
const props = defineProps({
    items: Array
});

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('th-TH') + ' ' + date.toLocaleTimeString('th-TH');
}

const form = useForm({
    name: "",
});

const loading = ref("Save");

const save = () => {
    loading.value = "Loading...";
    form.post(route("event.store"), {
        onFinish: () => {
            form.reset();
            my_modal_1.close();
            loading.value = "Save";
        }
    });
};
</script>


<template>
    <div>
        <div
            class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
            <button class="btn btn-xs sm:btn-sm md:btn-md lg:btn" onclick="my_modal_1.showModal()">Create a
                vote</button>

            <ul class="menu menu-lg bg-base-200 rounded-box mt-4">
                <div v-if="props.items.length > 0">
                    <li v-for="item in props.items" :key="item.id">
                    <Link :href="`/vote/${item.id}`">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <div>
                            {{ item.name }}

                            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Created on: {{ formatDate(item.created_at) }}
                            </p>
                        </div>
                        <span class="ml-2">Vote total: {{ item.count }}</span>
                    </Link>
                </li>
                </div>
                <li v-else>
                    <p class="text-gray-500 dark:text-gray-400 text-sm text-center flex justify-center">No vote found.</p>
                </li>

            </ul>


        </div>
    </div>

    <dialog id="my_modal_1" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Create a vote</h3>

            <form @submit.prevent="save" class="mt-2">
                <div class="label">
                    <span class="label-text">Name of the vote</span>
                </div>
                <input v-model="form.name" type="text" placeholder="Vote name..." class="input input-bordered w-full"
                    required />


                <div class="modal-action">
                    <button type="button" class="btn mr-3" onclick="my_modal_1.close()">Close</button>
                    <button type="submit" class="btn btn-success">{{ loading }}</button>
                </div>
            </form>
        </div>
    </dialog>
</template>
