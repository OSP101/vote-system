<script setup>
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/VoteCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupDescription,
    RadioGroupOption,
} from '@headlessui/vue';

const props = defineProps({
    vote: Object,
    activeVotesDetails: Array
});

const selected = ref(null);
const status = ref('');
const messagebuttons = ref('VOTE!!');

const form = useForm({
    student_id: '',
    id_votes_detail: '',
    id_votes: props.vote.id,
    qrcode: props.vote.qrcode
});

const submit = () => {
    if (selected.value) {
        messagebuttons.value = "Voting in progress..."
        form.id_votes_detail = selected.value.id;
        form.post(route('enroll'), {
            onSuccess: () => {
                status.value = 'Vote submitted successfully!';
                messagebuttons.value = "VOTE!!"
                form.reset();
            },
            onError: () => {
                status.value = 'An error occurred while submitting your vote.';
                messagebuttons.value = "VOTE!!"
                form.reset();

            }
        });
    } else {
        status.value = 'Please select an option.';
    }
};




</script>

<template>

    <Head title="Vote" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <template #name>
            {{ props.vote.name }}
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div v-if="props.vote.status == 'active'">
                <div>
                    <InputLabel for="studentid" value="Student ID" />
                    <TextInput id="studentid" v-model="form.student_id" type="text" class="mt-1 block w-full"
                        required />
                    <InputError class="mt-2" :message="form.errors.student_id" />
                </div>

                <div class="mt-4">
                    <InputLabel for="vote" value="Voting options" />
                    <div class="mx-auto w-full max-w-md mt-4">
                        <RadioGroup v-model="selected">
                            <RadioGroupLabel class="sr-only">Voting options</RadioGroupLabel>
                            <div class="space-y-2">
                                <RadioGroupOption as="template" v-for="activeVotesDetail in props.activeVotesDetails"
                                    :key="activeVotesDetail.id" :value="activeVotesDetail" v-slot="{ active, checked }">
                                    <div :class="[
                                        active ? 'ring-2 ring-white/60 ring-offset-2 ring-offset-sky-300' : '',
                                        checked ? 'bg-sky-900/75 text-white' : 'bg-white'
                                    ]"
                                        class="relative flex cursor-pointer rounded-lg px-5 py-4 shadow-md focus:outline-none">
                                        <div class="flex w-full items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="text-sm">
                                                    <RadioGroupLabel as="p"
                                                        :class="checked ? 'text-white' : 'text-gray-900'"
                                                        class="font-medium">
                                                        {{ activeVotesDetail.name_choice }}
                                                    </RadioGroupLabel>
                                                    <RadioGroupDescription as="span"
                                                        :class="checked ? 'text-sky-100' : 'text-gray-500'"
                                                        class="inline">
                                                        <span>{{ activeVotesDetail.description }}</span>
                                                    </RadioGroupDescription>
                                                </div>
                                            </div>
                                            <div v-show="checked" class="shrink-0 text-white">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                                    <circle cx="12" cy="12" r="12" fill="#fff" fill-opacity="0.2" />
                                                    <path d="M7 13l3 3 7-7" stroke="#fff" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>
                    </div>
                    <InputError class="mt-2" :message="form.errors.vote" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Vote!!
                    </PrimaryButton>
                </div>
            </div>
            <div v-else-if="props.vote.status == 'disabled'">
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">This vote is currently closed.</p>
            </div>
        </form>
    </AuthenticationCard>
</template>
