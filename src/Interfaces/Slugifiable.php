<?php namespace WeAreNotMachines\PDFMaker\Interfaces;

interface Slugifiable {

	public function setSlug($slug);
	public function getSlug();
	public function checkSlugAvailable($slug, $context, $except=null);

}