<?php
$id_object = $this->get('id_object');
$item = $this->get('item');
$images = $this->get('images');
$url_image = $this->get('url_image');
?>

<div class="back">
    <a onclick="history.back();">Назад</a>
</div>

<h1><?= $item['name'] ?></h1>

<div class="detail">

    <div class="views">Views: <b><?= $item['views'] ?></b></div>

    <div class="image">
        <div class="image-large">
            <img id="image-large" src="<?= $url_image . $id_object . '/' . $item['id_object_image'] . '_large.jpg' ?>">
        </div>
        <div class="images-small">
        <?php foreach ($images as $image): ?>
            <div class=item><img class="img-preview" data="<?= $url_image . $id_object . '/' . $image['id_object_image'] . '_large.jpg' ?>" src="<?= $url_image . $id_object . '/' . $image['id_object_image'] . '_small.jpg' ?>"></div>
        <?php endforeach; ?>
            <div class="item empty"></div>
            <div class="item empty"></div>
            <div class="item empty"></div>
            <div class="item empty"></div>
            <div class="item empty"></div>
            <div class="item empty"></div>
            <div class="item empty"></div>
        </div>
    </div>

</div>


<script>
    $(function(){
        $('.img-preview').click(function(){
            $('#image-large').attr('src', this.getAttribute('data'));
        });
    });
</script>