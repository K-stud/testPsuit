
<template>
    <div class="img-grid">
        <div class="img-container" v-for="image in images" :key="image.id">
            <p>{{ image.id }}, {{ image.file_name }}</p>
        </div>
    </div>  
</template>

<script>
    import axios from 'axios'; // Для отправки запросов

    export default {
        data() {
            return {
                images: []
            }
        },
        mounted() {
            axios.get('/api/gallery')
                .then(response => {
                    this.images = response.data;
                })
                .catch(error => {
                    console.error('Ошибка загрузки изображений:');
                    if (error.response) {
                        console.log(error.response.data);  // реальное сообщение от сервера
                    } else {
                        console.log(error.message);
                }
            });
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