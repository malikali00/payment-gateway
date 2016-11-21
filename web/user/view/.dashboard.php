<?php
use System\Config\DBConfig;
use Order\Model\OrderQueryStats;
use Merchant\Model\MerchantRow;
use User\Session\SessionManager;
use Order\Model\OrderRow;
/** @var $this \View\AbstractView*/
$SessionManager = new SessionManager();
$SessionUser = $SessionManager->getSessionUser();


$AppManager = new App

$this->getTheme()->printHTMLMenu('dashboard');
?>

<article class="themed">
    <!-- Bread Crumbs -->
    <aside class="bread-crumbs">
        <a href="/" class="nav_home"><?php echo $SessionUser->getFullName(); ?></a>
        <a href="/" class="nav_dashboard">Dashboard</a>
    </aside>

    <section class="content dashboard-section">


        <div class="stat-box-container">
            <a href="order?date_from=<?php echo $today; ?>&order=asc&order-by=id" class="stat-box stat-box-first">
                <div class="stat-large">$<?php echo number_format(@$stats['today'], 2); ?></div>
                <div>Today (<?php echo number_format(@$stats['today_count']); ?>)</div>
            </a>
            <a href="order?date_from=<?php echo $week_to_date; ?>&order=asc&order-by=id" class="stat-box stat-box-second">
                <div class="stat-large">$<?php echo number_format(@$stats['week_to_date'], 2); ?></div>
                <div>This Week (<?php echo number_format(@$stats['week_to_date_count']); ?>)</div>
            </a>
            <a href="order?date_from=<?php echo $month_to_date; ?>&order=asc&order-by=id" class="stat-box stat-box-third">
                <div class="stat-large">$<?php echo number_format(@$stats['month_to_date'], 2); ?></div>
                <div>This Month (<?php echo number_format(@$stats['month_to_date_count']); ?>)</div>
            </a>
            <a href="order?date_from=<?php echo $year_to_date; ?>&order=asc&order-by=id" class="stat-box stat-box-fourth">
                <div class="stat-large">$<?php echo number_format(@$stats['year_to_date'], 2); ?></div>
                <div>YTD (<?php echo number_format(@$stats['year_to_date_count']); ?>)</div>
            </a>
        </div>

        <div class="stat-box-container">

        </div>

        <?php if($this->hasMessage()) echo "<h5>", $this->getMessage(), "</h5>"; else echo "<h5></h5>"; ?>


        <form name="form-order-search" class="themed" style="text-align: center;">

            <fieldset style="display: inline-block; text-align: left;">
                <legend>Daily Totals</legend>
                <table class="table-stats themed small striped-rows">
                    <tr>
                        <th>Daily</th>
                        <th>Total</th>
                        <th>Settled</th>
                        <th>Void</th>
                        <th>Returned</th>
                        <?php if($SessionUser->hasAuthority('ROLE_ADMIN')) { ?>
                            <th>Conv. Fee</th>
                        <?php } ?>
                    </tr>
                    <?php
                    $odd = false;
                    foreach($DailyReportQuery as $Report) {
                        /** @var \Order\Model\OrderQueryStats $Report */
                        $report_url = $action_url . '&date_from=' . $Report->getStartDate() . '&date_to=' . $Report->getEndDate()
                        /** @var \Order\Model\OrderQueryStats $Stats */
                        ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td styl2e="max-width: 10em;"><a href="<?php echo $report_url; ?>&status="><?php echo strstr($Report->getGroupSpan(), ' 20', true); ?></a></td>
                            <td><a href="<?php echo $report_url; ?>&status="><?php echo number_format($Report->getTotal(),2), ' (', $Report->getTotalCount(), ')'; ?></a></td>
                            <td><a href="<?php echo $report_url; ?>&status=Settled"><?php echo number_format($Report->getSettledTotal(),2), ' (', $Report->getSettledCount(), ')'; ?></a></td>
                            <td><a href="<?php echo $report_url; ?>&status=Void"><?php echo number_format($Report->getVoidTotal(),2), ' (', $Report->getVoidCount(), ')'; ?></a></td>
                            <td><a href="<?php echo $report_url; ?>&status=Return"><?php echo number_format($Report->getReturnTotal(),2), ' (', $Report->getReturnCount(), ')'; ?></a></td>
                            <?php if($SessionUser->hasAuthority('ROLE_ADMIN')) { ?>
                                <td><?php echo number_format($Report->getConvenienceFeeTotal(),2), ' (', $Report->getConvenienceFeeCount(), ')'; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>

                </table>

            </fieldset>

            <fieldset style="display: inline-block; text-align: left;">
                <legend>Recent Transactions</legend>
                <table class="table-results themed small striped-rows">
                    <tr>
                        <th>Customer/ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th class="hide-on-layout-vertical">Mode</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    /** @var \Order\Model\OrderRow $Order */
                    $odd = false;

                    // Get Timezone diff
                    $offset = $SessionUser->getTimeZoneOffset('now');

                    foreach($RecentTransactionQuery as $Order) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td style="max-width: 5em;"><a href='order?uid=<?php echo $Order->getUID(); ?>'><?php echo $Order->getCardHolderFullName(), ($Order->getCustomerID() ? '/' . $Order->getCustomerID() : ''); ?></a></td>
                            <td style="max-width: 6em;"><?php echo date("M jS h:i A", strtotime($Order->getDate()) + $offset); ?></td>
                            <td><?php echo $Order->getAmount(); ?></td>
                            <td class="hide-on-layout-vertical"><?php echo ucfirst($Order->getEntryMode()); ?></td>
                            <td><?php echo $Order->getStatus(); ?></td>
                        </tr>
                    <?php } ?>

                </table>
            </fieldset>
        </form>
    </section>
</article>