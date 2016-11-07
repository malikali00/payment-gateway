<?php
namespace Order\View;

use System\Config\DBConfig;
use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use Order\Model\OrderQueryStats;
use User\Session\SessionManager;
use View\AbstractListView;


class OrderListView extends AbstractListView {

//Need to be able to pull information by batch, day, card #, amount, MID, TID ect.
// TODO batch id

	public function renderHTML($params=null) {
		if(in_array(strtolower(@$params['action']), array('export', 'export-stats', 'export-data'))) {
			$this->renderHTMLBody($params);
			return;
		}
		parent::renderHTML($params);
	}

	/**
	 * @param array $params
     */
	public function renderHTMLBody(Array $params) {
        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

		// Set up page parameters
		$this->setPageParameters(@$params['page'] ?: 1, @$params['limit'] ?: 10);

		$sqlParams = array();
		$whereSQL = "WHERE 1";
		$statsMessage = '';

		$action = strtolower(@$params['action'] ?: 'view');
		$export_filename = $action . '.csv';

		// Set up WHERE conditions
		if(!empty($params['search'])) {
			$whereSQL .= "\nAND
			(
				oi.id = :exact
				OR oi.uid = :exact

				OR oi.amount = :exact
				OR oi.invoice_number = :exact
				OR oi.customer_id = :exact
				OR oi.username = :exact

                OR SUBSTRING(oi.card_number, -4) = :exact

				OR oi.customer_first_name LIKE :startswith
				OR oi.customer_last_name LIKE :startswith

				OR m.uid = :exact
			)
			";
			$sqlParams = array(
				'exact' => $params['search'],
				'startswith' => $params['search'].'%',
				'endswith' => '%'.$params['search'],
			);
		}

        // Get Timezone diff
        $offset = $SessionUser->getTimeZoneOffset('now');
        $offset = 0;

        // Set up Date conditions
		if(!empty($params['date_from'])) {
			$whereSQL .= "\nAND oi.date >= :from";
			$sqlParams['from'] = date("Y-m-d G:00:00", strtotime($params['date_from']) + $offset);
			$statsMessage .= " from " . date("M jS Y G:00", strtotime($params['date_from']) + $offset);
			$export_filename = date("Y-m-d", strtotime($params['date_from']) + $offset) . $export_filename;
		}
		if(!empty($params['date_to'])) {
			$whereSQL .= "\nAND oi.date <= :to";
			$sqlParams['to'] = date("Y-m-d G:00:00", strtotime($params['date_to']) + $offset);
			$statsMessage .= " to " . date("M jS Y G:00", strtotime($params['date_to']) + $offset);
			$export_filename = date("Y-m-d", strtotime($params['date_to']) + $offset) . $export_filename;
		}


		// Handle authority
		if(!$SessionUser->hasAuthority('ROLE_ADMIN')) {
			$list = $SessionUser->getMerchantList() ?: array(0);
			$whereSQL .= "\nAND oi.merchant_id IN (" . implode(', ', $list) . ")\n";

            if(!$SessionUser->hasAuthority('ROLE_RUN_REPORTS', 'ROLE_SUB_ADMIN')) {
				$this->setMessage(
					"<span class='error'>Authorization required to run reports: ROLE_RUN_REPORTS</span>"
				);
				$whereSQL .= "\nAND 0=1";
			}
		}

        // Limit to merchant
        if(!empty($params['merchant_id'])) {
            $Merchant = MerchantRow::fetchByID($params['merchant_id']);
            $whereSQL .= "\nAND oi.merchant_id = :merchant_id";
            $sqlParams['merchant_id'] = $Merchant->getID();
			$export_filename = $Merchant->getShortName() . $export_filename;
//            $statsMessage .= " by merchant '" . $Merchant->getShortName() . "' ";
        }

        // Limit to status
        if(!empty($params['status'])) {
            $whereSQL .= "\nAND oi.status = :status";
            $sqlParams['status'] = $params['status'];
            $statsMessage .= " by status '" . $params['status'] . "' ";
        }

		// Query Statistics
		$DB = DBConfig::getInstance();


		$statsSQL = OrderQueryStats::SQL_SELECT . $whereSQL;
		$StatsQuery = $DB->prepare($statsSQL);
		$StatsQuery->execute($sqlParams);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$StatsQuery->setFetchMode(\PDO::FETCH_CLASS, OrderQueryStats::_CLASS);
		/** @var OrderQueryStats $Stats */
		$Stats = $StatsQuery->fetch();
		$this->setRowCount($Stats->getCount());

		// TODO decide date range

		$groupByStatsSQL = OrderQueryStats::SQL_GROUP_BY;
		$limitStatsSQL = "\nLIMIT 5";
		if(in_array(strtolower(@$params['action']),
			array('export', 'export-stats', 'export-data')))
			$limitStatsSQL = '';
		switch(@$params['stats_group']) {
			default:
			case 'Day': $groupByStatsSQL = "\n\tGROUP BY DATE_FORMAT(oi.date, '%Y%m%d')"; break;
			case 'Week': $groupByStatsSQL = "\n\tGROUP BY DATE_FORMAT(oi.date, '%Y%u')"; break;
			case 'Month': $groupByStatsSQL = "\n\tGROUP BY DATE_FORMAT(oi.date, '%Y%m')"; break;
			case 'Year': $groupByStatsSQL = "\n\tGROUP BY DATE_FORMAT(oi.date, '%Y')"; break;
		}

		$statsSQL = OrderQueryStats::SQL_SELECT . $whereSQL
			. $groupByStatsSQL
			. OrderQueryStats::SQL_ORDER_BY
			. $limitStatsSQL;
		$ReportQuery = $DB->prepare($statsSQL);
		$ReportQuery->execute($sqlParams);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$ReportQuery->setFetchMode(\PDO::FETCH_CLASS, OrderQueryStats::_CLASS);


		// Calculate GROUP BY
		$groupSQL = OrderRow::SQL_GROUP_BY;

		// Calculate ORDER BY
		$orderSQL = OrderRow::SQL_ORDER_BY;
		if(!empty($params[self::FIELD_ORDER_BY])) {
			$sortOrder = strcasecmp($params[self::FIELD_ORDER], 'DESC') === 0 ? 'DESC' : 'ASC';
			$sortField = $params[self::FIELD_ORDER_BY];
			if(substr($sortField, 0, 3) !== 'oi.')
				$sortField = 'oi.' . $sortField;
			if(!in_array($sortField, OrderRow::$SORT_FIELDS))
				throw new \InvalidArgumentException("Invalid order-by field");
			$orderSQL = "\nORDER BY {$sortField} {$sortOrder}";
			$statsMessage .= "\nsorted by field '{$sortField}' in " . strtolower($sortOrder) . "ending order";
		}

		// Calculate LIMIT
		$limitSQL = "\nLIMIT " . $this->getOffset() . ', ' . $this->getLimit();
		if(in_array(strtolower(@$params['action']),
			array('export', 'export-stats', 'export-data')))
			$limitSQL = '';

		// Query Rows
		$mainSQL = OrderRow::SQL_SELECT . $whereSQL . $groupSQL . $orderSQL . $limitSQL;
		$Query = $DB->prepare($mainSQL);
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		$Query->setFetchMode(\PDO::FETCH_CLASS, OrderRow::_CLASS);
		$time = -microtime(true);
		$Query->execute($sqlParams);
		$time += microtime(true);


		$statsMessage = $this->getRowCount() . " orders found in " . sprintf('%0.2f', $time) . ' seconds ' . $statsMessage;
        $statsMessage .= " (GMT " . $offset/(60*60) . ")";

		if(!$this->getMessage())
			$this->setMessage($statsMessage);

		if(in_array(strtolower(@$params['action']),
			array('export', 'export-stats', 'export-data'))) {
			// Render Page
			include ('.export.csv.php');

		} else {
			// Render Header
			$this->getTheme()->renderHTMLBodyHeader();
			// Render Page
			include ('.list.php');
			// Render footer
			$this->getTheme()->renderHTMLBodyFooter();
		}

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

	protected function renderHTMLHeadScripts() {
		echo <<<HEAD
        <script src="order/view/assets/order.js"></script>
HEAD;
		parent::renderHTMLHeadScripts();
	}
}

