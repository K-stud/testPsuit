<template>
    <div class="img-grid">

            <img class="img-container" v-for="(image, index) in images" :key="index" :src="`${imagePath}${image}`" alt="Image">

    </div>  
</template>

<script>
    export default {
        data() {
            return {
                imagePath: '/testimages/',
                images: ['test1.jpeg','test2.jpeg','test3.png','test4.jpeg','test5.png']
            }
        }
    }
</script>

<style scoped>
    .img-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, 100px);
        grid-auto-rows: 100px;
        gap: 10px;
    }
    img {
        width: 100px;
        height: 100px;
        border: 5px solid #ccc; /* Optional: border */
    }
</style>