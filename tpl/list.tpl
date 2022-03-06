<?php
$items = $this->get('items');
$url = $this->get('url');
$url_image = $this->get('url_image');
?>

<h1>Planeer5d Gallery</h1>

<?php
$this->pager->display();
?>

<div class="list">
<?php foreach ($items as $item): ?>
	<div class="item">
		<a class="img" href="<?= $url ?>?id=<?= $item['id_object'] ?>">
			<img src="<?= $url_image . $item['id_object'] . '_preview.jpg' ?>">
		</a>
		<a class="name" href="<?= $url ?>?id=<?= $item['id_object'] ?>"><?= $item['name'] ?></a>
		<span class="views">Views: <b><?= $item['views'] ?></b></span>
	</div>
<?php endforeach; ?>
	<div class="item empty"></div>
	<div class="item empty"></div>
	<div class="item empty"></div>
	<div class="item empty"></div>
	<div class="item empty"></div>
</div>

<?php
$this->pager->display();
?>


