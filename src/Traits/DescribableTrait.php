<?php namespace WeAreNotMachines\PDFMaker\Traits;

trait DescribableTrait {

	protected $description;

	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	public function getDescription() {
		return $this->description;
	}


}