<?php

namespace Models\Lists;

class CurlObjectsListClass extends AbstractListClass {

	protected function getListFromSource() {

		preg_match_all('|<img[^>]+>|', $this->source, $m);
		foreach ($m[0] as $image) {
			if (preg_match('|src="'.P_IMAGE_PREFIX.'([^"]+)"|', $image, $im)) {
				$params['image_preview_url'] = P_IMAGE_PREFIX.$im[1];
				$params['url'] = $this->getImageLink($image);
				$params['name'] = $this->getName($params['url']);
				$params['views'] = $this->getViews($image);
				$this->list[] = $params;
			}
		}

	}

	private function getName($link) {

		if (empty($link)) return '';

		$name = '';
		if (preg_match('|<h3>[^<]+<a href="'.$link.'">([^<]+)<\/a>[^<]+<\/h3>|', $this->source, $m)) {
			$name = trim($m[1]);
		}

		return $name;

	}

	private function getImageLink($img) {

		if (empty($img)) return '';

		$href = '';
		if (preg_match('|href="([^"]+)"[^<]+'.$img.'|', $this->source, $m)) {
			$href = $m[1];
		}

		return $href;

	}

	private function getViews($img) {

		return 0;

	}

}

