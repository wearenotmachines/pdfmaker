<?php namespace WeAreNotMachines\PDFMaker\Factories;
	
use \WeAreNotMachines\PDFMaker\PDFProject;
use \WeAreNotMachines\PDFMaker\PDFSection;
use \WeAreNotMachines\PDFMaker\PDFDocument;
use \PDO;
use \RuntimeException;

class PDFProjectFactory {

	private $db;

	public function __construct() {

		$this->db = MySQLConnection::getInstance();
	
	}

	public function save(PDFProject $project) {

		$project->touch();
		$project->generateSlug($project->getTitle(), "projects", $project->getID());

		if (empty($project->getID())) {
			$stmt = $this->db->prepare("INSERT INTO projects SET title=:title, slug=:slug, description=:description, created=:created, updated=:updated");
			$stmt->bindValue(":created", $project->getCreatedDateForSave(), PDO::PARAM_STR);
		} else {
			$stmt = $this->db->prepare("UPDATE projects SET title=:title, slug=:slug, description=:description, updated=:updated WHERE id=:id");
			$stmt->bindValue(":id", $project->getID(), PDO::PARAM_INT);
		}

		$stmt->bindValue(":title", $project->getTitle(), PDO::PARAM_STR);
		$stmt->bindValue(":slug", $project->getSlug(), PDO::PARAM_STR);
		$stmt->bindValue(":description", $project->getDescription(), PDO::PARAM_STR);
		$stmt->bindValue(":updated", $project->getUpdatedDateForSave(), PDO::PARAM_STR);

		$status = $stmt->execute();

		if (!$status) {
			$errorInfo = $stmt->errorInfo();
			throw new Exception("An error occurred whilst saving the project: ".$errorInfo[2]);
		}

		if (empty($project->getID())) {
			$project->setID($this->db->lastInsertID());
		}

		return $project;

	}

	public function load($projectIdentifier) {

		$stmt = $this->db->prepare("SELECT * FROM projects WHERE id=:id OR slug=:slug LIMIT 1");
		$stmt->execute(array(":id"=>$projectIdentifier, ":slug"=>$projectIdentifier));

		$projectData = $stmt->fetch(PDO::FETCH_OBJ);

		if (!$projectData) {
			throw new RuntimeException("The project identified by $projectIdentifier could not be found");
		}

		$project = (new PDFProject(["title"=>$projectData->title, "id"=>$projectData->id, "slug"=>$projectData->slug, "description"=>$projectData->description]))->setCreated($projectData->created)->setUpdated($projectData->updated);

		return $project;

	}

}