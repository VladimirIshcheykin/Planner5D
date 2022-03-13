<?php
$id_object = $this->get('id_object');
$item = $this->get('item');
$images = $this->get('images');
?>

<div class="back">
    <a onclick="history.back();">Back</a>
</div>

<h1><?= $item['name'] ?></h1>

<div class="detail">

    <div class="views">Views: <b><?= $item['views'] ?></b></div>

    <div class="image">
        <div class="image-large">
            <img id="image-large" src="<?= $item['image_large_url'] ?>">
        </div>
        <div class="images-small">
        <?php foreach ($images as $image): ?>
            <div class=item><img class="img-preview" data="<?= $image['image_large_url'] ?>" src="<?= $image['image_small_url'] ?>"></div>
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