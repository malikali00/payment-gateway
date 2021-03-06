<?php
namespace App\Chart;
use System\Config\DBConfig;
use User\Model\UserRow;

/**
 * Created by PhpStorm.
 * User: ari
 * Date: 11/14/2016
 * Time: 4:11 PM
 */
class YearlyChart extends AbstractTotalsApp {
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

        $amount = number_format($stats['yearly'], 2);
        $count = number_format($stats['yearly_count']);

        $appClassName = 'app-chart-yearly';
        echo <<<HTML
        <div class="app-chart {$appClassName}">
            <div class="app-section-top">
                <div class="app-section-text-large" style="text-align: center;">
                    <a href="order?date_from={$stats['time_yearly']}" class="app-chart-count {$appClassName}-count">
                        Yearly ({$count})
                    </a>
                </div>
                <hr />
            </div>
            <a href="order?date_from={$stats['time_yearly']}" class="app-chart-amount {$appClassName}-amount">
                \${$amount}
            </a>
            <div class="app-button app-button-config app-button-top-right">
                <ul class="app-menu">
                    <li><div class='app-button app-button-top'></div><a href="#" onclick="appChartAction('move-top', '{$appClassName}');">Move to top</a></li>
                    <li><div class='app-button app-button-up'></div><a href="#" onclick="appChartAction('move-up', '{$appClassName}');">Move up</a></li>
                    <li><div class='app-button app-button-down'></div><a href="#" onclick="appChartAction('move-down', '{$appClassName}');">Move down</a></li>
                    <li><div class='app-button app-button-bottom'></div><a href="#" onclick="appChartAction('move-bottom', '{$appClassName}');">Move to bottom</a></li>
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appChartAction('config', '{$appClassName}');">Configure...</a></li>
                    <li><div class='app-button app-button-remove'></div><a href="#" onclick="appChartAction('remove', '{$appClassName}');">Remove</a></li>
                </ul>
            </div>
        </div>
HTML;
    }

    public function fetchStats() {
        $offset = 0;
        $yearly  = date('Y-m-d', time() - 24*60*60*365 + $offset);

        $SQL = <<<SQL
SELECT
	SUM(amount - total_returned_amount) as yearly,
	COUNT(*) as yearly_count
 FROM order_item oi

WHERE 
    date>='{$yearly}'
    AND status in ('Settled', 'Authorized')
SQL;

        $SessionUser = $this->getSessionUser();
        if(!$SessionUser->hasAuthority('ROLE_ADMIN'))
            $SQL .= "\nAND oi.merchant_id = (SELECT um.id_merchant FROM user_merchants um WHERE um.id_user = " . $SessionUser->getID() . " AND um.id_merchant = oi.merchant_id)";

        $duration = -microtime(true);
        $DB = DBConfig::getInstance();
        $stmt = $DB->prepare($SQL);
        $stmt->execute();
        $stats = $stmt->fetch();
        $duration += microtime(true);
        $stats['duration'] = $duration;
        $stats['time_yearly'] = $yearly;

        return $stats;
    }


}

