<?php namespace WeAreNotMachines\PDFMaker\Traits;

trait TimestampableTrait {

	private $created;
	private $updated;

	public function touch() {

		if (empty($this->created)) {
			$this->created = new \DateTime();
		}
		$this->updated = new \DateTime();

		return $this;

	}

	public function getCreated() {
		return $this->created;
	}

	public function getCreatedDate($format="d/m/Y g:ia") {
		return $this->created->format($format);
	}

	public function getUpdated() {
		return $this->updated;
	}

	public function getUpdatedDate($format="d/m/Y g:ia") {
		return $this->updated->format($format);
	}

	public function getCreatedDateForSave() {
		return $this->created->format("Y-m-d H:i:s");
	}

	public function getUpdatedDateForSave() {
		return $this->updated->format("Y-m-d H:i:s");
	}

	public function setCreated($createdDate) {
		$this->created = new \DateTime($createdDate);
		return $this;
	}

	public function setUpdated($updatedDate) {
		$this->updated = new \DateTime($updatedDate);
		return $this;
	}

}