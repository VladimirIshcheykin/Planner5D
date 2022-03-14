<?php

namespace Import;

use Models\Sources\InterfaceSource;

class ImportClear {

	private $source;

	public function __construct(InterfaceSource $source) {
		$this->source = $source->init();
	}

	public function run() {
		$sql = '
			DROP TABLE IF EXISTS `objects`;
			CREATE TABLE `objects` (
			  `id_object` int(10) UNSIGNED NOT NULL,
			  `name` varchar(250) NOT NULL,
			  `url` text NOT NULL,
			  `image_preview_url` text NOT NULL,
			  `views` int(11) NOT NULL,
			  `image_small_url` text NOT NULL,
			  `image_large_url` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			ALTER TABLE `objects` ADD PRIMARY KEY (`id_object`);
			ALTER TABLE `objects` MODIFY `id_object` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		';
		$this->source->multiQuery($sql);
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
		$this->source->multiQuery($sql);
		echo 'Table object_images created' . "\n";
		echo '<br>';
	}

}