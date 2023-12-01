<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    autocomplete: {
        type: String,
        required: false,
    },
    id: {
        type: String,
        required: false,
    },
    leftIcon: {
        type: Boolean,
        default: false,
    },
    modelValue: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    rightIcon: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        required: false,
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
    <div class="relative p-0 rounded-xl border-none text-neutral-black-300">
        <div v-if=leftIcon class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
            <slot />
        </div>
        <input :id="id" :class="{'pl-10': leftIcon, 'pr-10':rightIcon}"
            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input"
            :placeholder="placeholder" :required="required" :type="type" :autocomplete="autocomplete"/>

        <div v-if=rightIcon class="absolute inset-y-0 right-0 mx-3 w-5 h-5 my-auto">
            <slot />
        </div>
    </div>
</template>
