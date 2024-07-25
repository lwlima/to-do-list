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
import { today } from "@internationalized/date";
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
} from "@internationalized/date";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { cn } from "@/Lib/utils";
import { Button } from "@/Components/ui/button";
import { toast } from "@/Components/ui/toast";
import DialogClose from "@/Components/ui/dialog/DialogClose.vue";

defineProps({
  users: Array,
  isOpen: Boolean,
  handleClose: Function,
});

const df = new DateFormatter("pt-BR", {
  dateStyle: "long",
});

const dueDate = ref<DateValue>();

const title = ref("");
const description = ref("");
const priority = ref("");
const userId = ref("");
const form = useForm({});

function resetForm() {
  title.value = "";
  description.value = "";
  priority.value = "";
  userId.value = "";
  dueDate.value = null;
}

function createTask() {
  form.post(
    route("task.store", {
      title: title.value,
      description: description.value,
      priority: priority.value,
      due_date: dueDate.value.toString(),
      user_id: userId.value,
    }),
    {
      onSuccess: () => {
        toast({
          title: "Tarefa criada com sucesso.",
        });
        this.handleClose();
        resetForm();
      },
      onError: () => {
        toast({
          title: "Erro ao criar tarefa.",
        });
      },
    }
  );
}
</script>

<template>
  <Dialog :open="isOpen" @update:open="handleClose()">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Nova Tarefa</DialogTitle>
        <DialogDescription>
          Preencha os detalhes abaixo para criar uma nova tarefa.
        </DialogDescription>
      </DialogHeader>

      <div>
        <Label for="title">Titulo</Label>
        <Input name="title" id="title" type="text" v-model="title" />
      </div>
      <div>
        <Label for="description">Descrição</Label>
        <Textarea name="description" id="description" v-model="description"> </Textarea>
      </div>

      <div>
        <Select name="user" id="user" v-model="userId">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Selecione um usuário" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="user in users"
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
        <Select name="priority" id="priority" v-model="priority">
          <SelectTrigger class="w-[120px]">
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

        <Popover>
          <PopoverTrigger as-child>
            <Button
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
                  : "Selecione uma data"
              }}
            </Button>
          </PopoverTrigger>
          <PopoverContent class="w-auto p-0">
            <Calendar
              v-model="dueDate"
              initial-focus
              :min-value="today(getLocalTimeZone())"
            />
          </PopoverContent>
        </Popover>
      </div>

      <DialogFooter>
        <DialogClose>
          <Button @click="createTask"> Criar tarefa </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
