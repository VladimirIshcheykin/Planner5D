<?php

namespace Models\Objects;

class CurlObjectClass extends AbstractObjectClass {

	public function getObjectFromSource() {
		if (empty($this->source)) return;

		if (preg_match('/<h1[^>]+>([^<]+)<\/h1>/', $this->source, $m)) {
			$this->params['name'] = $m[1];
		}

		if (preg_match('/<span[^class]+class="icon">[^<]+<i class="fas fa-eye"><\/i>[^<]+<\/span>[^<]+<span>([^<]+)<\/span>/', $this->source, $m)) {
			$this->params['views'] = $m[1];
		}

		preg_match_all('/data-fancybox="gallery"[^<]+<img[^data]+data="([^"]+)"[^src]+src="([^"]+)"/', $this->source, $m);
		foreach ($m[1] as $i => $v) {
			//$this->params['images'][] = ['image_large' => isset($m[1][$i])?$m[1][$i]:'', 'image_small' => isset($m[2][$i])?$m[2][$i]:''];
			$this->params['image_large_url'] = isset($m[1][$i])?$m[1][$i]:'';
			$this->params['image_small_url'] = isset($m[2][$i])?$m[2][$i]:'';
		}

		//var_dump($this->params);
		//echo '<br>';

		//echo 'Object params ('.$this->source->getPage().') recieved' . "\n";
		//echo '<br>';

		// получили объект
	}

}
