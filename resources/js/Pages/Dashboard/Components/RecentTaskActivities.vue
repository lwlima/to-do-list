<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

const props = defineProps({
  data: Array,
});
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center" v-for="activity in data">
      <Avatar class="h-9 w-9">
        <AvatarFallback class="uppercase">
          <span v-if="activity.user_id">
            {{ activity.user.name.charAt(0) }}
          </span>
          <span v-else>{{ "Sistema".charAt(0) }}</span>
        </AvatarFallback>
      </Avatar>
      <div class="ml-4 space-y-1">
        <p class="text-sm font-medium leading-none first-letter:uppercase">
          <span v-if="activity.user_id">{{ activity.user.name }}</span>
          <span v-else>Sistema</span>
        </p>
      </div>
      &nbsp
      <div class="text-sm" v-if="activity.event === 'created'">
        criou uma tarefa de <b>id:</b> {{ activity.task_id }}
      </div>
      <div class="text-sm" v-if="activity.event === 'updated'">
        alterou a tarefa de <b>id:</b> {{ activity.task_id }}
        <b>valores antigos:</b> {{ activity.details.old }}
        <b>valores novos:</b> {{ activity.details.new }}
      </div>
      <div class="text-sm" v-if="activity.event === 'deleted'">
        removeu a tarefa de <b>id:</b> {{ activity.task_id }}
      </div>
      <i class="text-sm text-gray-5s00"
        >. - Alterado em
        {{
          new Date(activity.created_at).toLocaleDateString("pt-BR", {
            timeZone: "UTC",
          })
        }}
      </i>
    </div>
  </div>
</template>
