<?php namespace WeAreNotMachines\PDFMaker\Interfaces;

interface Timestampable {

	public function touch();
	public function getCreated();
	public function getCreatedDate($format="d/m/Y g:ia");
	public function getUpdated();
	public function getUpdatedDate($format="d/m/Y g:ia");
	public function getCreatedDateForSave();
	public function getUpdatedDateForSave();

}