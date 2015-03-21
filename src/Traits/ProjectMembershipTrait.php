<?php namespace WeAreNotMachines\PDFMaker\Traits;

use WeAreNotMachines\PDFMaker\PDFProject;

trait ProjectMembershipTrait {

	protected $project;

	public function getProjectID() {
		return $this->project->getID();
	}

	public function getProject() {
		return $this->project;
	}

	public function setProject(PDFProject $project) {
		$this->project = $project;
	}

	public function getProjectSlug() {
		return $this->project->getSlug();
	}



}