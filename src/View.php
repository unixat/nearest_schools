<?php
// View class
// Manages all html outputs

namespace NearestSchools;

require_once __DIR__ . '/../vendor/autoload.php';

class View
{
	static public function form()
	{
		$pages = ['header','form','footer'];
		ob_start();
		foreach ($pages as $page) {
			require(__DIR__ . '/../views/' . $page . '.php');
		}
		ob_end_flush();
	}

	static public function formWithError()
	{
		if ('cli' == php_sapi_name()) {
			$pages = ['header','form','error','footer'];
			ob_start();
			foreach ($pages as $page) {
				require(__DIR__ . '/../views/' . $page . '.php');
			}
			ob_end_flush();
		}
		else {
			echo 'Something went wrong with your request';
		}
	}

	static public function results(array $school) 
	{
		include "views/results.php";
	}
}
