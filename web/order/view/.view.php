<?php
use \Merchant\Model\MerchantRow;
/** @var \Order\View\OrderView $this*/
// Render Header
$this->getTheme()->renderHTMLBodyHeader();

$Order = $this->getOrder();
$Transaction = $Order->fetchAuthorizedTransaction();
$Merchant = MerchantRow::fetchByID($Order->getMerchantID());
$odd = true;
$action_url = 'order/receipt.php?uid=' . $Order->getUID() . '&action=';
$action_url_pdf = 'order/pdf.php?uid=' . $Order->getUID();
$SessionManager = new \User\Session\SessionManager();
$SessionUser = $SessionManager->getSessionUser();

// Get Timezone diff
$offset = $SessionUser->getTimeZoneOffset('now');



?>

<!-- Page Navigation -->
<nav class="page-menu hide-on-print">
    <a href="/" class="button">Dashboard <div class="submenu-icon submenu-icon-dashboard"></div></a>
    <?php if($SessionUser->hasAuthority('ROLE_POST_CHARGE', 'ROLE_ADMIN')) { ?>
        <a href="transaction/charge.php?" class="button">Charge  <div class="submenu-icon submenu-icon-charge"></div></a>
    <?php } ?>
    <?php if($SessionUser->hasAuthority('ROLE_ADMIN')) { ?>
        <a href="order?" class="button">Transactions <div class="submenu-icon submenu-icon-list"></div></a>
    <?php } ?>
    <a href="<?php echo $action_url; ?>receipt" class="button current">Receipt <div class="submenu-icon submenu-icon-receipt"></div></a>
    <a href="javascript:window.print();" class="button">Print <div class="submenu-icon submenu-icon-print"></div></a>
    <a href="<?php echo $action_url_pdf; ?>" class="button">Download <div class="submenu-icon submenu-icon-download"></div></a>
<!--    <a href="--><?php //echo $action_url; ?><!--email" class="button">Email <div class="submenu-icon submenu-icon-email"></div></a>-->
<!--    <a href="--><?php //echo $action_url; ?><!--bookmark" class="button">Bookmark URL <div class="submenu-icon submenu-icon-bookmark"></div></a>-->
</nav>

<article class="themed">

    <section class="content">
        <!-- Bread Crumbs -->
        <aside class="bread-crumbs hide-on-print">
            <?php if($SessionUser->hasAuthority('ROLE_ADMIN')) { ?>
                <a href="order" class="nav_order">Transactions</a>
            <?php } ?>
            <a href="<?php echo $action_url; ?>view" class="nav_transaction_view">#<?php echo $Order->getUID(); ?></a>
        </aside>
        <?php if($this->hasMessage()) echo "<h5>", $this->getMessage(), "</h5>"; ?>

        <form name="form-order-view" id="form-order-view" class="themed" method="POST">
            <fieldset style="display: inline-block;">
                <legend><?php echo $Merchant->getShortName(); ?></legend>
                <table class="table-transaction-info themed striped-rows">
                    <tbody>
                    <?php $odd = true; ?>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name">Location</td>
                        <td class="value"><?php echo $Merchant->getCity(), ',', $Merchant->getState(), ' ', $Merchant->getZipCode() ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name">Phone</td>
                        <td class="value"><?php echo $Merchant->getTelephone(); ?></td>
                    </tr>

                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name">Customer</td>
                        <td class="value"><?php echo $Order->getCustomerFullName() ?></td>
                    </tr>

                    <?php if($Order->getCustomerID()) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Customer</td>
                            <td class="value"><?php echo $Order->getCustomerID() ?: 'N/A' ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($Order->getPayeeEmail()) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Email</td>
                            <td class="value"><a href="mailto:<?php echo $Order->getPayeeEmail() ?>"><?php echo $Order->getPayeeEmail() ?></a></td>
                        </tr>
                    <?php }  ?>

                    </tbody>
                </table>
            </fieldset>

            <fieldset style="display: inline-block;">
                <legend>Receipt</legend>
                <table class="table-transaction-info themed striped-rows">
                    <tbody>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Date</td>
                            <td class="value"><?php echo date("F jS Y", strtotime($Order->getDate()) + $offset); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Time</td>
                            <td class="value"><?php echo date("g:i:s A", strtotime($Order->getDate()) + $offset); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Time Zone</td>
                            <td class="value"><?php echo str_replace('_', '', $SessionUser->getTimeZone()); ?></td>
                        </tr>
                        <?php if($Order->getInvoiceNumber()) { ?>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Invoice</td>
                                <td class="value"><?php echo $Order->getInvoiceNumber() ?: 'N/A'; ?></td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </fieldset>

            <?php if ($Order->getCardNumber()) { ?>

                <fieldset>
                    <legend>Card Holder: <?php echo $Order->getCardHolderFullName(); ?></legend>
                    <table class="table-transaction-info themed cell-borders" style="width: 100%">
                        <tbody>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <?php if($Order->getUsername()) { ?>
                                <th>User ID</th>
                                <?php }  ?>
                                <th>Credit Card</th>
                                <th>Card Type</th>
                                <th>Status</th>
                                <th>Code</th>
                                <th>Order ID</th>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <?php if($Order->getUsername()) { ?>
                                    <td class="value"><?php echo $Order->getUsername(); ?></td>
                                <?php }  ?>
                                <td class="value"><?php echo $Order->getCardNumber(); ?></td>
                                <td class="value"><?php echo $Order->getCardType(); ?></td>
                                <td class="value"><?php echo $Order->getStatus(); ?></td>
                                <td class="value"><?php echo $Transaction->getTransactionID(); ?></td>
                                <td class="value"><?php echo $Order->getID(); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

            <?php } else  { ?>

                <fieldset style="display: inline-block;">
                    <legend>e-Check Information</legend>
                    <table class="table-transaction-info themed striped-rows">
                        <tbody>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Name on Account</td>
                            <td class="value"><?php echo $Order->getCheckAccountName(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Check Account Number</td>
                            <td class="value"><?php echo $Order->getCheckAccountNumber() ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Check Routing Number</td>
                            <td class="value"><?php echo $Order->getCheckRoutingNumber(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Check Account Type</td>
                            <td class="value"><?php echo $Order->getCheckAccountType(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Check Type</td>
                            <td class="value"><?php echo $Order->getCheckType(); ?></td>
                        </tr>
                        <?php if($Order->getCheckNumber()) { ?>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Check Number</td>
                                <td class="value"><?php echo $Order->getCheckNumber(); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </fieldset>

            <?php } ?>



            <fieldset style="display: inline-block; float: right;">
                <legend>Totals</legend>
                <table class="table-transaction-info-totals themed striped-rows">
                    <tbody>
                    <?php if ($Order->getConvenienceFee()) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Subtotal</td>
                            <td class="value">$<?php echo $Order->getAmount(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Fee</td>
                            <td class="value">$<?php echo $Order->getConvenienceFee(); ?></td>
                        </tr>
                    <?php } ?>

                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name">Total</td>
                        <td class="value">$<?php echo number_format($Order->getAmount()+$Order->getConvenienceFee(), 2); ?></td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>


            <fieldset class="show-on-print" style="clear: both;">
                <br/>
                <br/>
                <br/>
                <hr style="height: 2px;">
                Customer Signature
            </fieldset>

            <fieldset style="display: inline-block;" class="hide-on-print">
                <legend>Transaction History</legend>
                <table class="table-results themed small">
                    <tr>
                        <th>ID</th>
                        <th class="hide-on-layout-vertical">TID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Fee</th>
                        <th>Action</th>
                        <th>Perform</th>
                    </tr>
                    <?php
                    /** @var \Transaction\Model\TransactionRow $Transaction */
                    $DB = \Config\DBConfig::getInstance();
                    $TransactionQuery = $DB->prepare(\Transaction\Model\TransactionRow::SQL_SELECT . "WHERE t.order_item_id = ? LIMIT 100");
                    /** @noinspection PhpMethodParametersCountMismatchInspection */
                    $TransactionQuery->setFetchMode(\PDO::FETCH_CLASS, \Transaction\Model\TransactionRow::_CLASS);
                    $TransactionQuery->execute(array($this->getOrder()->getID()));
                    $odd = false;
                    foreach($TransactionQuery as $Transaction) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td><a href='transaction?id=<?php echo $Transaction->getID(); ?>#form-order-view'><?php echo $Transaction->getID(); ?></a></td>
                            <td class="hide-on-layout-vertical"><?php echo $Transaction->getTransactionID(); ?></td>
                            <td><?php echo date("M j g:i A", strtotime($Transaction->getTransactionDate()) + $offset); ?></td>
                            <td>$<?php echo $Transaction->getAmount(); ?></td>
                            <td>$<?php echo $Transaction->getServiceFee(); ?></td>
                            <td>
                                <a href="integration/request?type=transaction&type_id=<?php echo $Transaction->getID(); ?>">
                                    <?php echo $Transaction->getAction(); ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                    switch($Transaction->getAction()) {
                                        case 'Authorized':
                                            if($Order->getStatus() === 'Authorized') {
                                                $disabled = $SessionUser->hasAuthority('ROLE_VOID_CHARGE', 'ROLE_ADMIN') ? '' : " disabled='disabled'";
                                                echo "<input name='action' type='submit' value='Void'{$disabled}/>";
                                            }
                                            break;

                                        case 'Settled':
                                            if($Order->getStatus() === 'Settled') {
                                                $disabled = $SessionUser->hasAuthority('ROLE_RETURN_CHARGE', 'ROLE_ADMIN') ? '' : " disabled='disabled'";
                                                echo "<input name='action' type='submit' value='Return'{$disabled}/>";
                                            }
                                            break;
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </fieldset>


        </form>
    </section>
</article>

<?php
// Render Footer
$this->getTheme()->renderHTMLBodyFooter();
?>