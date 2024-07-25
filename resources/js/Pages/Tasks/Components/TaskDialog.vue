<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/Components/ui/popover";
import { Calendar } from "@/Components/ui/calendar";
import { CalendarIcon, PlusIcon } from "@radix-icons/vue";
import {
  DateFormatter,
  type DateValue,
  getLocalTimeZone,
  CalendarDate,
} from "@internationalized/date";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { cn } from "@/Lib/utils";
import { Button } from "@/Components/ui/button";
import { toast } from "@/Components/ui/toast";

const props = defineProps({
  isOpen: Boolean,
  handleClose: Function,
  task: Object,
  users: Array,
});

const df = new DateFormatter("pt-BR", {
  dateStyle: "long",
});

const dueDate = ref<DateValue>();
const id = ref("");
const title = ref("");
const description = ref("");
const priority = ref("");
const userId = ref("");

watch(
  () => props.task,
  (task) => {
    const date = new Date(task.due_date);
    id.value = task.id;
    title.value = task.title;
    description.value = task.description;
    priority.value = task.priority;
    userId.value = task.user_id.toString();
    dueDate.value = new CalendarDate(
      date.getFullYear(),
      date.getMonth() + 1,
      date.getDate()
    );
  }
);

function updateTask() {
  form.put(
    route("task.update", {
      title: title.value,
      id: id.value,
      description: description.value,
      priority: priority.value,
      user_id: userId.value,
      due_date: dueDate.value.toString(),
    }),
    {
      onSuccess: () => {
        toast({
          title: "Tarefa atualizada com sucesso.",
        });
      },
      onError: (res) => {
        toast({
          title: "Erro ao atualizar tarefa.",
        });
      },
    }
  );
}

const form = useForm({});

function createTask() {}
</script>

<template>
  <Dialog :open="isOpen" @update:open="handleClose()">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>
          <span v-if="$page.props.auth.user.is_admin">Editar Tarefa</span>
          <span v-else>Detalhes da Tarefa</span>
        </DialogTitle>
        <DialogDescription>
          <span v-if="$page.props.auth.user.is_admin">
            Atualize os detalhes da tarefa abaixo conforme necessário.
          </span>
          <span v-else> Visualize os dados e informações da tarefa. </span>
        </DialogDescription>
      </DialogHeader>

      <div>
        <Label for="title">Titulo</Label>
        <Input
          name="title"
          id="title"
          type="text"
          v-model="title"
          :disabled="!$page.props.auth.user.is_admin"
        />
      </div>
      <div>
        <Label for="description">Descrição</Label>
        <Textarea
          name="description"
          id="description"
          v-model="description"
          :disabled="!$page.props.auth.user.is_admin"
        >
        </Textarea>
      </div>

      <div v-if="$page.props.auth.user.is_admin">
        <Label for="user">Usuário</Label>
        <Select name="user" id="user" v-model="userId">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Selecione um usuário" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="user in props.users"
                :key="user.id"
                class="cursor-pointer"
                :value="user.id.toString()"
              >
                {{ user.email }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </div>

      <div class="flex gap-4">
        <div class="flex flex-col">
          <Label for="priority">Prioridade</Label>
          <Select id="priority" name="priority" v-model="priority">
            <SelectTrigger
              class="w-[120px]"
              :disabled="!$page.props.auth.user.is_admin"
            >
              <SelectValue placeholder="Prioridade" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem class="cursor-pointer" value="baixa">
                  Baixa
                </SelectItem>
                <SelectItem class="cursor-pointer" value="média">
                  Média
                </SelectItem>
                <SelectItem class="cursor-pointer" value="alta">
                  Alta
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>

        <div class="flex flex-col">
          <Label for="dueDate">Vencimento</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                :disabled="!$page.props.auth.user.is_admin"
                variant="outline"
                :class="
                  cn(
                    'w-[220px] justify-start text-left font-normal',
                    !dueDate && 'text-muted-foreground'
                  )
                "
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{
                  dueDate
                    ? df.format(dueDate.toDate(getLocalTimeZone()))
                    : df.format(new Date(task.due_date))
                }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar v-model="dueDate" initial-focus />
            </PopoverContent>
          </Popover>
        </div>
      </div>

      <DialogFooter>
        <Button v-if="$page.props.auth.user.is_admin" @click="updateTask()">
          Salvar alterações
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
