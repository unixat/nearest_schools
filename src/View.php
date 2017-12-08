<?php
// View class
// Manages all html outputs

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
		$pages = ['header','form','error','footer'];
		ob_start();
		foreach ($pages as $page) {
			require(__DIR__ . '/../views/' . $page . '.php');
		}
		ob_end_flush();
	}
}
