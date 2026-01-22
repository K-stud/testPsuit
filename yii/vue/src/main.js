import Vue from 'vue';
import ImageGallery from './components/ImageGallery.vue';

new Vue({
  el: '#vue-gallery',
  render: h => {
    return h(ImageGallery);
  }
});
