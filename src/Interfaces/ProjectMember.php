<?php namespace WeAreNotMachines\PDFMaker\Interfaces;

use WeAreNotMachines\PDFMaker\PDFProject;

interface ProjectMember {

	public function getProject();
	public function getProjectID();
	public function setProject(PDFProject $project);

}