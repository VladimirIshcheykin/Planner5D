<?php

ini_set('max_execution_time', 3600);

require_once(dirname(__FILE__) . '/system/define.php');
require_once(dirname(__FILE__) . '/system/require.php');

class ImportPlanner5D {
	private $max_pages = 3; //
	private $objects = []; //

	function run() {

		echo 'Import objects - Start' . "\n";
		echo '<br>';
		$page = 1;
		do {
			echo 'Page '.P_URL.(($page > 1)?'?page='.$page:'').' proceeding' . "\n";
			$this->getObjects(P_URL.(($page > 1)?'?page='.$page:''));
			++$page;
		} while ($page <= $this->max_pages);
		echo 'Import objects - End' . "\n";
		echo '<br>';
		echo 'Count imported objects - ' . count($this->objects) . "\n";
		echo '<br>';

		echo 'Save to DB - Start' . "\n";
		echo '<br>';
		$this->saveToDB();
		echo 'Save to DB - End' . "\n";
		echo '<br>';

		echo 'Download images - Start' . "\n";
		echo '<br>';
		$this->saveImages();
		echo 'Download images - End' . "\n";
		echo '<br>';

	}

	function getObjects($url) {

		$html = $this->getFile($url);

		preg_match_all('|<img[^>]+>|', $html, $m);
		foreach ($m[0] as $image) {
			if (preg_match('|src="'.P_IMAGE_PREFIX.'([^"]+)"|', $image, $im)) {
				$params['image_preview'] = P_IMAGE_PREFIX.$im[1];
				$params['link'] = $this->getImageLink($image, $html);
				$params = array_merge($params, $this->getObjectParams($params['link']));
				$this->objects[] = $params;
			}
		}

	}

	function getImageLink($img, $html) {

		if (empty($img)) return '';

		$href = '';
		if (preg_match('|href="([^"]+)"[^<]+'.$img.'|', $html, $m)) {
			$href = $m[1];
		}

		return $href;

	}

	function getObjectParams($url) {
		if (empty($url)) return '';

		$params = [];
		$html = $this->getFile($url);
		if (preg_match('/<h1[^>]+>([^<]+)<\/h1>/', $html, $m)) {
			$params['name'] = $m[1];
		}
		//if (preg_match('/<figure[^>]+>[^<]+<img[^src]+src="([^"]+)"/', $html, $m)) {
		//	$params['image_large'] = $m[1];
		//}
		if (preg_match('/<span[^class]+class="icon">[^<]+<i class="fas fa-eye"><\/i>[^<]+<\/span>[^<]+<span>([^<]+)<\/span>/', $html, $m)) {
			$params['views'] = $m[1];
		}

		preg_match_all('/data-fancybox="gallery"[^<]+<img[^data]+data="([^"]+)"[^src]+src="([^"]+)"/', $html, $m);
		foreach ($m[1] as $i => $v) {
			$params['images'][] = ['image_large' => isset($m[1][$i])?$m[1][$i]:'', 'image_small' => isset($m[2][$i])?$m[2][$i]:''];
		}

		echo 'Object params ('.$url.') recieved' . "\n";
		echo '<br>';

		return $params;
	}

	function getFile($url) {
		if (empty($url)) return '';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	function saveImage($url, $path){
		if (empty($url)) return '';

		if (file_exists($path)) unlink($path);
		file_put_contents($path, $this->getFile($url));
	}

	function saveToDB() {

		$sql = '
			DROP TABLE IF EXISTS `objects`;
			CREATE TABLE `objects` (
			  `id_object` int(10) UNSIGNED NOT NULL,
			  `name` varchar(250) NOT NULL,
			  `url` text NOT NULL,
			  `image_preview_url` text NOT NULL,
			  `views` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			ALTER TABLE `objects` ADD PRIMARY KEY (`id_object`);
			ALTER TABLE `objects` MODIFY `id_object` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		';
		DBDriver::getDriver()->multiQuery($sql);
		echo 'Table object created' . "\n";
		echo '<br>';

		$sql = '
			DROP TABLE IF EXISTS `object_images`;
			CREATE TABLE `object_images` (
			  `id_object_image` int(10) UNSIGNED NOT NULL,
			  `id_object` int(10) UNSIGNED NOT NULL,
			  `image_small_url` text NOT NULL,
			  `image_large_url` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			ALTER TABLE `object_images` ADD PRIMARY KEY (`id_object_image`), ADD KEY `id_object` (`id_object`);
			ALTER TABLE `object_images` MODIFY `id_object_image` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		';
		DBDriver::getDriver()->multiQuery($sql);
		echo 'Table object_images created' . "\n";
		echo '<br>';

		$sql_objects = 'insert into objects (name, url, image_preview_url, views) values ("%s", "%s", "%s", %d)';
		$sql_object_images = 'insert into object_images (id_object, image_small_url, image_large_url) values (%d, "%s", "%s")';
		$id_objects = [];
		foreach ($this->objects as $k => $object) {
			$id_object = DBDriver::getDriver()->simpleQuery(sprintf($sql_objects, $object['name'], $object['link'], $object['image_preview'], $object['views']));
			$id_objects[] = $id_object;
			echo 'Object '.$id_object.' saved' . "\n";
			echo '<br>';
			if (isset($object['images'])) {
				$id_object_images = [];
				foreach($object['images'] as $image) {
					$id_object_image = DBDriver::getDriver()->simpleQuery(sprintf($sql_object_images, $id_object, $image['image_small'], $image['image_large']));
					$id_object_images[] = $id_object_image;
					echo 'Object image '.$id_object_image.' saved' . "\n";
					echo '<br>';
				}
				$this->objects[$k]['images'] = array_combine($id_object_images, $this->objects[$k]['images']);
			}
		}
		$this->objects = array_combine($id_objects, $this->objects);

	}

	function saveImages() {
		foreach ($this->objects as $id_object => $object) {
			$this->saveImage($object['image_preview'], IMAGE_PATH . $id_object . '_preview.jpg');
			foreach ($object['images'] as $id_object_image => $image) {
				if (!is_dir(IMAGE_PATH . $id_object))
					mkdir(IMAGE_PATH . $id_object);
				$this->saveImage($image['image_large'], IMAGE_PATH . $id_object . '/' . $id_object_image . '_large.jpg');
				$this->saveImage($image['image_small'], IMAGE_PATH . $id_object . '/' . $id_object_image . '_small.jpg');
			}
			echo 'Object '.$id_object.' finished' . "\n";
			echo '<br>';
		}
	}

}


$starttime = time();

try {
	(new ImportPlanner5D())->run();
} catch (Exception $e){
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}

$endtime = time();
echo 'START '.date("d.m.Y - H:i:s", $starttime) . "\n";
echo '<br>';
echo 'END '.date("d.m.Y - H:i:s", $endtime) . "\n";
echo '<br>';
echo "Lifetime = " . ($endtime-$starttime) . " sec\n";
echo '<br>';
