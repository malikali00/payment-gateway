<?php
namespace View\User;

use Config\DBConfig;
use User\UserRow;
use View\AbstractView;


class UserListView extends AbstractView {

	public function renderHTMLBody(Array $params) {
		$page = intval(@$params['page']) ?: 1;
		$limit = intval(@$params['limit']) ?: 50;
		if($limit > 250) $limit = 250;
		$offset = ($page-1) * $limit;

		$sqlParams = array();
		$sql = "SELECT * FROM USER ";

		if(isset($params['search'])) {
			$sql .= "\nWHERE username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ? OR uid = ?";
			$sqlParams = array($params['search'].'%', $params['search'].'%', $params['search'].'%', '%'.$params['search'].'%', $params['search']);
		}

		$sql .= "\nORDER BY ID DESC";
		$sql .= "\nLIMIT {$offset}, {$limit}";

		$DB = DBConfig::getInstance();
		$UserQuery = $DB->prepare($sql);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$UserQuery->setFetchMode(\PDO::FETCH_CLASS, 'User\UserRow');
		$UserQuery->execute($sqlParams);


		// Render Header
		$this->getTheme()->renderHTMLBodyHeader();

		// Render Page
		include ('.list.php');

		// Render footer
		$this->getTheme()->renderHTMLBodyFooter();
	}

	protected function processRequest(Array $post) {
		// Render on POST
		$this->renderHTML();
	}
}

