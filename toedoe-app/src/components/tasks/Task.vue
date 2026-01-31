<template>
    <li class="list-group-item py-3">
        <div class="d-flex justify-content-start align-items-center">
            <input
                class="form-check-input mt-0" :class="[completedClass, priorityClass]"
                type="checkbox" :checked="task.is_completed"
                @change="markTaskAsCompleted"/>
            <div class="ms-2 flex-grow-1" :class="completedClass"
                title="Double click the text to edit or remove"
                @dblclick="$event => isEdit = true">
                <div v-if="isEdit">
                    <DatePicker
                        v-model="selectedDate"
                        :popover="{ placement: 'bottom-end' }"
                        :min-date="new Date()"
                        @update:model-value="focusInput">
                        <template #default="{ togglePopover }">
                            <div class="relative">
                                <input
                                    class="editable-task"
                                    type="text"
                                    v-focus
                                    @keyup.esc="undo"
                                    @keyup.enter="updateTask"
                                    v-model="editingTask"
                                    ref="inputRef"/>
                                <div class="action-buttons">
                                    <SelectPriority
                                        :selected="selectedPriority"
                                        @change="setPriority"/>
                                    <button class="btn btn-sm btn-light" @click="togglePopover" type="button" title="Set due date">
                                        <IconCalendar />
                                    </button>
                                </div>
                            </div>
                        </template>
                    </DatePicker>
                </div>
                <span v-else>{{ task.name }}</span>
            </div>
            <div class="task-date" v-if="!isEdit">{{ formattedDate }}</div>
        </div>
        <TaskActions @edit="$event => isEdit = true" v-show="!isEdit" @remove="removeTask" />
    </li>
</template>

<script setup>
import { computed, ref } from "vue";
import TaskActions from "./TaskActions.vue"
import SelectPriority from "./SelectPriority.vue";
import { useDateFormatter } from "../../composables/useDateFormatter";
import { useFocusInput } from "../../composables/useFocusInput";
import IconCalendar from "../icons/IconCalendar.vue";
import { DatePicker } from "v-calendar";
import 'v-calendar/style.css';

const props = defineProps({
    task: Object
})

const emit = defineEmits(["updated", "completed", "removed"])

const isEdit = ref(false)
const editingTask = ref(props.task.name)
const completedClass = computed(() => props.task.is_completed ? "completed" : "")

const vFocus = {
    mounted: (el) => el.focus()
}

const updateTask = (event) => {
    console.log('Data atual:', selectedDate.value);
    const updatedTask = {
        ...props.task,
        name: event.target.value,
        priority_id: selectedPriority.value,
        due_date: selectedDate.value ? formatDateLocal(selectedDate.value) : null
    };

    isEdit.value = false;
    emit("updated", updatedTask);
};
const undo = () => {
    isEdit.value = false;
    editingTask.value = props.task.name;
    selectedPriority.value = props.task.priority?.id || null,
    selectedDate.value = props.task.due_date ? new Date(props.task.due_date) : null
}
const formattedDate = computed(() => formatDate(selectedDate.value));
const markTaskAsCompleted = event => {
    const updatedTask = { ...props.task, is_completed: !props.task.is_completed }
    emit("completed", updatedTask)
}

const removeTask = () => {
    if (confirm("Are you sure?")){
        emit("removed", props.task)
    }
}

const priorityClass = computed(() => {

    const classesMap = {
        null: 'none',
        1: 'high',
        2: 'medium',
        3: 'low'
    }

    const activeClass = classesMap[selectedPriority.value] || 'none';

    return `priority-${activeClass}`;
});

const selectedPriority = ref(props.task.priority?.id || null)
const selectedDate = ref(props.task.due_date ? new Date(props.task.due_date) : null);

const inputRef = ref();

const { focusInput } = useFocusInput(inputRef);
const { formatDateLocal, formatDate } = useDateFormatter();
const setPriority = (id) => {
    selectedPriority.value = id
    inputRef.value.focus();
}



</script>
<style scoped>
.form-check-input:checked {
    background-color: rgb(108,117,125);
    border-color: rgb(108,117,125);
}
.form-check-input:not(:checked) {
    outline: 0;
    border: 0;
}
.priority-high:not(:checked) {
    box-shadow: 0 0 0 0.1rem rgb(220,53,69) !important;
}
.priority-medium:not(:checked) {
    box-shadow: 0 0 0 0.1rem rgb(255,193,7) !important;
}
.priority-low:not(:checked) {
    box-shadow: 0 0 0 0.1rem rgb(13,110,253) !important;
}
.priority-none:not(:checked) {
    box-shadow: 0 0 0 0.1rem rgba(0,0,0,.25) !important;
}
</style>
