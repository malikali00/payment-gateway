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

class SimpleOrderForm extends AbstractForm
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
        <script src="order/view/assets/charge.js"></script>
        <link href='order/forms/assets/simple-order-form.css' type='text/css' rel='stylesheet' />
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
        $Integration = $IntegrationRow->getIntegration();        // Render Order Form

        $odd = false;
        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

        $LASTPOST = array();
        if(isset($_SESSION[__FILE__]))
            $LASTPOST = $_SESSION[__FILE__];

        $SITE_CUSTOMER_NAME = SiteConfig::$SITE_DEFAULT_CUSTOMER_NAME;

        ?>
        <article class="themed" style="text-align: center; clear: right;">
            <section class="content" style="text-align: left; display: inline-block;">
                <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>
                <form name="form-transaction-charge"
                      class="simple-order-form <?php echo $MerchantForm->getFormClasses(); ?> payment-method-keyed payment-method-card themed"
                      method="POST"
                >
                    <input type="hidden" name="merchant_id" value="<?php echo $Merchant->getID(); ?>" />
                    <input type="hidden" name="merchant_uid" value="<?php echo $Merchant->getUID(); ?>" />
                    <input type="hidden" name="form_uid" value="<?php echo $MerchantForm->getUID(); ?>" />

                    <input type="hidden" name="convenience_fee_flat" value="<?php echo $Merchant->getConvenienceFeeFlat(); ?>" />
                    <input type="hidden" name="convenience_fee_limit" value="<?php echo $Merchant->getConvenienceFeeLimit(); ?>" />
                    <input type="hidden" name="convenience_fee_variable_rate" value="<?php echo $Merchant->getConvenienceFeeVariable(); ?>" />
                    <input type="hidden" name="integration_uid" value="<?php echo $IntegrationRow->getUID(); ?>" />

                    <?php if($Merchant->getFraudHighLimit() > 1) { ?>
                        <input type="hidden" name="fraud_high_limit" value="<?php echo $Merchant->getFraudHighLimit(); ?>" />
                    <?php } ?>

                    <?php $Integration->renderChargeFormHiddenFields($MerchantIdentity); ?>

                    <fieldset class="" style="max-width: 45em;">
                        <div class="legend">Enter Payment Details</div>
                       <div style="float: left;">

                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <span><?php echo $SITE_CUSTOMER_NAME; ?> Name</span>
                                <input type="text" name="payee_full_name" placeholder="Customer Name" required autofocus/>
                            </label>

                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                <span>Payment Amount</span>
                                <input type="text" name="amount" value=""  size="6" placeholder="x.xx" required />
                            </label>

                            <?php if($MerchantForm->hasField('payee_receipt_email')) { ?>
                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> <?php echo $MerchantForm->isFieldRequired('payee_receipt_email') ? 'required ' : ''; ?>">
                                <span>Email</span>
                                <input type="text" name="payee_reciept_email" placeholder="xxx@xxx.xxx" <?php echo $MerchantForm->isFieldRequired('payee_receipt_email') ? 'required ' : ''; ?>/>
                            </label>
                            <?php } ?>


                            <?php if($MerchantForm->hasField('payee_phone_number')) { ?>
                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> <?php echo $MerchantForm->isFieldRequired('payee_phone_number') ? 'required ' : ''; ?>">
                                    <span>Phone</span>
                                    <input type="text" name="payee_phone_number" placeholder="xxx-xxx-xxxx" <?php echo $MerchantForm->isFieldRequired('payee_phone_number') ? 'required ' : ''; ?> />
                                </label>
                            <?php } ?>

                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <span>Billing Address</span>
                                <div style="display: inline-block;">
                                    <input type="text" name="payee_address" placeholder="Address" />
                                    <br/>
                                    <input type="text" name="payee_address2" placeholder="Address #2" />
                                </div>
                            </label>

                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <span>Billing Zip/State</span>
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
                            </label>

                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <span>Billing City</span>
                                <input type="text" name="payee_city" size="10" placeholder="City" class='zip-lookup-field-city-title-case' />
                            </label>

                        </div>
                        <div style="float: left;">


                            <?php
                            foreach($MerchantForm->getAllCustomFields(true) as $field) {
                                if(in_array($field, array('payee_receipt_email', 'payee_phone_number')))
                                    continue;
                            $title = $MerchantForm->getCustomFieldName($field);
                            ?>
                            <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> <?php echo $MerchantForm->isFieldRequired($field) ? 'required ' : ''; ?>">
                                <span><?php echo $title; ?></span>
                                <input type="text" name="<?php echo $field; ?>" placeholder="<?php echo $title; ?>" <?php echo $MerchantForm->isFieldRequired($field) ? 'required ' : ''; ?>/>
                            </label>
                            <?php
                            }
                            ?>
                        </div>
                    </fieldset>


                    <fieldset class="" style="max-width: 45em;">
                        <div class="legend">Choose a Payment Method</div>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required" style="display: none;">
                            <span>Payment Method</span>

                            <select name="entry_mode2" class="" required autofocus title="Choose an entry method">
                                <option value="Keyed">Keyed Card</option>
                                <option value="Swipe">Swipe Card</option>
                                <option value="Check">e-Check</option>
                            </select>
                        </label>

                        <div class="entry-options">
                            <label class="entry-option entry-option-keyed">
                                <input type="radio" name="entry_mode" value="Keyed" checked>
                                Credit Card (Keyed)
                            </label>
                            <label class="entry-option entry-option-swipe">
                                <input type="radio" name="entry_mode" value="Swipe">
                                Credit Card (Swipe)
                            </label>
                            <label class="entry-option entry-option-check">
                                <input type="radio" name="entry_mode" value="Check">
                                e-Check (ACH)
                            </label>
                        </div>


                        <div class="show-on-payment-method-card entry-section">
                            <div style="display: inline-block">
                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Card Number</span>
                                    <input type="text" name="card_number" placeholder="xxxxxxxxxxxxxxxx" required />
                                </label>


                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Card Type</span>
                                    <select name="card_type" required title="Choose a Card Type">
                                        <option value="">Choose an option</option>
                                        <option title="Visa">Visa</option>
                                        <option title="MasterCard">MasterCard</option>
                                        <option title="Amex">Amex</option>
                                        <option title="Discover">Discover</option>
                                    </select>
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <span>**CVV</span>
                                    <input type="text" name="card_cvv2" placeholder="xxxx" autocomplete="off" style="width: 4em;" />
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Expiration</span>
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
                                </label>

                                <div class="credit-image small" style="margin: 7px auto -3px auto;"></div>
                                <div style="font-size: x-small; color: grey; padding: 1em; max-width: 300px;">
                                    **The CVV Number ("Card Verification Value") on your credit card
                                    or debit card is a 3-4 digit number on credit and debit cards.
                                </div>
                            </div>

                            <div style="margin: 10px 5px; vertical-align: top; display: inline-block;">
                                <div class="cvv2-image" style="display: inline-block; float: left;"></div>
                            </div>
                        </div>

                        <div class="show-on-payment-method-check entry-section">
                            <div style="display: inline-block;">
                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <span>Name on Account</span>
                                    <input type="text" name="check_account_name" placeholder="" />
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <span>Bank Name</span>
                                    <input type="text" name="check_account_bank_name" placeholder="" />
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Account Type</span>
                                    <select name="check_account_type" title="Choose a Checking Account Type">
                                        <option>Checking</option>
                                        <option>Savings</option>
                                    </select>
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Account Number</span>
                                    <input type="text" name="check_account_number" placeholder="" required />
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Routing Number</span>
                                    <input type="text" name="check_routing_number" placeholder="" required />
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Check Number</span>
                                    <input type="text" name="check_number" placeholder="" />
                                </label>

                                <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                                    <span>Check Type</span>
                                    <select name="check_type" title="Choose a Check Type">
                                        <option>Personal</option>
                                        <option>Business</option>
                                    </select>
                                </label>
                            </div>
                            <div style="display: inline-block; padding: 0 1em;">
                                <div class="check-image"></div>
                            </div>
                        </div>
                    </fieldset>



                    <div class="swipe-fullscreen-box-container show-on-payment-method-swipe">
                        <fieldset class="themed swipe-fullscreen-box " style="min-width:45%; padding: 8px;">
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


                    <?php if($MerchantForm->isRecurAvailable()) { ?>
                    <fieldset class="" style="max-width: 45em; clear: both;" <?php echo $MerchantForm->isRecurAvailable() ? '' : 'disabled '; ?>>
                        <div class="legend">Recurring Payment</div>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <span>Re-bill Count</span>
                            <select name='recur_count' title="The number of times an order will automatically re-bill">
                                <option value="0">Disabled</option>
                                <?php
                                for($i=1; $i<=99; $i++)
                                    echo "\n\t<option ",
                                        //                                        @$LASTPOST['recur_count'] == $i ? 'selected="selected"' : '',
                                    ">", $i, "</option>";
                                ?>
                            </select>
                        </label>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <span>Re-bill Amount</span>
                            <input type="text" name="recur_amount" placeholder="x.xx" size="6" required="required"/>
                        </label>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <span>Re-bill Frequency</span>
                            <select name='recur_frequency' title="Choose the frequency in which this order will automatically re-bill">
                                <?php
                                foreach(OrderRow::$ENUM_RUN_FREQUENCY as $type => $name)
                                    echo "\n\t<option value='", $type, "'",
                                    ">", $name, "</option>";
                                ?>
                            </select>
                        </label>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <span>Next Re-bill Date</span>
                            <input type="date" name="recur_next_date" required="required" style="max-width: 10em;"/>
                        </label>
                    </fieldset>
                    <?php } ?>

                    <fieldset class="" style="clear: both; max-width: 45em;"
                        <?php if(!empty($_GET['disabled'])) echo 'disabled="disabled"'; ?>
                    >
                        <div class="legend">Submit Order</div>


                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <span>Total</span>
                            <input type="text" size="6" name="total_amount" value="$0.00" disabled="disabled" />
                        </label>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> ">
                            <span>Fee</span>
                            <div style="display: inline-block;" class="conv-fee-text">Includes Conv. Fee</div>
                            <div class="conv-fee-pop-up-box">
                                *Charge includes a
                                <br />
                                convenience fee of
                                <br />
                                <br />
                                <input type="text" size="6" name="convenience_fee" value="$0.00" disabled="disabled" style="float: right;" />
                            </div>
                        </label>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?> required">
                            <span>Submit</span>
                            <input type="submit" value="Pay Now" class="themed" />
                        </label>

                        <label class="field-row row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <span>Reset</span>
                            <input type="reset" value="Reset" class="themed" onclick="return confirm('Are you sure you want to reset all form values?');" />
                        </label>
                    </fieldset>


                    <fieldset class="" style="max-width: 45em;">
                        <div class="legend">Form Options: <?php echo $Merchant->getShortName(); ?></div>
                        <?php if(sizeof($SessionUser->getMerchantList()) > 1) { ?>
                            <label class="">
                                <select name="change_merchant_url" class="">
                                    <option value="">Switch Merchant</option>
                                    <?php
                                    $MerchantQuery = $SessionUser->queryUserMerchants();
                                    foreach ($MerchantQuery as $MerchantOption) {
                                        /** @var MerchantRow $MerchantOption */
                                        echo "\n\t\t\t\t\t\t\t<option",
                                        ($MerchantOption->getID() === $Merchant->getID() ? ' selected="selected" value=""' :
                                            " value='?form_uid=" . $MerchantForm->getUID() . "&merchant_id=" . $MerchantOption->getID() . "'"), '>',
                                        $MerchantOption->getShortName(),
                                        "</option>";
                                    }
                                    ?>
                                </select>
                                </label>
                        <?php } ?>

                        <select name="change_form_url" class=""
                                title="Select a charge form template">
                            <option value="">Switch Templates</option>
                            <?php
                            $MerchantFormQuery = MerchantFormRow::queryAvailableForms($SessionUser->getID());
                            foreach ($MerchantFormQuery as $Form) {
                                echo "\n\t\t\t\t\t\t\t<option",
                                ($Form->getID() === $MerchantForm->getID() ? ' selected="selected" value=""' :
                                    " value='?form_uid=" . $Form->getUID() . "&merchant_id=" . $Merchant->getID() . "'"),
                                ">",
                                $Form->getTitle(),
                                "</option>";
                            }
                            ?>
                        </select>
                        <a href="merchant/form.php?uid=<?php echo $MerchantForm->getUID(); ?>" style="display: inline-block; ">
                            <div class="app-button app-button-edit" style="font-size: 24px; margin: 0 0 -6px 3px;"></div>
                        </a>
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