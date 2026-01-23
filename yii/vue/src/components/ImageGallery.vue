<template>
    <div class="img-grid">
        <p v-if="message" :class="isSuccess ? 'success' : 'error'"> {{ message }} </p><br>
        <form @submit.prevent="uploadImage">
            <input type="file" @change="onFileChange" />
            <input type="text" v-model="userFileName" placeholder="Имя файла" />
            <button type="submit">Загрузить</button>
        </form><br>

        <div class="img-container" v-for="image in images" :key="image.id">
            <div>
                <img :src="image.file_path" alt="изображение" />
            </div>
            <div>
                <strong>Имя:</strong> {{ image.file_name }} <br>
                <strong>ID:</strong> {{ image.id }}
            </div>
            <div>
                <button @click="deleteImage(image.id)">Удалить</button>
            </div>
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
                images: [],
                message: '',
                isSuccess: false,
                devPath: ''
            }
        },
        mounted() {
            this.loadImages()
        },
        methods: {
            loadImages() {
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
                    this.message = response.data.message;
                    this.isSuccess = true;
                    this.loadImages()
                } catch (err) {
                    console.error('Ошибка:', err.response?.data || err.message);
                    this.isSuccess = false;
                }
            },

            async deleteImage(id) {
                try {
                    // Передаем id через query string
                    const response = await axios.delete(`/api/delete`,{
                        params: { id }
                    });
        
                    if (response.data.success) {
                        this.message = response.data.message;
                        this.isSuccess = true;
                        // Убирание не работет
                        this.images = this.images.filter(img => img.id !== id);
                        console.log(response.data.path);
                        this.loadImages();
                    } else {
                        this.message = response.data.message;
                        this.isSuccess = false;
                    }
                } catch (err) {
                    this.message = err.response?.data?.message || err.message;
                    this.isSuccess = false;
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
    .img-container {
    display: flex;
    flex-wrap: wrap;  
    gap: 10px;        
    }

    .img-container > div {
        flex: 0 1 auto;   
        width: 300px;     
        height: 300;     
    }

    .img-container img {
        max-width: 100%;   
        height: auto;      
        display: block;    
    }

    img {
        width: 300px;
        height: 300px;
        border: 3px solid #ccc; /* Optional: border */
    }
    .success {
        color: #2ecc71;
    }
    .error {
        color: #e74c3c;
    }
</style>