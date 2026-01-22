
<template>
    <div class="img-grid">
        <form @submit.prevent="uploadImage">
            <input type="file" @change="onFileChange" />
            <input type="text" v-model="userFileName" placeholder="Имя файла" />
            <button type="submit">Загрузить</button>
         </form>
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
                file: null,
                userFileName: '',
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
        },
        methods: {
            onFileChange(event){
                this.file = event.target.files[0];
            },
            async uploadImage() {
                if(!this.file) return;
                
                const formData = new FormData();
                formData.append('image',this.file);
                formData.append('user_file_name', this.userFileName);

                try {
                    const response = await axios.post('/api/upload', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                console.log('Успех:', response.data);
                } catch (err) {
                    console.error('Ошибка:', err.response?.data || err.message);
                }
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