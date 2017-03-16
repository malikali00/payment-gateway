<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/29/2016
 * Time: 1:25 PM
 */
namespace Order\View;

use Integration\Model\IntegrationRow;
use Merchant\Model\MerchantFormRow;
use Merchant\Model\MerchantRow;
use Order\Mail\DeclineEmail;
use Order\Mail\MerchantDeclineEmail;
use Order\Mail\MerchantReceiptEmail;
use Order\Mail\ReceiptEmail;
use Order\Model\OrderRow;
use Payment\Model\PayeeRow;
use Payment\Model\PaymentRow;
use User\Session\SessionManager;
use View\AbstractView;
use View\Error\Mail\ErrorEmail;

class ChargeView extends AbstractView
{
    protected $integration;
    /** @var MerchantFormRow */
    private $form;
    /** @var MerchantRow */
    private $merchantIdentity;

    public function __construct($merchant_id, $formUID=null)    {
        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

        try {
            if($formUID) {
                $OrderForm = MerchantFormRow::fetchByUID($formUID);
            } else {
                $OrderForm = MerchantFormRow::fetchGlobalForm();
            }
        } catch (\Exception $ex) {
            $SessionManager->setMessage($ex->getMessage());
            $OrderForm = MerchantFormRow::fetchGlobalForm();
        }
        $this->form = $OrderForm;

//        $merchant_id = $OrderForm->getMerchantID();
        if($merchant_id !== null) {
            if(!$SessionUser->hasAuthority('ADMIN')) {
                if($SessionUser->getMerchantID() !== $merchant_id)
                    throw new \Exception("Invalid authorization to use form uid: " . $OrderForm->getUID());
            }
        } else {
            $merchant_id = $SessionUser->getMerchantID();
        }

        $Merchant = MerchantRow::fetchByID($merchant_id);

//        $SessionUser->setDefaultOrderForm($OrderForm);

        $integrationIDs = $Merchant->getProvisionedIntegrationIDs();
        $selectedIntegrationID = $Merchant->getDefaultIntegrationID() ?: $integrationIDs[0];
        $IntegrationRow = IntegrationRow::fetchByID($selectedIntegrationID);
        $Integration = $IntegrationRow->getIntegration();
        $this->integration = $IntegrationRow;

        $MerchantIdentity = $Integration->getMerchantIdentity($Merchant, $IntegrationRow);
        $this->merchantIdentity = $MerchantIdentity;

        parent::__construct($OrderForm->getTitle() . ' - ' . $Merchant->getName());
    }

    /**
     * @param array $params
     */
    public function renderHTMLBody(Array $params) {
        $MerchantIdentity = $this->merchantIdentity;
        $Merchant = $MerchantIdentity->getMerchantRow();

        /** @var MerchantFormRow $MerchantForm */
        $MerchantForm = $this->form;

//        $IntegrationRow = $this->integration;
//        $Integration = $IntegrationRow->getIntegration();


        $Theme = $this->getTheme();
        $Theme->addPathURL('merchant?uid='.$Merchant->getUID(), $Merchant->getName());
        $Theme->addPathURL('order/charge.php', 'New Charge');
        $Theme->renderHTMLBodyHeader();

        if(!@$params['iframe']) {
//            $Theme->addPathURL('order',               'Transactions');
            $Theme->addPathURL('order/charge.php',    $MerchantForm->getTitle());
            $Theme->printHTMLMenu('order-charge');
        }

//        if($this->hasMessage())
//            echo "<h5>", $this->getMessage(), "</h5>";

        if(isset($_SESSION[__FILE__]))
            $params += $_SESSION[__FILE__];

        // Render Order Form
        $MerchantForm->renderHTML($MerchantIdentity, $params);

        if(!@$params['iframe'])
            $Theme->renderHTMLBodyFooter();

    }

    public function processFormRequest(Array $post) {
        $baseHREF = defined("BASE_HREF") ? \BASE_HREF : '';
        $location = 'order/charge.php';

        $Order = null;
        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

        $MerchantIdentity = $this->merchantIdentity;
        $Merchant = $MerchantIdentity->getMerchantRow();

        $_SESSION[__FILE__] = $post;

        try {

            if($SessionUser->hasAuthority('ADMIN')) {

            } else {
                if($SessionUser->getMerchantID() !== $Merchant->getID())
                    throw new \Exception("User does not have authority to this merchant");
            }

            $OrderForm = $this->form;

            // Get Payment info
            if(!empty($post['payment_uid'])) {
                $PaymentInfo = PaymentRow::fetchByUID($post['payment_uid']);

            } else {
                $PayeeInfo = PayeeRow::createPayerFromPost($post);
                if(!empty($post['payment_save']))
                    PayeeRow::insertOrUpdate($PayeeInfo);

                $PaymentInfo = PaymentRow::createPaymentFromPost($post, $PayeeInfo);
                if(!empty($post['payment_save']))
                    PaymentRow::insertOrUpdate($PaymentInfo);
            }

            $Order = $MerchantIdentity->createNewOrder($PaymentInfo, $OrderForm, $post);

            $OrderForm->processFormRequest($Order, $post);

            // Perform Fraud Scrubbing
            $Order->performFraudScrubbing($MerchantIdentity, $SessionUser, $post);

            // Submit Transaction
            $Transaction = $MerchantIdentity->submitNewTransaction($Order, $SessionUser, $post);

            $location = 'order/receipt.php?uid=' . $Order->getUID();
            unset($_SESSION[__FILE__]);

            // Insert custom order fields
            foreach($OrderForm->getAllCustomFields(false) as $customField) {
                if(!empty($post[$customField])) {
                    $Order->insertCustomField($customField, $post[$customField]);
                }
            }

            // Send Customer Receipt
            if($Order->getPayeeEmail() && !empty($post['email_customer'])) {
                $EmailReceipt = new ReceiptEmail($Order, $MerchantIdentity->getMerchantRow());
                $EmailReceipt->send();
            }

            // Send Merchant Receipt
            if($Merchant->getMainEmailID() && $Merchant->hasFlag(MerchantRow::FLAG_EMAIL_APPROVE)) {
                $EmailReceipt = new MerchantReceiptEmail($Order, $MerchantIdentity->getMerchantRow());
                $EmailReceipt->send();
            }


            // TODO: If AJAX

            // Else POST

            $SessionManager->setMessage(
                "<div class='info'>Success: " . $Transaction->getStatusMessage() . "</div>"
            );
            header('Location: ' . $baseHREF . $location);
            die();

        } catch (\Exception $ex) {

            $SessionManager->setMessage(
                "<div class='error'>Error: " . $ex->getMessage() . "</div>"
            );
            header('Location: ' . $baseHREF . $location);

            error_log($ex->getMessage());

            // Delete pending orders that didn't complete
            if(!empty($post['email_decline']) && $Order) {
                // Send Decline Emails
                if ($Order->getPayeeEmail() && !empty($post['email_customer'])) {
                    $EmailReceipt = new DeclineEmail($Order, $MerchantIdentity->getMerchantRow());
                    $EmailReceipt->send();
                }

            }

            if($Order) {
                // Send Merchant Receipt
                if($Merchant->getMainEmailID() && $Merchant->hasFlag(MerchantRow::FLAG_EMAIL_APPROVE)) {
                    $EmailReceipt = new MerchantDeclineEmail($Order, $MerchantIdentity->getMerchantRow());
                    $EmailReceipt->send();
                }

                OrderRow::delete($Order);
            }

            // Send error email
            $Email = new ErrorEmail($ex);
            $Email->send();

            die();
        }
    }

    protected function renderHTMLHeadLinks() {
        parent::renderHTMLHeadLinks();

        $MerchantForm = $this->form;
        $MerchantIdentity = $this->merchantIdentity;

        // Render Head Content
        $MerchantForm->renderHTMLHeadLinks($MerchantIdentity);


    }

}