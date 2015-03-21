<?php namespace WeAreNotMachines\PDFMaker\Factories;
	
use \WeAreNotMachines\PDFMaker\PDFProject;
use \WeAreNotMachines\PDFMaker\PDFSection;
use \WeAreNotMachines\PDFMaker\PDFDocument;
use \WeAreNotMachines\PDFMaker\Factories\PDFProjectFactory;
use \PDO;
use \RuntimeException;

class PDFSectionFactory {

	private $db;

	public function __construct() {

		$this->db = MySQLConnection::getInstance();
	
	}

	public function save(PDFSection $section) {

		$section->touch();
		$section->generateSlug($section->getTitle(), "sections", ["id"=>$section->getID(), "project_id"=>$section->getProjectID()]);

		if (empty($section->getID())) {
			$stmt = $this->db->prepare("INSERT INTO sections SET project_id=:project_id, title=:title, slug=:slug, description=:description, created=:created, updated=:updated");
			$stmt->bindValue(":created", $section->getCreatedDateForSave(), PDO::PARAM_STR);
		} else {
			$stmt = $this->db->prepare("UPDATE sections SET project_id=:project_id, title=:title, slug=:slug, description=:description, updated=:updated WHERE id=:id");
			$stmt->bindValue(":id", $section->getID(), PDO::PARAM_INT);
		}
		$stmt->bindValue(":project_id", $section->getProjectID(), PDO::PARAM_INT);
		$stmt->bindValue(":title", $section->getTitle(), PDO::PARAM_STR);
		$stmt->bindValue(":slug", $section->getSlug(), PDO::PARAM_STR);
		$stmt->bindValue(":description", $section->getDescription(), PDO::PARAM_STR);
		$stmt->bindValue(":updated", $section->getUpdatedDateForSave(), PDO::PARAM_STR);

		$status = $stmt->execute();

		if (!$status) {
			$errorInfo = $stmt->errorInfo();
			throw new Exception("An error occurred whilst saving the section: ".$errorInfo[2]);
		}

		if (empty($section->getID())) {
			$section->setID($this->db->lastInsertID());
		}

		return $section;

	}

	public function load($sectionIdentifier, $projectIdentifier) {

		$stmt = $this->db->prepare("SELECT sections.* FROM sections JOIN projects ON projects.id=sections.project_id WHERE (sections.id=:id OR sections.slug=:slug) AND (projects.id=:projectID OR projects.slug=:projectSlug) LIMIT 1");
		$stmt->execute(array(":id"=>$sectionIdentifier, ":slug"=>$sectionIdentifier, ":projectID"=>$projectIdentifier, ":projectSlug"=>$projectIdentifier));

		$sectionData = $stmt->fetch(PDO::FETCH_OBJ);

		if (!$sectionData) {
			throw new RuntimeException("The section identified by $sectionIdentifier could not be found");
		}

		$section = (new PDFsection(["title"=>$sectionData->title, "id"=>$sectionData->id, "slug"=>$sectionData->slug, "description"=>$sectionData->description]))->setCreated($sectionData->created)->setUpdated($sectionData->updated);
		$section->setProject((new PDFProjectFactory)->load($sectionData->project_id));


		return $section;

	}

}