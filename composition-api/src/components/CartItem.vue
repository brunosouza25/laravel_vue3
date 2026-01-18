<script>
import { computed, reactive, toRefs, onMounted, onUnmounted, onUpdated } from "vue"
export default {
    props: {
      cartItem: {
          type: Object,
          required: true
      }
    },
    emits: ["remove"],
    setup(props, { emit }) {
        const item = reactive(props.cartItem)

        const increment = () => item.quantity++
        const decrement = () => item.quantity--

        const { name, price, quantity } = toRefs(item)

        const total = computed(() => item.price * quantity.value)

        const remove = () => emit("remove", item)

        onMounted(() => {
            console.log("component mounted.")
        })

        onUpdated(() => {
            console.log("component updated.")
        })

        onUnmounted(() => {
            console.log("component unmonted.")
        })

        return {
            remove,
            quantity,
            increment,
            decrement,
            name,
            price,
            total
        }
    }
}
</script>

<template>
    <h1>{{ name }} : {{ price }} : {{ quantity }}</h1>

    <button @click="increment">+</button>
    <button @click="decrement">-</button>
    <br/>
    <button @click="remove">Remove</button>

    <h3>Total: {{ total }}</h3>


</template>

<style scoped></style>
