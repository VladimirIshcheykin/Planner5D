<?php

namespace Models\Lists;

class CurlImagesListClass extends AbstractListClass {

	protected function getListFromSource() {
		if (empty($this->source)) return;

		$list = [];
		preg_match_all('/data-fancybox="gallery"[^<]+<img[^data]+data="([^"]+)"[^src]+src="([^"]+)"/', $this->source, $m);
		foreach ($m[1] as $i => $v) {
			$list[] = ['image_large_url' => isset($m[1][$i])?$m[1][$i]:'', 'image_small_url' => isset($m[2][$i])?$m[2][$i]:''];
		}
		$this->list = $list;

		//var_dump($this->list);
		//echo '<br>';

		//echo 'Object params ('.$this->source.') recieved' . "\n";
		//echo '<br>';

		// получили объект
	}

}

