<?php namespace WeAreNotMachines\PDFMaker;

use WeAreNotMachines\PDFMaker\Abstracts\IdentifiableEntity;
use WeAreNotMachines\PDFMaker\Interfaces\Entitlable;
use WeAreNotMachines\PDFMaker\Traits\EntitlableTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Timestampable;
use WeAreNotMachines\PDFMaker\Traits\TimestampableTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Slugifiable;
use WeAreNotMachines\PDFMaker\Traits\SlugifiableTrait;
use WeAreNotMachines\PDFMaker\Interfaces\Describable;
use WeAreNotMachines\PDFMaker\Traits\DescribableTrait;

class PDFProject extends IdentifiableEntity implements Entitlable, Timestampable, Slugifiable, Describable {

	use EntitlableTrait;
	use TimestampableTrait;
	use SlugifiableTrait;
	use DescribableTrait;

	protected $assignable = [ "id", "title", "slug", "description" ];


	public function __construct($props=array()) {
		$this->touch();
		foreach ($props AS $k=>$v) {
			if (in_array($k, $this->assignable)) {
				$this->{$k} = $v;
			}
		}
	}

}