<script setup>
import { onMounted, ref } from 'vue';
defineProps({
    modelValue: {
        required: true,
    },
});

defineEmits(['update:modelValue']);
const input = ref(null);
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>