<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/28/2016
 * Time: 1:32 PM
 */
namespace Transaction\Mail;


use System\Config\SiteConfig;
use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use PHPMailer;
use Transaction\Model\TransactionRow;
use User\Model\UserRow;

@define("PHPMAILER_DIR", dirname(dirname(__DIR__)) . '/system/support/PHPMailer/');
require_once PHPMAILER_DIR . 'PHPMailerAutoload.php';
require_once PHPMAILER_DIR . 'class.smtp.php';

class ReceiptEmail extends \PHPMailer
{
    public function __construct(OrderRow $Order, MerchantRow $Merchant) {
        parent::__construct();

        $this->Host = SiteConfig::$EMAIL_SERVER_HOST;
        $this->Username = SiteConfig::$EMAIL_USERNAME;
        $this->Password = SiteConfig::$EMAIL_PASSWORD;
        $this->Port = SiteConfig::$EMAIL_SERVER_PORT;
        $this->Timeout = SiteConfig::$EMAIL_TIMEOUT;
        $this->SMTPAuth = SiteConfig::$EMAIL_SMTP_AUTH;
        $this->SMTPSecure = SiteConfig::$EMAIL_SMTP_SECURE;
        if(SiteConfig::$EMAIL_SMTP_AUTH)
            $this->isSMTP();


        if(SiteConfig::$EMAIL_FROM_ADDRESS)
            $this->setFrom(SiteConfig::$EMAIL_FROM_ADDRESS, SiteConfig::$EMAIL_FROM_TITLE);

        $this->addAddress($Order->getPayeeEmail(), $Order->getCardHolderFullName());
        $this->addBCC($Merchant->getMainEmailID(), $Order->getCardHolderFullName());
        $this->addBCC("ari@govpaynetwork.com", $Order->getCardHolderFullName());

        $this->Subject = "Receipt: " . $Merchant->getName();

        $pu = parse_url(@$_SERVER['REQUEST_URI']);
        $url = (@$pu["host"]?:SiteConfig::$SITE_URL?:'localhost') . '/transaction/receipt.php?uid='.$Order->getUID();
        $date = date('M jS Y G:i', strtotime($Order->getDate()) ?: time());

        $content = <<<HTML
Order Information
Amount: \${$Order->getAmount()}
Merchant: {$Merchant->getName()}
Date: {$date}
Status: {$Order->getStatus()}
HTML;
        if($Order->getSubscriptionID())
            $content .= <<<HTML


Subscription Information
Status:    {$Order->getSubscriptionStatus()}
Frequency: {$Order->getSubscriptionFrequency()}
Count:     {$Order->getSubscriptionCount()}
Next Date: {$Order->getSubscriptionNextDate()}
HTML;

        if($Order->getEntryMode() == OrderRow::ENUM_ENTRY_MODE_CHECK)
            $content .= <<<HTML


E-Check Information
Account Name:   {$Order->getCheckAccountName()}
Account Type:   {$Order->getCheckAccountType()}
Account Number: {$Order->getCheckAccountNumber()}
Routing Number: {$Order->getCheckRoutingNumber()}
Type:           {$Order->getCheckType()}
HTML;
        else $content .= <<<HTML


Card Holder Information
Full Name: {$Order->getCardHolderFullName()}
Number:    {$Order->getCardNumber()}
Type:      {$Order->getCardType()}
HTML;

        $content_html = nl2br($content);

        $sig = SiteConfig::$SITE_NAME;

        $this->isHTML(true);
        $this->Body = <<<HTML
<html>
    <body>
        {$content_html}<br/>
        <br/>
        If you would like to view your receipt online, please click the following link:<br/>
        <a href="{$url}">{$url}</a><br/>

        ____<br/>
        {$sig}
    </body>
</html>
HTML;

$this->AltBody = <<<TEXT
{$content}

If you would like to view your receipt online, please click the following link:<br/>
{$url}

____
{$sig}
TEXT;

    }

    public function send2() {
        if(@$_SERVER['HTTP_HOST'] === 'localhost' || @$_SERVER['OS'] === 'Windows_NT') {
            $log = "<pre>Email was sent from localhost\n". print_r($this, true) . "</pre>";
            echo $log;
            error_log($log);
            return true;
        }
        return parent::send();
    }
}

