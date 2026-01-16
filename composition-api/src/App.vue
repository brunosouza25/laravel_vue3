<script>
import { ref, reactive, toRef, toRefs } from "vue"
export default {
    setup() {
        const message = ref("hello");
        const quantity = ref(1);
        const item = reactive({
            name: "product 1",
            price: 10
        })

        const increment = () => quantity.value++
        const decrement = () => quantity.value--

        const swapProduct = () => {
            item.name = "product A"
            item.price = 30
        }

        // const nameRef = toRef(item, "name");
        //
        // console.log("nameref:", nameRef.value)
        //
        // item.name = "new product"
        //
        // console.log("nameref:", nameRef.value)

        const { name, price } = toRefs(item)

        // const itemRefs = toRefs(item)
        // console.log("name:", itemRefs.name.value)
        // console.log("price:", itemRefs.price.value)
        // itemRefs.name.value = "product 2"
        // itemRefs.price.value = 40
        // console.log("name:", itemRefs.name.value)
        // console.log("price:", itemRefs.price.value)

        return {
            message,
            quantity,
            increment,
            decrement,
            name,
            price,
            swapProduct
        }
    }
}
</script>

<template>
    <h1>{{ name }} : {{ price }}</h1>
    <h2>{{ quantity }}</h2>
    <button @click="swapProduct">Swap</button>
    <button @click="increment">+</button>
    <button @click="decrement">-</button>


</template>

<style scoped></style>
