<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router, Head, useForm } from "@inertiajs/vue3";
import { Card, CardFooter, CardHeader, CardTitle } from "@/Components/ui/card";
import {
  CheckCircledIcon,
  MagnifyingGlassIcon,
  PlusIcon,
  TrashIcon,
} from "@radix-icons/vue";
import { useToast } from "@/Components/ui/toast";
import Toaster from "@/Components/ui/toast/Toaster.vue";
import { Input } from "@/Components/ui/input";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select";
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from "@/Components/ui/pagination";
import { onMounted, ref, watch } from "vue";
import { debounce } from "lodash";
import CreateTaskDialog from "./Components/CreateTaskDialog.vue";
import TaskDialog from "./Components/TaskDialog.vue";
import { Button } from "@/Components/ui/button";

const props = defineProps({
  tasks: Object,
  users: Array,
  search: String,
  filter: String,
});

const selectedFilter = ref(props.filter);
const searchTerm = ref(props.search);
const isOpen = ref(false);
const isOpenCreateDialog = ref(false);
const selectedTask = ref({});

const { toast } = useToast();
const form = useForm({});

function handleFilterChange(value) {
  router.reload({
    only: ["tasks"],
    data: { filter: value, searchTerm: searchTerm.value },
  });
}

function handleSearch(value) {
  router.reload({
    data: { page: 1, search: value, filter: selectedFilter.value },
  });
}
const debouncedHandleSearch = debounce(handleSearch, 300);

watch(selectedFilter, (value) => handleFilterChange(value));
watch(searchTerm, (value) => debouncedHandleSearch(value));

function handleCloseCreateDialog() {
  isOpenCreateDialog.value = false;
}

function openCreateDialog() {
  isOpenCreateDialog.value = true;
}

function handleClose() {
  isOpen.value = false;
}

function openDialog(task) {
  selectedTask.value = task;
  isOpen.value = true;
}

function completeTask(event, id) {
  event.stopPropagation();
  form.post(route("task.complete", id), {
    onSuccess: () => {
      toast({
        title: "Tarefa concluída com sucesso.",
      });
    },
    onError: () => {
      toast({
        title: "Erro ao concluir tarefa.",
      });
    },
  });
}

function deleteTask(event, id) {
  event.stopPropagation();
  form.delete(route("task.destroy", id), {
    onSuccess: () => {
      toast({
        title: "Tarefa excluída com sucesso.",
      });
    },
    onError: (res) => {
      toast({
        title: res.message,
      });
    },
  });
}
</script>

<template>
  <Head title="Tarefas" />
  <AuthenticatedLayout>
    <TaskDialog
      :isOpen="isOpen"
      :task="selectedTask"
      :handleClose="handleClose"
      :users="users"
    />
    <CreateTaskDialog
      :users="users"
      :isOpen="isOpenCreateDialog"
      :handleClose="handleCloseCreateDialog"
    />
    <Toaster />
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Tarefas
        </h2>
        <Button
          v-if="$page.props.auth.user.is_admin"
          @Click="openCreateDialog()"
          ><PlusIcon class="w-5 h-5 mr-1 text-white" /> Nova tarefa</Button
        >
      </div>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-2">
        <div class="flex justify-end bg-gray-200 p-6 rounded-lg mb-4">
          <Select
            id="selectedFilter"
            name="selectedFilter"
            v-model="selectedFilter"
          >
            <SelectTrigger class="w-[180px]">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem class="cursor-pointer" value="all">
                  Todas as tarefas
                </SelectItem>
                <SelectItem class="cursor-pointer" value="concluída">
                  Tarefas concluídas
                </SelectItem>
                <SelectItem class="cursor-pointer" value="pendente">
                  Tarefas pendentes
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
          <div class="relative w-80 max-w-sm items-center">
            <Input
              v-model="searchTerm"
              name="search"
              id="search"
              type="text"
              placeholder="Search..."
              class="pl-10"
            />
            <span
              class="absolute start-0 inset-y-0 flex items-center justify-center px-2"
            >
              <MagnifyingGlassIcon class="size-6 text-muted-foreground" />
            </span>
          </div>
        </div>
        <!-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg"> -->
        <Card
          v-for="task in tasks.data"
          @click="openDialog(task)"
          class="cursor-pointer hover:bg-gray-200 transition duration-300"
          :class="{
            'bg-green-100 hover:bg-green-200': task.status === 'concluída',
          }"
        >
          <CardHeader class="flex flex-row items-center justify-between py-4">
            <div>
              <CardTitle class="first-letter:uppercase">
                {{ task.title }}
              </CardTitle>
            </div>
            <div class="flex flex-row">
              <CheckCircledIcon
                v-if="task.status !== 'concluída'"
                class="mr-2 h-6 w-6 text-green-500 cursor-pointer"
                @click="completeTask($event, task.id)"
              />
              <TrashIcon
                class="h-6 w-6 text-red-500 cursor-pointer"
                @click="deleteTask($event, task.id)"
              />
            </div>
          </CardHeader>
          <CardFooter class="flex justify-end gap-4 text-gray-600 text-xs pb-4">
            <span
              ><b>Status: </b>
              <span class="capitalize">{{ task.status }}</span></span
            >
            <span
              ><b>Prioridade: </b>
              <span class="capitalize">{{ task.priority }}</span></span
            >
            <span>
              <b>Vencimento: </b>
              {{ new Date(task.due_date).toLocaleDateString("pt-br") }}
            </span>
          </CardFooter>
        </Card>
        <!-- </div> -->
        <Pagination
          v-slot="{ page }"
          :total="tasks.total"
          :sibling-count="1"
          show-edges
          :default-page="tasks.current_page"
          :items-per-page="tasks.per_page"
          class="flex justify-end"
        >
          <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst @click="router.visit(tasks.first_page_url)" />
            <PaginationPrev
              @click="router.visit(tasks.prev_page_url)"
              :disabled="page === 1"
            />

            <template v-for="(item, index) in items" :key="index">
              <PaginationListItem
                :value="item.value"
                as-child
              >
                <Button
                  class="w-10 h-10 p-0"
                  :variant="
                    item.value === tasks.current_page ? 'default' : 'outline'
                  "
                  @click="
                    () => {
                      router.visit(
                        tasks.links.find((link) => item.value == link.label).url
                      );
                    }
                  "
                >
                  {{ item.value }}
                </Button>
              </PaginationListItem>
            </template>

            <PaginationNext
              @click="router.visit(tasks.next_page_url)"
              :disabled="page === tasks.last_page"
            />
            <PaginationLast @click="router.visit(tasks.last_page_url)" />
          </PaginationList>
        </Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
