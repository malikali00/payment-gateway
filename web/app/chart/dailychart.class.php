<?php
namespace App\Chart;
use App\AbstractApp;
use System\Config\DBConfig;
use User\Model\UserRow;

/**
 * Created by PhpStorm.
 * User: ari
 * Date: 11/14/2016
 * Time: 4:11 PM
 */
class DailyChart extends AbstractTotalsApp {
    const SESSION_KEY = __FILE__;

    const TIMEOUT = 60;

    private $config;

    public function __construct(UserRow $SessionUser, $config) {
        parent::__construct($SessionUser);
        $this->config = $config;
    }

    /**
     * Generate a string representing the user configuration for this app
     * @return mixed
     */
    protected function getConfig() {
        return $this->config;
    }

    /**
     * Print an HTML representation of this app
     * @param array $params
     * @return mixed
     */
    function renderAppHTML(Array $params = array()) {
        $stats = $this->getStats();

        $amount = number_format($stats['today'], 2);
        $count = number_format($stats['today_count']);

        $appClassName = 'app-chart-today';
        echo <<<HTML
        <div class="app-chart {$appClassName}">
            <a href="order?date_from={$stats['time_today']}" class="app-chart-amount {$appClassName}-amount">
                \${$amount}
            </a>
            <a href="order?date_from={$stats['time_today']}" class="app-chart-count {$appClassName}-count">
                Today ({$count})
            </a>
            <div class="app-button-config">
                <ul>
                    <li><a href="#" onclick="appAction('move-up', '{$appClassName}');">Move up</a></li>
                    <li><a href="#" onclick="appAction('move-down', '{$appClassName}');">Move down</a></li>
                    <li><a href="#" onclick="appAction('move-top', '{$appClassName}');">Move to top</a></li>
                    <li><a href="#" onclick="appAction('move-bottom', '{$appClassName}');">Move to bottom</a></li>
                    <li><a href="#" onclick="appAction('config', '{$appClassName}');">Configure...</a></li>
                    <li><a href="#" onclick="appAction('remove', '{$appClassName}');">Remove</a></li>
                </ul>
            </div>
        </div>
HTML;

    }

    public function fetchStats() {
        $offset = 0;
        $today = date('Y-m-d', time() + $offset);

        $SQL = <<<SQL
SELECT
	SUM(amount) as today,
	COUNT(*) as today_count
 FROM order_item oi

WHERE
    date>='{$today}'
    AND status in ('Settled', 'Authorized')
SQL;

        $SessionUser = $this->getSessionUser();
        $ids = $SessionUser->getMerchantList() ?: array(-1);
        $SQL .= "\nAND oi.merchant_id IN (" . implode(', ', $ids) . ")";
//            $SQL .= "\nAND oi.merchant_id = (SELECT um.id_merchant FROM user_merchants um WHERE um.id_user = " . intval($userID) . " AND um.id_merchant = oi.merchant_id)";

        $duration = -microtime(true);
        $DB = DBConfig::getInstance();
        $stmt = $DB->prepare($SQL);
        $stmt->execute();
        $stats = $stmt->fetch();
        $duration += microtime(true);
        $stats['duration'] = $duration;
        $stats['time_today'] = $today;

        return $stats;
    }

}

