<template>
   <div class="w-full space-y-4">
    <div v-for="(medicine, index) in medicines" :key="index" class="w-full">
      <h2 class="text-sm font-medium text-neutral-grey-100">{{ medicine.name }}</h2>
      <div class="flex items-center mt-1">
        <div class="relative w-full h-3 bg-gray-200 rounded-full overflow-hidden">
          <div :style="{ width: (medicine.stock / medicine.maxStock) * 100 + '%' }" class="absolute top-0 left-0 h-full bg-secondhand-orange-300"></div>
        </div>
        <span class="ml-2 text-xs font-semibold text-neutral-black-500">{{ medicine.stock }} units</span>
      </div>
    </div>
  </div>
</template>
  
<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    medicines: {
        type: Array,
        required: true,
    },
});
</script>
  