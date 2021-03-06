<?php
use Merchant\Model\MerchantRow;
use System\Config\SiteConfig;

/** @var \Order\View\OrderView $this*/

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

$SITE_CUSTOMER_NAME = SiteConfig::$SITE_DEFAULT_CUSTOMER_NAME;
?>

<article class="themed">

    <section class="content">


        <form name="form-order-view" id="form-order-view" class="themed" method="POST">
            <fieldset>
                <legend><?php echo $Merchant->getShortName(); ?></legend>
                <table class="table-transaction-info themed striped-rows" style="width:60%">
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
                        <td class="name"><?php echo $SITE_CUSTOMER_NAME; ?></td>
                        <td class="value"><?php echo $Order->getCustomerFullName() ?></td>
                    </tr>

                    <?php if($Order->getCustomerID()) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name"><?php echo $SITE_CUSTOMER_NAME; ?></td>
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

            <fieldset>
                <legend>Receipt</legend>
                <table class="table-transaction-info themed striped-rows" style="width:60%">
                    <tbody>

                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name" style="width: 30%;">Date</td>
                        <td class="value"><?php echo $Order->getDate($SessionUser->getTimeZone())->format("F jS, Y"); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name" style="width: 30%;">Time</td>
                        <td class="value"><?php echo $Order->getDate($SessionUser->getTimeZone())->format("g:i:s A"); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="name" style="width: 30%;">Time Zone</td>
                        <td class="value"><?php echo $Order->getDate($SessionUser->getTimeZone())->format("e P"); ?></td>
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
                    <legend>Card Holder: <?php echo $Order->getPayeeFullName(); ?></legend>
                    <table class="table-transaction-info themed cell-borders" style="width: 98%; text-align: left;">
                        <tbody>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>" style="font-weight: bold;">
                            <td>Order ID</td>
                            <?php if($Order->getUsername()) { ?>
                                <td>User ID</td>
                            <?php }  ?>
                            <td>Credit Card</td>
                            <td>Card Type</td>
                            <td>Status</td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="value"><?php echo $Order->getID(); ?></td>
                            <?php if($Order->getUsername()) { ?>
                                <td class="value"><?php echo $Order->getUsername(); ?></td>
                            <?php }  ?>
                            <td class="value"><?php echo $Order->getCardNumber(); ?></td>
                            <td class="value"><?php echo $Order->getCardType(); ?></td>
                            <td class="value"><?php echo $Order->getStatus(); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>

            <?php } else  { ?>

                <fieldset>
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



            <fieldset style=" float: right;">
                <legend>Totals</legend>
                <table class="table-transaction-info-totals themed striped-rows" style="width:60%">
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
                        <td class="name" style="font-weight: bold;">Total</td>
                        <td class="value" style="font-weight: bold;">$<?php echo number_format($Order->getAmount()+$Order->getConvenienceFee(), 2); ?></td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>


            <div class="show-on-print" style="clear: both;">
                <br/>
                <br/>
                <br/>
                <hr style="height: 2px;">
                <?php echo $SITE_CUSTOMER_NAME; ?> Signature
            </div>



        </form>
    </section>
</article>
