<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/28/2016
 * Time: 1:32 PM
 */
namespace Subscription\Mail;


use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use System\Config\SiteConfig;

@define("PHPMAILER_DIR", dirname(dirname(__DIR__)) . '/system/lib/PHPMailer/');
require_once PHPMAILER_DIR . 'PHPMailerAutoload.php';
require_once PHPMAILER_DIR . 'class.smtp.php';

class CancelEmail extends \PHPMailer
{
    public function __construct(OrderRow $Order, MerchantRow $Merchant) {
        parent::__construct();

        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

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

        $this->addAddress($Order->getPayeeEmail(), $Order->getPayeeFullName());
        $this->addBCC($Merchant->getMainEmailID(), $Order->getPayeeFullName());
        $this->addBCC("support@simonpayments.com", $Order->getPayeeFullName());

        $this->Subject = "Subscription Canceled: " . $Merchant->getName();

        $pu = parse_url(@$_SERVER['REQUEST_URI']);
        $url = (@$pu["host"]?:SiteConfig::$SITE_URL?:'localhost') . '/order/receipt.php?uid='.$Order->getUID();

        $cancel_date = date('M dS Y G:i', strtotime($Order->getSubscriptionCancelDate()) ?: time());
        $date = $Order->getDate($SessionUser->getTimeZone())->format("M dS Y g:i a");
        $next_date = $Order->getSubscriptionNextDate() ? date('M dS Y G:i', strtotime($Order->getSubscriptionNextDate())) : 'N/A';

        $content = <<<HTML
Order Information
Amount:         \${$Order->getAmount()}
Merchant:       {$Merchant->getName()}
Date:           {$date}
Status:         {$Order->getStatus()}
HTML;
        if($Order->getSubscriptionID())
            $content .= <<<HTML


Subscription Information
Status:         {$Order->getSubscriptionStatus()}
Frequency:      {$Order->getSubscriptionFrequency()}
Count:          {$Order->getSubscriptionCount()}
Next Date:      {$next_date}
Cancel Date:    {$cancel_date}
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
Full Name:      {$Order->getPayeeFullName()}
Number:         {$Order->getCardNumber()}
Type:           {$Order->getCardType()}

HTML;

        $sig = SiteConfig::$SITE_NAME;

        $this->isHTML(true);
        $this->Body = <<<HTML
<html>
    <body>
        Your subscription was canceled on {$cancel_date}.<br/>
        <pre>{$content}</pre>
        <br/>
        If you would like to view your cancellation online, please click the following link:<br/>
        <a href="{$url}">{$url}</a><br/>

        ____<br/>
        {$sig}
    </body>
</html>
HTML;

$this->AltBody = <<<TEXT
Your subscription was canceled on {$cancel_date}.

{$content}

If you would like to view your cancellation online, please click the following link:<br/>
{$url}

____
{$sig}
TEXT;

    }
}

