<?php namespace WeAreNotMachines\PDFMaker\Traits;

trait SlugifiableTrait {

	protected $slug;
	private static $contexts = [ "projects", "sections", "documents" ];

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug = $slug;
	}

	public function checkSlugAvailable($base, $context, $except=null) {
		$connection = \WeAreNotMachines\PDFMaker\Factories\MySQLConnection::getInstance();
		$context = strtolower($context);
		if (!in_array($context, self::$contexts)) {
			throw new \InvalidArgumentException("The context $context is not available");
		}

		if (empty($except)) {
			$stmt = $connection->prepare("SELECT id FROM $context WHERE slug=:slug");
		} else {
			$stmt = $connection->prepare("SELECT id FROM $context WHERE slug=:slug AND id != :id");
			$stmt->bindValue(":id", $except, \PDO::PARAM_INT);
		}

		$stmt->bindValue(":slug", (new \Slugifier)->slugify($base), \PDO::PARAM_STR);

		$stmt->execute();

		return count($stmt->fetchAll())==0;

	}

	public function slugify($text) {
		$this->setSlug((new \Slugifier)->slugify($text));
		return $this;
	}

	public function generateSlug($base, $context, $except=null) {

		if ($this->checkSlugAvailable($base, $context, $except)) {
			$this->slug = (new \Slugifier)->slugify($base);
			return $this;
		}

		$connection = \WeAreNotMachines\PDFMaker\Factories\MySQLConnection::getInstance();
		$context = strtolower($context);
		if (!in_array($context, self::$contexts)) {
			throw new \InvalidArgumentException("The context $context is not available");
		}
		$stmt = $connection->query("SELECT MAX(id)+1 AS top FROM $context");
		$res = $stmt->fetch(\PDO::FETCH_OBJ);

		$counter = $res ? $res->top : null;

		$this->setSlug((new \Slugifier)->slugify($base.$res->top));
		return $this;
	}

}