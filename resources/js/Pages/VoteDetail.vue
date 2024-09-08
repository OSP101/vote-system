<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from "@inertiajs/vue3";
import QrcodeVue from 'qrcode.vue'
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);
const props = defineProps({
    vote: Object,
    activeVotesDetails: Array
});

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('th-TH') + ' ' + date.toLocaleTimeString('th-TH');
}
const value = ref(`http://10.199.7.28/vote/${props.vote.id}/${props.vote.qrcode}`)

const form = useForm({
    name_choice: "",
    description: "",
    id_votes: props.vote.id
});

const loading = ref("Save");
const status = ref("");

if (props.vote.status == "disabled") {
    status.value = "Open voting"
} else {
    status.value = "Clone voting"
}

const save = () => {
    loading.value = "Loading...";
    form.post(route("create.options"), {
        onFinish: () => {
            form.reset();
            my_modal_2.close();
            loading.value = "Save";
        }
    });
};


const voteData = ref([]);
let chart = null;

const fetchVoteData = async () => {
    const response = await fetch(`/api/vote/${props.vote.id}/data`);
    voteData.value = await response.json();
    updateChart();
};

const updateChart = () => {
    const canvas = document.getElementById('voteChart');
    const ctx = canvas.getContext('2d');

    if (chart != null) {
        chart.destroy();
        chart = null;
    }

    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: voteData.value.map(detail => detail.name_choice),
            datasets: [{
                label: 'Votes',
                data: voteData.value.map(detail => detail.count),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function (value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                }
            }
        }
    });
};

onMounted(() => {
    fetchVoteData();
    setInterval(fetchVoteData, 5000);
});

</script>

<template>
    <AppLayout title="Vote detail">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-rows-12 grid-cols-2 gap-4">
                    <div
                        class="row-span-12 col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 ">
                            <div class="flex justify-center mb-4">
                                <qrcode-vue :value="value" :size=300 level="M" />
                            </div>
                            <h2 class="text-2xl font-bold text-center">Vote Details: {{ vote.name }}</h2>
                            <p class="mt-4 text-center">Created on: {{ formatDate(vote.created_at) }} | Status: {{
                                vote.status }}</p>

                            <div class="mt-6 text-center flex justify-center">
                                <Link href="/dashboard" class="btn btn-primary">Back to Dashboard</Link>
                                <Link :href="`/votes/change/${vote.id}`" class="btn btn-warning ml-4">{{ status }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-span-1 row-span-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <canvas id="voteChart"></canvas>
                    </div>
                    <div
                        class="col-span-1 row-span-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                        <div class="p-6 lg:p-8 ">
                            <div class="flex justify-between">
                                <p>Voting options</p>
                                <button onclick="my_modal_2.showModal()" class="btn btn-primary btn-sm">Add voting
                                    options</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <!-- head -->
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name options</th>
                                            <th>Description</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- ใช้ v-if สำหรับการแสดงผลของข้อมูล -->
                                        <template v-if="props.activeVotesDetails.length > 0">
                                            <tr v-for="(trashedVote, index) in props.activeVotesDetails" :key="index">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ trashedVote.name_choice }}</td>
                                                <td>{{ trashedVote.description }}</td>
                                                <td>
                                                    <Link :href="`/options/delete/${trashedVote.id}`"
                                                        class="btn btn-error btn-sm">Delete</Link>

                                                </td>
                                            </tr>
                                        </template>
                                        <!-- ใช้ v-else สำหรับกรณีที่ไม่มีข้อมูล -->
                                        <template v-else>
                                            <tr>
                                                <td colspan="4" class="text-center">No trashed options.</td>
                                            </tr>
                                        </template>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <dialog id="my_modal_2" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Create a options</h3>

                <form @submit.prevent="save" class="mt-2">
                    <div class="label">
                        <span class="label-text">Name of options</span>
                    </div>
                    <input v-model="form.name_choice" type="text" placeholder="Name options"
                        class="input input-bordered w-full" required />
                    <label class="form-control">
                        <div class="label">
                            <span class="label-text">Description</span>
                        </div>
                        <textarea v-model="form.description" class="textarea textarea-bordered h-24"
                            placeholder="Description..."></textarea>
                    </label>

                    <div class="modal-action">
                        <button type="button" class="btn mr-3" onclick="my_modal_2.close()">Close</button>
                        <button type="submit" class="btn btn-success">{{ loading }}</button>
                    </div>
                </form>
            </div>
        </dialog>

    </AppLayout>


</template>
