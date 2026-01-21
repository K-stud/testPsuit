<div id="vue-gallery"></div>

<?php
$this->registerJsFile(
    '@web/js/vue/app.js', // @web для браузера
    ['depends' => [\yii\web\JqueryAsset::class]]
);
