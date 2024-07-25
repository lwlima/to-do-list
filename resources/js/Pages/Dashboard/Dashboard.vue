<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import RecentTaskActivities from "./Components/RecentTaskActivities.vue";
import echo from "@/echo";
import { ref } from "vue";

const props = defineProps({
  report: Object,
});

const total = ref(props.report.total);
const totalPending = ref(props.report.total_pending);
const totalComplete = ref(props.report.total_complete);
const recentActivities = ref(props.report.recent_activities);

echo
  .channel("dashboard")
  .listen("TaskUpdated", (event) => {
    total.value = event.total;
    totalPending.value = event.totalPending;
    totalComplete.value = event.totalComplete;
  })
  .listen("TaskCreated", (event) => {
    total.value = event.total;
    totalPending.value = event.totalPending;
    totalComplete.value = event.totalComplete;
  })
  .listen("TaskDeleted", (event) => {
    total.value = event.total;
    totalPending.value = event.totalPending;
    totalComplete.value = event.totalComplete;
  });
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="max-w-7xl flex flex-col mx-auto px-6 lg:px-8 space-y-2 py-4">
      <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
        <Card>
          <CardHeader
            class="flex flex-row items-center justify-between space-y-0 pb-2"
          >
            <CardTitle class="text-sm font-medium">
              Total de tarefas
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ total }}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader
            class="flex flex-row items-center justify-between space-y-0 pb-2"
          >
            <CardTitle class="text-sm font-medium">
              Tarefas pendentes
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ totalPending }}
            </div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader
            class="flex flex-row items-center justify-between space-y-0 pb-2"
          >
            <CardTitle class="text-sm font-medium">
              Tarefas concluídas
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ totalComplete }}
            </div>
          </CardContent>
        </Card>
      </div>
      <div class="grid grid-cols-1">
        <Card>
          <CardHeader>
            <CardTitle>Atividades recentes</CardTitle>
          </CardHeader>
          <CardContent>
            <RecentTaskActivities :data="recentActivities" />
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
