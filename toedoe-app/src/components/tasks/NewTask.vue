<template>
    <DatePicker v-model="newTask.due_date"
                :popover="{ placement: 'bottom-end' }"
                :min-date="new Date()"
                @update:model-value="focusInput">
        <template #default="{ togglePopover }">
            <div class="relative">
                <div class="input-highlighter" v-html="highlightedText" ref="highlighterRef"></div>
                <input
                    type="text"
                    class="form-control form-control-lg padding-right-lg task-input"
                    placeholder="+ Add new task. Press enter to save."
                    spellcheck="false"
                    @keydown.enter="addNewTask"
                    v-model="taskInput"
                    ref="inputRef"
                    @scroll="syncScroll"
                />
            </div>
        </template>

    </DatePicker>
</template>

<script setup>
import { reactive, ref, computed } from "vue"
import { useTaskStore } from "../../stores/task.js"
import SelectPriority from "./SelectPriority.vue";
import { DatePicker } from "v-calendar";
import 'v-calendar/style.css';
import IconCalendar from "@/components/icons/IconCalendar.vue";
import { useFocusInput } from "../../composables/useFocusInput";
import { useDateFormatter } from "../../composables/useDateFormatter";

const highlighterRef = ref();
const taskInput = ref('');

const syncScroll = (event) => {
    if (highlighterRef.value) {
        highlighterRef.value.scrollLeft = event.target.scrollLeft;
    }
}

const highlightedText = computed(() => {
    let text = taskInput.value || '';
    text = text.replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

    text = text.replace(/(^|\s)(!high)(?=\s|$)/gi, '$1<span class="highlight-priority highlight-high">$2</span>');
    text = text.replace(/(^|\s)(!medium)(?=\s|$)/gi, '$1<span class="highlight-priority highlight-medium">$2</span>');
    text = text.replace(/(^|\s)(!low)(?=\s|$)/gi, '$1<span class="highlight-priority highlight-low">$2</span>');
    text = text.replace(/(^|\s)(@(today|tomorrow|nextweek|next\d+d|\d{4}-\d{2}-\d{2}))(?=\s|$)/gi, '$1<span class="highlight-date">$2</span>');

    return text;
})
const inputRef = ref()


const { focusInput } = useFocusInput(inputRef);
const { formatDateLocal } = useDateFormatter();
const store = useTaskStore();
const { handleAddedTask } = store
const newTask = reactive({
    name: "",
    is_completed: false,
    priority_id: null,
    due_date: null,
});

const addNewTask = async (event) => {
    if (taskInput.value.trim()) {
        newTask.name = taskInput.value;
        newTask.due_date = formatDateLocal(newTask.due_date);
        taskInput.value = '';
        await handleAddedTask(newTask);
    }
};


const setPriority = (id) => {
    newTask.priority_id = id;
    inputRef.value.focus();
};

const onDateSelected = () => {
    nextTick(() => {
        inputRef.value?.focus();
    });
};



</script>
<style>
.relative {
    position: relative;
}

.select-priority {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 10px;
    padding-left: 10px;
    z-index: 999;
}

.action-buttons {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 10px;
    padding-left: 10px;
    z-index: 999;
    display: flex;
    align-items: center;
    gap: 5px;
}



</style>

<style scoped>
.input-highlighter {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0.5rem 1rem;
    margin: 0;
    border: 1px solid transparent;
    border-radius: 0.5rem;
    font-family: inherit;
    font-size: 1.25rem;
    line-height: 1.5;
    letter-spacing: inherit;
    color: #212529;
    pointer-events: none;
    white-space: pre;
    overflow: hidden;
    z-index: 2;
    background-color: transparent;
    box-sizing: border-box;
}

.task-input {
    position: relative;
    z-index: 1;
    background-color: #fff;
    color: transparent;
    caret-color: #212529;
}

.task-input::placeholder {
    color: #6c757d;
    opacity: 1;
}

:deep(.highlight-priority) {
    border-radius: 2px;
    padding: 0;
    font-weight: normal;
}

:deep(.highlight-high) {
    color: #dc3545;
    background-color: #f8d7da;
}

:deep(.highlight-medium) {
    color: #ffc107;
    background-color: #fff3cd;
}

:deep(.highlight-low) {
    color: #0d6efd;
    background-color: #cfe2ff;
}

:deep(.highlight-date) {
    color: #0dcaf0;
    background-color: #cff4fc;
    border-radius: 2px;
    padding: 0;
}

</style>
