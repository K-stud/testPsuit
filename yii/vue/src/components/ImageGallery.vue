<template>
  <div class="container">
    <h1>Галерея</h1>
    <p v-if="message" :class="isSuccess ? 'success' : 'error'">
      {{ message }}
    </p>

    <div class="upload-section">
      <form @submit.prevent="uploadImage">
        <input type="file" @change="onFileChange" />
        <input type="text" v-model="userFileName" placeholder="Имя файла" />
        <button type="submit">Загрузить</button>
      </form>
    </div>

    <div class="cards">
      <div class="card" v-for="image in images" :key="image.id">
        
        <img :src="image.file_path" class="card-img" />

        <div class="card-info">
          <div><strong>ID:</strong> {{ image.id }}</div>
          <div><strong>Имя:</strong> {{ image.file_name }}</div>
        </div>

        <div class="card-actions">
          <button @click="deleteImage(image.id)">Удалить</button>
        </div>

        <div class="edit-form">
            <h4>Редактировать изображение</h4>
            <form @submit.prevent="updateImage(image.id)">
                <input type="text" v-model="image.file_name" placeholder="Новое имя файла" />
                <button type="submit">Сохранить</button>
            </form>
        </div>


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
                    console.log(error.response.data); 
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
                const response = await axios.delete(`/api/delete`,{
                    params: { id }
                });
    
                if (response.data.success) {
                    this.message = response.data.message;
                    this.isSuccess = true;
                    this.images = this.images.filter(img => img.id !== id);
                    this.loadImages();
                } else {
                    this.message = response.data.message;
                    this.isSuccess = false;
                }
            } catch (err) {
                this.message = err.response?.data?.message || err.message;
                this.isSuccess = false;
            }       
        },

        async updateImage(id) {
            try {
                const image = this.images.find(img => img.id === id);
                if (!image) return;

                const response = await axios.put('/api/update', {
                    id: id,
                    file_name: image.file_name
            });

            if (response.data.success) {
                this.message = response.data.message;
                this.isSuccess = true;
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

.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 15px;
}

.success {
  color: #2ecc71;
  margin-bottom: 10px;
}

.error {
  color: #e74c3c;
  margin-bottom: 10px;
}

.upload-section {
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #ccc;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.card {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 15px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.card-img {
  width: 100%;
  height: auto;
  border-radius: 4px;
}

.card-info {
  font-size: 14px;
  line-height: 1.4;
}

.card-actions {
  margin-top: 10px;
}

.card-actions button {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.card-actions button:hover {
  background: #c0392b;
}

.edit-form {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid #eee;
}

.edit-form form {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.edit-form input {
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.edit-form button {
  background: #3498db;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.edit-form button:hover {
  background: #2980b9;
}

</style>
