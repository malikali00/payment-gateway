<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/28/2016
 * Time: 1:32 PM
 */
namespace System\Mail;


use Merchant\Model\MerchantRow;
use System\Model\EmailTemplateRow;
use User\Model\UserRow;
use System\Config\SiteConfig;
use User\Session\SessionManager;

@define("SWIFTMAILER_DIR", dirname(dirname(dirname(__DIR__))) . '/support/swiftmailer/');
require_once SWIFTMAILER_DIR . 'lib/swift_required.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/IoBuffer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/StreamBuffer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/StreamFilters/StringReplacementFilterFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/AuthHandler.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/CramMd5Authenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/LoginAuthenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/PlainAuthenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mailer/RecipientIterator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mailer/ArrayRecipientIterator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/MimePart.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Encoder/QpEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Encoder/Base64Encoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Encoder/Rfc2231Encoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/FileStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/ByteStream/AbstractFilterableInputStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/ByteStream/FileByteStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/ByteStream/ArrayByteStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/ByteStream/TemporaryFileByteStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/KeyCache/NullKeyCache.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/KeyCache/SimpleKeyCacheInputStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/KeyCache/KeyCacheInputStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/KeyCache/DiskKeyCache.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/KeyCache/ArrayKeyCache.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/StreamFilter.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/InputByteStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/NullTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mailer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Attachment.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/LoadBalancedTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/MemorySpool.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/DependencyException.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/ResponseEvent.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/ResponseListener.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/TransportExceptionEvent.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/CommandListener.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/EventListener.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/TransportExceptionListener.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/Event.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/TransportChangeEvent.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/CommandEvent.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/EventDispatcher.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/SendEvent.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/EventObject.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/SendListener.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/TransportChangeListener.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Events/SimpleEventDispatcher.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterReader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/FileSpool.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/KeyCache.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Preferences.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/SpoolTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Filterable.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/FailoverTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/MimePart.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Header.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Attachment.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/EncodingObserver.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ParameterizedHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Grammar.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/MimeEntity.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/HeaderEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Message.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/SimpleHeaderSet.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/SimpleMimeEntity.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/HeaderSet.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/CharsetObserver.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/ParameterizedHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/PathHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/DateHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/AbstractHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/MailboxHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/IdentificationHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/UnstructuredHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/Headers/OpenDKIMHeader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/EmbeddedFile.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/HeaderEncoder/QpHeaderEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/HeaderEncoder/Base64HeaderEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/SimpleHeaderFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/HeaderFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/SimpleMessage.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder/NativeQpContentEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder/RawContentEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder/QpContentEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder/Base64ContentEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder/QpContentEncoderProxy.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Mime/ContentEncoder/PlainContentEncoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Message.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterReaderFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Image.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/RfcComplianceException.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterReader/Utf8Reader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterReader/UsAsciiReader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterReader/GenericFixedWidthReader.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/ConfigurableSpool.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterStream/ArrayCharacterStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterStream/NgCharacterStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Encoding.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/SwiftException.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/NullTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/LoadBalancedTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/IoBuffer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/AbstractSmtpTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/SpoolTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/FailoverTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/EsmtpTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/StreamBuffer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/EsmtpHandler.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/MailInvoker.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/SimpleMailInvoker.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/SmtpAgent.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/MailTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/PlainAuthenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/CramMd5Authenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/XOAuth2Authenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/LoginAuthenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Auth/NTLMAuthenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/AuthHandler.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/Esmtp/Authenticator.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Transport/SendmailTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/TransportException.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Validate.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Encoder.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/StreamFilters/StringReplacementFilterFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/StreamFilters/StringReplacementFilter.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/StreamFilters/ByteArrayReplacementFilter.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/EmbeddedFile.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/SignedMessage.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/ReplacementFilterFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/DependencyContainer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signers/DKIMSigner.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signers/HeaderSigner.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signers/BodySigner.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signers/OpenDKIMSigner.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signers/DomainKeySigner.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Signers/SMimeSigner.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/MailTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/CharacterReaderFactory/SimpleCharacterReaderFactory.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/OutputByteStream.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Spool.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/SmtpTransport.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/ThrottlerPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/DecoratorPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Pop/Pop3Connection.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Pop/Pop3Exception.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/LoggerPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Logger.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/PopBeforeSmtpPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/ReporterPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Loggers/ArrayLogger.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Loggers/EchoLogger.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Timer.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Sleeper.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/ImpersonatePlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Reporters/HtmlReporter.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Reporters/HitReporter.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Reporter.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/BandwidthMonitorPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/RedirectingPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/MessageLogger.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/AntiFloodPlugin.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/Plugins/Decorator/Replacements.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/IoException.php';
require_once SWIFTMAILER_DIR . 'lib/classes/Swift/SendmailTransport.php';


if(!class_exists("Swift_SmtpTransport"))
    error_log("Class not found: Swift_SmtpTransport");

abstract class AbstractEmail
{
    const TITLE = null;
    const TEMPLATE_SUBJECT = null;
    const TEMPLATE_BODY = null;

    protected $subject;
    protected $body;

    protected $to = array();
    protected $from = array();
    protected $bcc = array();

    public function __construct() {
        $this->subject = static::TEMPLATE_SUBJECT;
        $this->body = static::TEMPLATE_BODY;

        if(SiteConfig::$EMAIL_FROM_ADDRESS)
            $this->from = array(
                SiteConfig::$EMAIL_FROM_ADDRESS => SiteConfig::$EMAIL_FROM_TITLE
            );
    }

    protected function processTemplate(Array $params, MerchantRow $Merchant=null) {
        // Query email template
        if($Merchant) {
            $class = get_class($this);
            $EmailTemplate = EmailTemplateRow::fetchAvailableTemplate($class, $Merchant->getID());
            if($EmailTemplate) {
                // Replace email template
                $this->body = $EmailTemplate->getBody();
                $this->subject = $EmailTemplate->getSubject();
            }
        }

        // Pre-process site constants
        self::processTemplateConstants($this->body, $this->subject, $Merchant);

        foreach($params as $name => $value) {
            $this->body = str_replace('{$' . $name . '}', $value, $this->body);
            $this->subject = str_replace('{$' . $name . '}', $value, $this->subject);
        }

        if(strpos($this->body, '{$')!==false) error_log("Not all variables were replaced: \n" . $this->body);
        if(strpos($this->subject, '{$')!==false) error_log("Not all variables were replaced: \n" . $this->subject);

    }

    public function send() {
        $Transport = \Swift_SmtpTransport::newInstance(SiteConfig::$EMAIL_SERVER_HOST, SiteConfig::$EMAIL_SERVER_PORT);
        $Transport->setUsername(SiteConfig::$EMAIL_USERNAME);
        $Transport->setPassword(SiteConfig::$EMAIL_PASSWORD);

        $Mailer = \Swift_Mailer::newInstance($Transport);

        $Message = \Swift_Message::newInstance($this->subject);
        if($this->from)
            $Message->setFrom($this->from);
        if($this->to)
            $Message->setTo($this->to);
        if($this->bcc)
            $Message->setBcc($this->bcc);


        $HTML = <<<HTML
<html>
    <body>
        {$this->body}
    </body>
</html>
HTML;
        $Text = strip_tags(
            preg_replace('/<br[^>]*>/i', "\r\n", $this->body)
        );
        $Message->setBody($HTML, 'text/html');
        $Message->addPart($Text, 'text/plain');

        return $Mailer->send($Message);
    }

    // Static

    static function processTemplateConstants(&$body, &$subject, MerchantRow $Merchant=null) {
        $constants = array(
            'SITE_NAME' => SiteConfig::$SITE_NAME,
            'SITE_URL_LOGO' => SiteConfig::$SITE_URL_LOGO,
            'SITE_URL_MERCHANT_LOGO' => SiteConfig::$SITE_URL_LOGO,
            'SITE_DEFAULT_MERCHANT_NAME' => SiteConfig::$SITE_DEFAULT_MERCHANT_NAME,
            'SITE_DEFAULT_CUSTOMER_NAME' => SiteConfig::$SITE_DEFAULT_CUSTOMER_NAME,
        );
        if($Merchant && $Merchant->hasLogoPath())
            $constants['SITE_URL_MERCHANT_LOGO'] = $Merchant->getLogoPathURL();

        // Pre-process site constants
        foreach($constants as $name => $value) {
            $body = str_replace('{$' . $name . '}', $value, $body);
            $subject = str_replace('{$' . $name . '}', $value, $subject);
        }

    }
}

