<script>
import { computed, ref, reactive, toRef, toRefs, watch, watchEffect } from "vue"
export default {
    setup() {
        const item = reactive({
            name: "product 1",
            price: 10,
            quantity: 1
        })

        const increment = () => item.quantity++
        const decrement = () => item.quantity--

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

        const { name, price, quantity } = toRefs(item)



        // const itemRefs = toRefs(item)
        // console.log("name:", itemRefs.name.value)
        // console.log("price:", itemRefs.price.value)
        // itemRefs.name.value = "product 2"
        // itemRefs.price.value = 40
        // console.log("name:", itemRefs.name.value)
        // console.log("price:", itemRefs.price.value)

        const total = computed(() => item.price * quantity.value)

        watch(() => item.quantity, () => {
            if (item.quantity === 5){
                alert("you cannot add more item")
            }
        }, { immediate: true})

        watchEffect(() => {
            console.log("price changed", item.price)
        })

        return {
            quantity,
            increment,
            decrement,
            name,
            price,
            swapProduct,
            total
        }
    }
}
</script>

<template>
    <h1>{{ name }} : {{ price }}</h1>
    <h2>{{ quantity }}</h2>
    <button @click="swapProduct">Swap</button>
    <button @click="price++">increment price</button>
    <button @click="increment">+</button>
    <button @click="decrement">-</button>

    <h3>Total: {{ total }}</h3>


</template>

<style scoped></style>
