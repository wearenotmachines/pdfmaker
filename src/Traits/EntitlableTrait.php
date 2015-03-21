<?php namespace WeAreNotMachines\PDFMaker\Traits;

trait EntitlableTrait {

	protected $title;

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

}