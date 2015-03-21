<?php namespace WeAreNotMachines\PDFMaker\Abstracts;

use WeAreNotMachines\PDFMaker\Interfaces\Identifiable;

abstract class IdentifiableEntity implements Identifiable {

	protected $id;

	public function setID($id) {
		$this->id = $id;
		return $this;
	}

	public function getID() {
		return $this->id;
	}

}