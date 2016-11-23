<?php
namespace Integration\View;

use System\Config\DBConfig;
use Integration\Model\IntegrationRow;
use User\Session\SessionManager;
use View\AbstractListView;


class IntegrationListView extends AbstractListView {


	/**
	 * @param array $params
	 */
	public function renderHTMLBody(Array $params) {

		$sqlParams = array();
		$whereSQL = "WHERE 1";
		$statsMessage = '';

		// Handle authority
		$SessionManager = new SessionManager();
		$SessionUser = $SessionManager->getSessionUser();
		if(!$SessionUser->hasAuthority('ROLE_ADMIN')) {
			$whereSQL .= "\nAND 0\n";
		}


		// Calculate GROUP BY
		$groupSQL = IntegrationRow::SQL_GROUP_BY;

		// Calculate ORDER BY
		$orderSQL = IntegrationRow::SQL_ORDER_BY;
		if(!empty($params[self::FIELD_ORDER_BY])) {
			$sortOrder = strcasecmp($params[self::FIELD_ORDER], 'DESC') === 0 ? 'DESC' : 'ASC';
			$sortField = $params[self::FIELD_ORDER_BY];
			if(!in_array($sortField, IntegrationRow::$SORT_FIELDS))
				throw new \InvalidArgumentException("Invalid order-by field");
			$orderSQL = "\nORDER BY {$sortField} {$sortOrder}";
			$statsMessage .= "\nsorted by field '{$sortField}' in " . strtolower($sortOrder) . "ending order";
		}

		// Calculate LIMIT
		$limitSQL = "\nLIMIT " . $this->getOffset() . ', ' . $this->getLimit();

		// Query Rows
		$mainSQL = IntegrationRow::SQL_SELECT . $whereSQL . $groupSQL . $orderSQL . $limitSQL;
		$DB = DBConfig::getInstance();
		$ListQuery = $DB->prepare($mainSQL);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$ListQuery->setFetchMode(\PDO::FETCH_CLASS, IntegrationRow::_CLASS);
		$time = -microtime(true);
		$ListQuery->execute($sqlParams);
		$time += microtime(true);
		$this->setListQuery($ListQuery);
		$this->setRowCount($ListQuery->rowCount());

		$statsMessage = $this->getRowCount() . " integrations found in " . sprintf('%0.2f', $time) . ' seconds <br/>' . $statsMessage;
		$this->setMessage($statsMessage);

		// Render Page
		include ('.list.php');
	}


	public function processFormRequest(Array $post) {
		try {
			$this->setSessionMessage("Unhandled Form Post");
			header("Location: /");

		} catch (\Exception $ex) {
			$this->setSessionMessage($ex->getMessage());
			header("Location: login.php");
		}
	}
}

