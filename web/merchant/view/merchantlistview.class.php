<?php
namespace Merchant\View;

use System\Config\DBConfig;
use Merchant\Model\MerchantQueryStats;
use Merchant\Model\MerchantRow;
use User\Session\SessionManager;
use View\AbstractListView;


class MerchantListView extends AbstractListView {


	/**
	 * @param array $params
	 */
	public function renderHTMLBody(Array $params) {

		// Set up page parameters
		$this->setPageParameters(@$params['page'] ?: 1, @$params['limit'] ?: 10);

		$sqlParams = array();
		$whereSQL = MerchantRow::SQL_WHERE;
		$statsMessage = '';

		// Set up WHERE conditions
		if(!empty($params['search'])) {
			$whereSQL .= "\nAND
			(
				m.id = :exact
				OR m.uid = :exact

				OR m.name LIKE :startswith
				OR m.short_name LIKE :startswith

				OR m.main_email_id LIKE :has
			)
			";
			$sqlParams = array(
				'exact' => $params['search'],
				'startswith' => $params['search'].'%',
				'has' => '%'.$params['search'].'%',
			);
		}

		if(!empty($params['user_id'])) {
			$whereSQL .= "\nAND EXISTS (
				SELECT * FROM user_merchants um WHERE um.id_user = :user_id AND um.id_merchant = m.id
			)";
			$sqlParams[':user_id'] = $params['user_id'];
		}


		// Handle authority
		$SessionManager = new SessionManager();
		$SessionUser = $SessionManager->getSessionUser();
		if(!$SessionUser->hasAuthority('ROLE_ADMIN', 'ROLE_SUB_ADMIN')) {
			$whereSQL .= "\nAND 0\n";
		}

		// Query Statistics
		$DB = DBConfig::getInstance();
		$countSQL = MerchantQueryStats::SQL_SELECT . $whereSQL;
		$StatsQuery = $DB->prepare($countSQL);
		$StatsQuery->execute($sqlParams);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$StatsQuery->setFetchMode(\PDO::FETCH_CLASS, MerchantQueryStats::_CLASS);
		/** @var MerchantQueryStats $Stats */
		$Stats = $StatsQuery->fetch();
		$this->setRowCount($Stats->getCount());


		// Calculate GROUP BY
		$groupSQL = MerchantRow::SQL_GROUP_BY;

		// Calculate ORDER BY
		$orderSQL = MerchantRow::SQL_ORDER_BY;
		if(!empty($params[self::FIELD_ORDER_BY])) {
			$sortOrder = strcasecmp($params[self::FIELD_ORDER], 'DESC') === 0 ? 'DESC' : 'ASC';
			$sortField = $params[self::FIELD_ORDER_BY];
			if(!in_array($sortField, MerchantRow::$SORT_FIELDS))
				throw new \InvalidArgumentException("Invalid order-by field");
			$orderSQL = "\nORDER BY {$sortField} {$sortOrder}";
			$statsMessage .= "\nsorted by field '{$sortField}' in " . strtolower($sortOrder) . "ending order";
		}

		// Calculate LIMIT
		$limitSQL = "\nLIMIT " . $this->getOffset() . ', ' . $this->getLimit();

		// Query Rows
		$mainSQL = MerchantRow::SQL_SELECT . $whereSQL . $groupSQL . $orderSQL . $limitSQL;
		$ListQuery = $DB->prepare($mainSQL);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$ListQuery->setFetchMode(\PDO::FETCH_CLASS, MerchantRow::_CLASS);
		$time = -microtime(true);
		$ListQuery->execute($sqlParams);
		$time += microtime(true);
		$this->setListQuery($ListQuery);

		$statsMessage = $this->getRowCount() . " merchants found in " . sprintf('%0.2f', $time) . ' seconds <br/>' . $statsMessage;
//		$this->setMessage($statsMessage);

		// Render Page
		include ('.list.php');
	}


	public function processFormRequest(Array $post) {
		try {
			$this->setSessionMessage("Unhandled Form Post");
			header("Location: home.php");

		} catch (\Exception $ex) {
			$this->setSessionMessage($ex->getMessage());
			header("Location: login.php");
		}
	}
}

