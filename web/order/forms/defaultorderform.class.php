<?php
/**
 * Created by PhpStorm.
 * User: Ari
 * Date: 1/15/2017
 * Time: 4:24 PM
 */
namespace Order\Forms;

use Integration\Model\AbstractMerchantIdentity;
use Merchant\Model\MerchantFormRow;
use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use System\Arrays\Locations;
use System\Config\SiteConfig;
use User\Session\SessionManager;

class DefaultOrderForm extends AbstractForm
{
    const _CLASS = __CLASS__;

    /**
     * Render HTML Head content
     * @param MerchantFormRow $MerchantForm
     * @param AbstractMerchantIdentity $MerchantIdentity
     */
    function renderHTMLHeadLinks(MerchantFormRow $MerchantForm, AbstractMerchantIdentity $MerchantIdentity)
    {

        echo <<<HEAD
        <script src="https://clevertree.github.io/zip-lookup/zip-lookup.min.js" type="text/javascript" ></script>
        <script src="order/view/assets/charge.js?v=1"></script>
        <link href='order/forms/assets/default-order-form.css' type='text/css' rel='stylesheet' />
HEAD;
        $IntegrationRow = $MerchantIdentity->getIntegrationRow();
        $Integration = $IntegrationRow->getIntegration();
        $Integration->renderChargeFormHTMLHeadLinks($MerchantIdentity);

    }

    /**
     * Render custom order form HTML
     * @param MerchantFormRow $MerchantForm
     * @param AbstractMerchantIdentity $MerchantIdentity
     * @param array $params
     * @return mixed
     */
    function renderHTML(MerchantFormRow $MerchantForm, AbstractMerchantIdentity $MerchantIdentity, Array $params)
    {
        $Merchant = $MerchantIdentity->getMerchantRow();
        $IntegrationRow = $MerchantIdentity->getIntegrationRow();
        $Integration = $IntegrationRow->getIntegration();
        // Render Order Form
        
        $odd = false;
        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

        $LASTPOST = array();
        if(isset($_SESSION[__FILE__]))
            $LASTPOST = $_SESSION[__FILE__];

        $SITE_CUSTOMER_NAME = SiteConfig::$SITE_DEFAULT_CUSTOMER_NAME;

        ?>
        <article class="themed">
            <section class="content">
                <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>
                <form name="form-transaction-charge"
                      class="default-order-form <?php echo $MerchantForm->getFormClasses(); ?> payment-method-keyed payment-method-card themed"
                      method="POST"
                    >

                    <input type="hidden" name="merchant_id" value="<?php echo $Merchant->getID(); ?>"/>
                    <input type="hidden" name="merchant_uid" value="<?php echo $Merchant->getUID(); ?>" />
                    <input type="hidden" name="form_uid" value="<?php echo $MerchantForm->getUID(); ?>"/>

                    <input type="hidden" name="convenience_fee_flat" value="<?php echo $Merchant->getConvenienceFeeFlat(); ?>"/>
                    <input type="hidden" name="convenience_fee_limit" value="<?php echo $Merchant->getConvenienceFeeLimit(); ?>"/>
                    <input type="hidden" name="convenience_fee_variable_rate" value="<?php echo $Merchant->getConvenienceFeeVariable(); ?>" />
                    <input type="hidden" name="integration_uid" value="<?php echo $IntegrationRow->getUID(); ?>" />

                    <?php if($Merchant->getFraudHighLimit() > 1) { ?>
                        <input type="hidden" name="fraud_high_limit" value="<?php echo $Merchant->getFraudHighLimit(); ?>" />
                    <?php } ?>

                    <?php $Integration->renderChargeFormHiddenFields($MerchantIdentity); ?>

                    <fieldset class="stretch-box" style="min-width:44%; ">
                        <div class="legend">Payment Method: <?php echo $Merchant->getName(); ?></div>
                        <table class="table-payment-method" style="float: left;">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td>
                                    <select name="entry_mode" class="" required autofocus title="Choose an entry method">
                                        <!--                        <option value="">Choose a method</option>-->
                                        <option value="Keyed">Keyed Card</option>
                                        <option value="Swipe">Swipe Card</option>
                                        <option value="Check">e-Check</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <fieldset class="stretch-box" style="min-width:44%;">
                        <div class="legend">Order Form Options</div>
                        <table class="table-choose-merchant" style="float: left;">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td>
                                    <select name="change_form_url" class=""
                                            title="Select a charge form template">
                                        <optgroup label="Switch Templates">
                                        <?php
                                        $MerchantFormQuery = MerchantFormRow::queryAvailableForms($SessionUser->getMerchantID());
                                        foreach ($MerchantFormQuery as $Form) {
                                            echo "\n\t\t\t\t\t\t\t<option",
                                            ($Form->getID() === $MerchantForm->getID() ? ' selected="selected" value=""' :
                                                " value='?form_uid=" . $Form->getUID() . "&merchant_uid=" . $Merchant->getUID() . "'"),
                                            ">",
                                            $Form->getTitle(),
                                            "</option>";
                                        }
                                        ?>
                                        </optgroup>
                                    </select>
                                    <a href="merchant/form.php?uid=<?php echo $MerchantForm->getUID(); ?>" style="float: right; display: inline-block; padding: 2px 8px;">
                                        <div class="app-button app-button-edit" style="font-size: 24px;"></div>
                                    </a>

                                </td>
                            </tr>
                        </table>
                    </fieldset>



                    <fieldset class="form-payment-method-credit stretch-box show-on-payment-method-card" style="min-width:44%; min-height: 21em;">
                        <div class="legend">Cardholder Information</div>
                        <table class="table-transaction-charge themed" style="display: inline-block;">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">First Name</td>
                                <td><input type="text" name="payee_first_name" placeholder="First Name" required /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">Last Name</td>
                                <td><input type="text" name="payee_last_name" placeholder="Last Name" required /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">Card Number</td>
                                <td><input type="text" name="card_number" placeholder="xxxxxxxxxxxxxxxx" required pattern=".{5,16}" title=""/></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">Card Type</td>
                                <td>
                                    <select name="card_type" required title="Choose a Card Type">
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
                                <td><input type="text" name="card_cvv2" placeholder="xxxx" autocomplete="off" style="width: 4em;" /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">Expiration</td>
                                <td>
                                    <select name='card_exp_month' id='expireMM' required title="Choose a card expiration month">
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
                                    <select name='card_exp_year' id='expireYY' required title="Choose an expiration year">
                                        <option value=''>Year</option>
                                        <?php for($i=date('y'); $i<64; $i++) { ?>
                                            <option value='<?php echo $i; ?>'>20<?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="white-space: normal; padding-left: 2em;">
                                    <div class="credit-image small" style="margin: 7px 0 5px 5px;"></div>
                                    <div class="cvv2-image small" style="display: inline-block; "></div>
                                    <div style="font-size: x-small; color: grey; padding: 1em; max-width: 200px;">
                                        **The CVV Number ("Card Verification Value") on your credit card
                                        or debit card is a 3-4 digit number on credit and debit cards.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>


                    <div class="swipe-fullscreen-box-container show-on-payment-method-swipe">
                        <fieldset class="themed swipe-fullscreen-box " style="min-width:44%; padding: 8px;">
                            <div class="legend alert reader-status">Please swipe your card now</div>
                            <br />

                            <div>
                                <textarea name="card_track" rows="8" placeholder="[MagTrack Data will appear here]" style="font-size: 1.3em; width: 90%;" ><?php // echo @$LASTPOST['card_track']; ?></textarea>
                                <br />
                                <input type="button" class='themed' value="Close" onclick="this.form.classList.add('swipe-input-successful'); return false;" />
                            </div>

                            <br />
                        </fieldset>
                    </div>

                    <fieldset class="form-payment-method-check stretch-box show-on-payment-method-check" style="min-width:44%; min-height: 21em;">
                        <div class="legend">e-Check Information</div>
                        <table class="table-transaction-charge themed">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Name on Account</td>
                                <td><input type="text" name="check_account_name" placeholder="" /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Bank Name</td>
                                <td><input type="text" name="check_account_bank_name" placeholder="" /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">Account Type</td>
                                <td>
                                    <select name="check_account_type" required title="Choose a Checking Account Type">
                                        <option value="">Choose an option</option>
                                        <option>Checking</option>
                                        <option>Savings</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="color: #000092;">
                                <td class="name">Account Number</td>
                                <td><input type="text" name="check_account_number" placeholder="" required /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="color: #920000;">
                                <td class="name">Routing Number</td>
                                <td><input type="text" name="check_routing_number" placeholder="" required /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="color: #009200;">
                                <td class="name">Check Number</td>
                                <td><input type="text" name="check_number" placeholder="" /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Check Type</td>
                                <td>
                                    <select name="check_type" required title="Choose a Check Type">
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

                    <fieldset class="stretch-box" style="min-width:44%; min-height: 21em;">
                        <div class="legend"><?php echo $SITE_CUSTOMER_NAME; ?> Fields</div>
                        <table class="table-transaction-charge themed" style="float: left;">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <td class="name">Payment Amount</td>
                                <td>
                                    <input type="text" name="amount" value=""  size="6" placeholder="x.xx" required autofocus/>
                                </td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name"><?php echo $SITE_CUSTOMER_NAME; ?> Name</td>
                                <td>
                                    <input type="text" name="customer_first_name" placeholder="First Name" size="12" />
                                    <input type="text" name="customermi" placeholder="MI" size="1" /> <br/>
                                    <input type="text" name="customer_last_name" placeholder="Last Name" size="12" />
                                </td>
                            </tr>
                            <?php if($MerchantForm->hasField('payee_receipt_email')) { ?>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name">Email</td>
                                    <td><input type="text" name="payee_reciept_email" placeholder="xxx@xxx.xxx" <?php echo $MerchantForm->isFieldRequired('payee_receipt_email') ? 'required ' : ''; ?>/></td>
                                </tr>
                            <?php } ?>
                            <?php if($MerchantForm->hasField('payee_phone_number')) { ?>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name">Phone</td>
                                    <td><input type="text" name="payee_phone_number" placeholder="xxx-xxx-xxxx" <?php echo $MerchantForm->isFieldRequired('payee_phone_number') ? 'required ' : ''; ?> /></td>
                                </tr>
                            <?php } ?>

                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Billing Address</td>
                                <td>
                                    <input type="text" name="payee_address" placeholder="Address" />
                                    <br/>
                                    <input type="text" name="payee_address2" placeholder="Address #2" />
                                </td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Billing Zip/State</td>
                                <td>
                                    <input type="text" name="payee_zipcode" placeholder="ZipCode" size="6" class="zip-lookup-field-zipcode" />
                                    <select name="payee_state" style="width: 7em;" class='zip-lookup-field-state-short' title="Choose a billing state">
                                        <option value="">State</option>
                                        <?php
                                        foreach(Locations::$STATES as $code => $name)
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
                                    <input type="text" name="payee_city" size="10" placeholder="City" class='zip-lookup-field-city-title-case' />
                                </td>
                            </tr>

                            <?php if($MerchantForm->hasField('customer_id')) { ?>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name"><?php echo $SITE_CUSTOMER_NAME; ?>&nbsp;ID#</td>
                                    <td><input type="text" name="customer_id" placeholder="Customer ID" <?php echo $MerchantForm->isFieldRequired('customer_id') ? 'required ' : ''; ?>/></td>
                                </tr>
                            <?php } ?>

                            <?php if($MerchantForm->hasField('invoice_number')) { ?>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name">Invoice&nbsp;ID#</td>
                                    <td><input type="text" name="invoice_number" placeholder="Invoice Number" <?php echo $MerchantForm->isFieldRequired('invoice_number') ? 'required ' : ''; ?>/></td>
                                </tr>
                            <?php } ?>


                            <?php
                            foreach($MerchantForm->getAllCustomFields(false) as $field) {
                                $title = $MerchantForm->getCustomFieldName($field);
                                ?>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name"><?php echo $title; ?></td>
                                    <td>
                                        <input type="text" name="<?php echo $field; ?>" placeholder="<?php echo $title; ?>" <?php echo $MerchantForm->isFieldRequired($field) ? 'required ' : ''; ?>/>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                        </table>
                    </fieldset>


                    <?php if($MerchantForm->isRecurAvailable()) { ?>
                    <fieldset class="stretch-box" style="display: inline-block; min-width: 94%;" <?php echo $MerchantForm->isRecurAvailable() ? '' : 'disabled '; ?>>
                        <div class="legend">Re-bill Schedule</div>
                        <table class="table-transaction-charge themed" style="float: left; width: 44%;">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Re-bill Count</td>
                                <td>
                                    <select name='recur_count' title="The number of times an order will automatically re-bill">
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
                                <td class="name">Re-bill Amount</td>
                                <td class="value"><input type="text" name="recur_amount" placeholder="x.xx" size="6" required="required"/></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Re-bill Frequency</td>
                                <td>
                                    <select name='recur_frequency' title="Choose the frequency in which this order will automatically re-bill">
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
                                <td class="name">First Re-bill Date</td>
                                <td><input type="date" name="recur_next_date" required="required" style="max-width: 10em;"/></td>
                            </tr>
                        </table>
                    </fieldset>
                    <?php } ?>

                    <fieldset class="<?php if($MerchantForm->isRecurAvailable()) { ?>stretch-box<?php } ?>" style="display: inline-block; min-width: 92%;"
                        <?php if(!empty($_GET['disabled'])) echo 'disabled="disabled"'; ?>
                    >
                        <div class="legend">Submit Payment</div>
                        <table class="table-transaction-charge themed" style="width: 44%;">
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
                                    <input type="reset" value="Reset" class="themed" onclick="return confirm('Are you sure you want to reset all form values?');" />
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


    }

    /**
     * Process form submission
     * @param MerchantFormRow $MerchantForm
     * @param OrderRow $Order
     * @param array $post
     * @return mixed
     */
    function processFormRequest(MerchantFormRow $MerchantForm, OrderRow $Order, Array $post) {
//        $_SESSION[__FILE__] = $post;
//        $_SESSION[__FILE__]['order_id'] = $Order->getID();
    }
}