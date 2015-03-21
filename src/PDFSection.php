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

use \ArrayAccess;


class PDFSection extends IdentifiableEntity implements ProjectMember, Entitlable, Describable, Timestampable, Slugifiable, ArrayAccess {

	use ProjectMembershipTrait;
	use EntitlableTrait;
	use DescribableTrait;
	use TimestampableTrait;
	use SlugifiableTrait;

	protected $documents;
	protected $assignable = [ "id", "title", "slug", "description" ];

	public function __construct($props=array()) {
		$this->touch();
		foreach ($props AS $k=>$v) {
			if (in_array($k, $this->assignable)) {
				$this->{$k} = $v;
			}
		}
	}

	public function addDocument(PDFDocument $document, $atPosition=null) {
		if (empty($atPosition)) {
			$this->documents[] = $document;
		} else {
			array_splice($this->documents, $atPosition-1, 0, $document);
		}
		$this->reorderDocuments();
		return $this;
	}

	public function getDocuments() {
		return $this->documents;
	}

	public function offsetGet($offset) {
		return $this->documents[$offset];
	}

	public function offsetExists($offset) {
		return !empty($this->documents[$offset]);
	}

	public function offsetSet($offset, $item) {
		$this->addDocument($item, $offset);
	}

	public function offsetUnset($offset) {
		unset($this->documents[$offset]);
		$this->reorderDocuments();
	}

	public function reorderDocuments() {
		$counter = 1;
		foreach ($this->documents AS $document) {
			$document->setDisplayOrder($counter);
			++$counter;
		}
	}

	public function checkSlugAvailable($base, $context, $except=null) {
		$connection = \WeAreNotMachines\PDFMaker\Factories\MySQLConnection::getInstance();
		$context = strtolower($context);
		if (!in_array($context, self::$contexts)) {
			throw new \InvalidArgumentException("The context $context is not available");
		}

		if (empty($except['id'])) {
			$stmt = $connection->prepare("SELECT id FROM $context WHERE slug=:slug AND project_id=:project_id");
		} else {
			$stmt = $connection->prepare("SELECT id FROM $context WHERE slug=:slug AND id != :id AND project_id=:project_id");
			$stmt->bindValue(":id", $except['id'], \PDO::PARAM_INT);
		}
		$stmt->bindValue(":slug", (new \Slugifier)->slugify($base), \PDO::PARAM_STR);
		$stmt->bindValue(":project_id", $except['project_id'], \PDO::PARAM_INT);

		$stmt->execute();

		return count($stmt->fetchAll())==0;

	}

}