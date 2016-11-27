<?php
use Integration\Model\IntegrationRow;
use User\Session\SessionManager;
use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
/**
 * @var \Transaction\View\ChargeView $this
 **/
$odd = false;
$SessionManager = new SessionManager();
$SessionUser = $SessionManager->getSessionUser();

$LASTPOST = array();
if(isset($_SESSION['transaction/charge.php']))
    $LASTPOST = $_SESSION['transaction/charge.php'];

$Theme = $this->getTheme();
$Theme->addPathURL('order',                     'Transactions');
$Theme->addPathURL('transaction/charge.php',    'New Charge');
$Theme->renderHTMLBodyHeader();
$Theme->printHTMLMenu('order-charge');
?>


    <article class="themed">
        <section class="content">

            <?php if($this->hasMessage()) echo "<h5>", $this->getMessage(), "</h5>"; ?>

            <form name="form-transaction-charge" class=" themed" method="POST">
                <input type="hidden" name="integration_id" value="" />
                <input type="hidden" name="convenience_fee_flat" value="" />
                <input type="hidden" name="convenience_fee_limit" value="" />
                <input type="hidden" name="convenience_fee_variable_rate" value="" />

                <fieldset class="float-left-on-layout-horizontal" style="min-width:47%;">
                    <legend>Choose a Merchant</legend>
                    <table class="table-choose-merchant themed" style="float: left;">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td>
                                <select name="merchant_id" class="" required autofocus>
                                    <?php
                                    if($SessionUser->hasAuthority('ROLE_ADMIN')) {
                                        echo '<option value="">Choose a Merchant (as Admin ', $SessionUser->getUsername(), ')</option>';
                                        $MerchantQuery = MerchantRow::queryAll();
                                    } else {
                                        $MerchantQuery = $SessionUser->queryUserMerchants();
                                    }
                                    foreach ($MerchantQuery as $Merchant) {
                                        /** @var MerchantRow $Merchant */
                                        foreach ($Merchant->getMerchantIdentities() as $MerchantIdentity) {
                                            $reason = null;
                                            $Integration = $MerchantIdentity->getIntegrationRow();
                                            if($Integration->getAPIType() === IntegrationRow::ENUM_API_TYPE_DISABLED)
                                                continue;
                                            $testing = $Integration->getAPIType() === IntegrationRow::ENUM_API_TYPE_TESTING;

                                            if($MerchantIdentity->isProvisioned($reason)) {
                                                echo "\n\t\t\t\t\t\t\t<option",
                                                " data-integration-id='", $Integration->getID(), "'",
                                                " data-form-class='", $Merchant->getChargeFormClasses(), "'",
                                                " data-convenience-fee-flat='", $Merchant->getFeeFlat(), "'",
                                                " data-convenience-fee-limit='", $Merchant->getFeeLimit(), "'",
                                                " data-convenience-fee-variable-rate='", $Merchant->getFeeVariable(), "'",
//                                                (@$LASTPOST['merchant_id'] == $Merchant->getID() ? 'selected="selected" ' : ''),
                                                " value='", $Merchant->getID(), "'>",
                                                    $Merchant->getShortName(),
                                                    ( $testing || $SessionUser->hasAuthority('ROLE_ADMIN')
                                                    ? " (" . $Integration->getName() . ")" : ''),
                                                "</option>";
                                            } else {
                                                echo "\n\t\t\t\t\t\t\t<!--option disabled='disabled'>",
                                                    $Merchant->getShortName(),
                                                    " (", $Integration->getName(), ")",
                                                '</option-->';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="show-on-merchant-selected" style="min-width:47%;">
                    <legend>Choose a Payment Method</legend>
                    <table class="table-payment-method themed" style="float: left;">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td>
                                <select name="entry_mode" class="" required autofocus>
            <!--                        <option value="">Choose a method</option>-->
                                    <option value="Keyed">Keyed Card</option>
                                    <option value="Swipe">Swipe Card</option>
                                    <option value="Check">e-Check</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="show-on-merchant-selected float-left-on-layout-horizontal" style="min-width:47%; min-height: 25em;">
                    <legend>Customer Fields</legend>
                    <table class="table-transaction-charge themed" style="float: left;">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Payment Amount</td>
                            <td>
                                <input type="text" name="amount" value=""  size="6" placeholder="x.xx" required autofocus />
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Customer Name</td>
                            <td>
                                <input type="text" name="customer_first_name" value="<?php // echo @$LASTPOST['customer_first_name']; ?>" placeholder="First Name" size="12" />
                                <input type="text" name="customermi" value="<?php // echo @$LASTPOST['customermi']; ?>" placeholder="MI" size="1" /> <br/>
                                <input type="text" name="customer_last_name" value="<?php // echo @$LASTPOST['customer_last_name']; ?>" placeholder="Last Name" size="12" />
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Email</td>
                            <td><input type="text" name="payee_reciept_email" value="<?php // echo @$LASTPOST['payee_reciept_email']; ?>" placeholder="xxx@xxx.xxx" /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Phone</td>
                            <td><input type="text" name="payee_phone_number" value="<?php // echo @$LASTPOST['payee_phone_number']; ?>" placeholder="xxx-xxx-xxxx" /></td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Billing Address</td>
                            <td>
                                <input type="text" name="payee_address" value="<?php // echo @$LASTPOST['payee_address']; ?>" placeholder="Address" />
                                <br/>
                                <input type="text" name="payee_address2" value="<?php // echo @$LASTPOST['payee_address2']; ?>" placeholder="Address #2" />
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Billing Zip/State</td>
                            <td>
                                <input type="text" name="payee_zipcode" value="<?php // echo @$LASTPOST['payee_zipcode']; ?>" placeholder="ZipCode" size="6" class="zip-lookup-field-zipcode" />
                                <select name="payee_state" style="width: 7em;" class='zip-lookup-field-state-short'>
                                    <option value="">State</option>
                                    <?php
                                    foreach(\System\Arrays\Locations::$STATES as $code => $name)
                                        echo "\n\t<option value='", $code, "' ",
//                                        ($code === @$LASTPOST['payee_state'] ? ' selected="selected"' : ''),
                                        ">", $name, "</option>";
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Billing City</td>
                            <td>
                                <input type="text" name="payee_city" size="10" value="<?php // echo @$LASTPOST['payee_city']; ?>" placeholder="City" class='zip-lookup-field-city-title-case' />
                            </td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Customer&nbsp;ID#</td>
                            <td><input type="text" name="customer_id" value="<?php // echo @$LASTPOST['customer_id']; ?>" placeholder="Optional Customer ID" /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Invoice&nbsp;ID#</td>
                            <td><input type="text" name="invoice_number" value="<?php // echo @$LASTPOST['invoice_number']; ?>" placeholder="Optional Invoice Number" /></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="form-payment-method-credit show-on-merchant-selected show-on-payment-method-keyed show-on-payment-method-swipe" style="min-width:47%; min-height: 26em;">
                    <legend>Cardholder Information</legend>
                    <table class="table-transaction-charge themed">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">First Name</td>
                            <td><input type="text" name="payee_first_name" value="<?php // echo @$LASTPOST['payee_first_name']; ?>" placeholder="First Name" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Last Name</td>
                            <td><input type="text" name="payee_last_name" value="<?php // echo @$LASTPOST['payee_last_name']; ?>" placeholder="Last Name" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Card Number</td>
                            <td><input type="text" name="card_number" value="<?php // echo @$LASTPOST['card_number']; ?>" placeholder="xxxxxxxxxxxxxxxx" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Card Type</td>
                            <td>
                                <select name="card_type" required>
                                    <option value="">Choose an option</option>
                                    <option title="Visa">Visa</option>
                                    <option title="MasterCard">MasterCard</option>
                                    <option title="Amex">Amex</option>
                                    <option title="Discover">Discover</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">**CVV</td>
                            <td><input type="number" name="card_cvv2" value="<?php // echo @$LASTPOST['card_cvv2']; ?>" placeholder="xxxx" autocomplete="off" style="width: 4em;" /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Expiration</td>
                            <td>
                                <select name='card_exp_month' id='expireMM' required>
                                    <option value=''>Month</option>
                                    <option value='01'>January</option>
                                    <option value='02'>February</option>
                                    <option value='03'>March</option>
                                    <option value='04'>April</option>
                                    <option value='05'>May</option>
                                    <option value='06'>June</option>
                                    <option value='07'>July</option>
                                    <option value='08'>August</option>
                                    <option value='09'>September</option>
                                    <option value='10'>October</option>
                                    <option value='11'>November</option>
                                    <option value='12'>December</option>
                                </select>
                                <select name='card_exp_year' id='expireYY' required>
                                    <option value=''>Year</option>
                                    <?php for($i=16; $i<64; $i++) { ?>
                                    <option value='<?php echo $i; ?>'>20<?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="credit-image"></div>

                                <span style="font-size: x-small; color: grey">
                                    **The CVV Number ("Card Verification Value") on your<br/>
                                    credit card or debit card is a 3-4 digit number on <br />
                                    credit and debit cards.
                                </span>
                            </td>
                        </tr>

                    </table>
                </fieldset>


                <div class="swipe-fullscreen-box-container show-on-payment-method-swipe show-on-merchant-selected">
                    <fieldset class="themed swipe-fullscreen-box " style="min-width:47%;">
                        <legend class="alert reader-status">Please swipe your card now</legend>
                        <br />

                        <div>
                        <textarea name="card_track" rows="12" placeholder="[MagTrack Data will appear here]" style="font-size: 1.3em; width: 90%;" ><?php // echo @$LASTPOST['card_track']; ?></textarea>
                        <br />
                        <input type="button" class='submit-button' value="Close" onclick="this.form.classList.add('swipe-input-successful'); return false;" />
                        </div>

                        <br />
                    </fieldset>
                </div>

                <fieldset class="form-payment-method-check show-on-payment-method-check" style="min-width:47%; min-height: 26em;">
                    <legend>e-Check Information</legend>
                    <table class="table-transaction-charge themed">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Account Name</td>
                            <td><input type="text" name="check_account_name" value="<?php // echo @$LASTPOST['check_account_name']; ?>" placeholder="" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <td class="name">Account Type</td>
                            <td>
                                <select name="check_account_type" required>
                                    <option value="">Choose an option</option>
                                    <option>Checking</option>
                                    <option>Savings</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="color: #000092;">
                            <td class="name">Account Number</td>
                            <td><input type="text" name="check_account_number" value="<?php // echo @$LASTPOST['check_account_number']; ?>" placeholder="" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="color: #920000;">
                            <td class="name">Routing Number</td>
                            <td><input type="text" name="check_routing_number" value="<?php // echo @$LASTPOST['check_routing_number']; ?>" placeholder="" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="color: #009200;">
                            <td class="name">Check Number</td>
                            <td><input type="text" name="check_number" value="<?php // echo @$LASTPOST['check_number']; ?>" placeholder="" /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Check Type</td>
                            <td>
                                <select name="check_type" required>
                                    <option value="">Choose an option</option>
                                    <option>Personal</option>
                                    <option>Business</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="check-image"></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>


                <fieldset class="show-on-merchant-selected show-on-payment-method-selected" style="clear: both;">
                    <legend>Submit Payment</legend>


                    <table class="table-transaction-charge themed" style="float: left; width: 48%;">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Recur Count</td>
                            <td>
                                <select name='recur_count'>
                                    <option value="0">Disabled</option>
                                    <?php
                                    for($i=1; $i<=99; $i++)
                                        echo "\n\t<option ",
//                                        @$LASTPOST['recur_count'] == $i ? 'selected="selected"' : '',
                                        ">", $i, "</option>";
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Recur Amount</td>
                            <td class="value"><input type="text" name="recur_amount" placeholder="x.xx" size="6" value="<?php // echo @$LASTPOST['recur_amount']; ?>" required="required"/></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Recur Frequency</td>
                            <td>
                                <select name='recur_frequency'>
                                    <?php
//                                    if(empty($LASTPOST['recur_frequency']))
//                                        $LASTPOST['recur_frequency'] = 'Monthly';
                                    foreach(OrderRow::$ENUM_RUN_FREQUENCY as $type => $name)
                                        echo "\n\t<option value='", $type, "'",
//                                        @$LASTPOST['recur_frequency'] === $type ? ' selected="selected"' : '',
                                        ">", $name, "</option>";
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">First Recur Date</td>
                            <td><input type="date" name="recur_next_date" value="<?php // echo @$LASTPOST['recur_next_date']; ?>" required="required"/></td>
                        </tr>
                    </table>

                    <table class="table-transaction-charge themed" style="width: 48%;">
<!--                        <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
<!--                            <td class="name">Convenience Fee</td>-->
<!--                            <td><input type="text" size="6" name="convenience_fee_total" value="$0.00" disabled="disabled" /></td>-->
<!--                        </tr>-->
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">
                                Total Charge Amount
                            </td>
                            <td>
                                <input type="text" size="6" name="total_amount" value="$0.00" disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="">
                                <span class="conv-fee-text">*Includes Conv. Fee</span>
                                <div class="conv-fee-pop-up-box">
                                    *Charge includes a
                                    <br />
                                    convenience fee of
                                    <br />
                                    <br />
                                    <input type="text" size="6" name="convenience_fee" value="$0.00" disabled="disabled" style="float: right;" />
                                </div>
                            </td>
                        </tr>
                        <!--                    <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
                        <!--                        <td class="name">Method</td>-->
                        <!--                        <td><input type="text" name="entry_method" value="Keyed" disabled="disabled" /></td>-->
                        <!--                    </tr>-->
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Submit</td>
                            <td>
                                <input type="submit" value="Pay Now" class="themed" />
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Reset</td>
                            <td>
                                <input type="reset" value="Reset" class="themed" todo="clear all" />
                            </td>
                        </tr>
                    </table>
                </fieldset>

            </form>
        </section>
    </article>

    <?php
        if(sizeof($LASTPOST) > 0) {
            $json = json_encode($LASTPOST);
            ?>
            <script>
                var LASTPOST = <?php echo $json; ?>;
                var chargeForm = document.getElementsByName('form-transaction-charge')[0];
                for(var key in LASTPOST) {
                    if(LASTPOST.hasOwnProperty(key)) {
                        var value = LASTPOST[key];
                        console.log("Updating form with saved value: " + key);
                        chargeForm[key].value = value;
                    }
                }
                if(LASTPOST.card_track)
                    chargeForm.classList.add('swipe-input-successful');
            </script>
            <?php
        }
    ?>

    <?php $Theme->renderHTMLBodyFooter(); ?>


