<div id="vue-gallery"></div>

<?php
$this->registerJsFile(
    '@web/js/vue/app.js', // @web для браузера
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<!-- Передача токена Vue для работы с API -->
<script>
    window.APP = {
        token: "<?=Yii::$app->user->identity->access_token ?>"
        }
</script>
