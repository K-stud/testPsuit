import Vue from 'vue';
import axios from 'axios';
import ImageGallery from './components/ImageGallery.vue';

if (window.APP && window.APP.token) {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.APP.token;
}

new Vue({
  el: '#vue-gallery',
  render: h => {
    return h(ImageGallery);
  }
});
