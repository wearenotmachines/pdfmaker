<?php namespace WeAreNotMachines\PDFMaker;

use WeAreNotMachines\PDFMaker\Abstracts\IdentifiableEntity;
use WeAreNotMachines\PDFMaker\Interfaces\ProjectMember;
use WeAreNotMachines\PDFMaker\Traits\ProjectMembershipTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Entitlable;
use WeAreNotMachines\PDFMaker\Traits\EntitlableTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Describable;
use WeAreNotMachines\PDFMaker\Traits\DescribableTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Timestampable;
use WeAreNotMachines\PDFMaker\Traits\TimestampableTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Slugifiable;
use WeAreNotMachines\PDFMaker\Traits\SlugifiableTrait;

class PDFDocument extends IdentifiableEntity implements ProjectMember, Timestampable, Slugifiable {

	use ProjectMembershipTrait;
	use EntitlableTrait;
	use DescribableTrait;
	use TimestampableTrait;
	use SlugifiableTrait;
	
	protected $section;
	protected $filename;
	protected $path;

	public function __construct($filename, $title=null) {
		$this->filename = $filename;
		$this->title = $title;
		$this->touch();
	}

	public function getFilename() {
		return $this->filename;
	}

	public function setPath($path) {
		$this->path = $path;
		return $this;
	}

	public function getPath() {
		return $this->path;
	}

	public function getFullPath() {
		return $this->path.$this->filename;
	}

	public function getSectionID() {
		return $this->section->getID();
	}

	public function getSectionTitle() {
		return $this->section->getTitle();
	}
	
}