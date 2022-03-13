<?php
$items = $this->get('items');
$url = $this->get('url');
?>

<h1>Planeer5d Gallery</h1>

<?php
	$this->pager->display();
?>

<div class="list">
<?php foreach ($items as $item): ?>
	<div class="item">
		<a class="img" href="<?= $url ?>?id=<?= $item['id_object'] ?>">
			<img src="<?= $item['image_preview_url'] ?>">
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


