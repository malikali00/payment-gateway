<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/28/2016
 * Time: 1:32 PM
 */
namespace Order\PDF;

define('FPDF_DIR', dirname(dirname(dirname(__DIR__))) . '/support/FPDF/');
require_once FPDF_DIR . '/fpdf.php';


use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use Order\Model\TransactionRow;
use Order\View\OrderView;
use System\Config\DBConfig;
use System\Config\SiteConfig;
use User\Session\SessionManager;
use View\Theme\Blank\BlankViewTheme;


class ReceiptPDF extends \FPDF
{
    private $order, $merchant;
    public function __construct(OrderRow $Order, MerchantRow $Merchant) {
        parent::__construct();

        $this->order = $Order;
        $this->merchant = $Merchant;

        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();

        $OrderView = new OrderView($Order->getID(), 'download');
        $OrderView->setTheme(new BlankViewTheme());


        $this->AliasNbPages();
        $this->AddPage();

        switch(strtolower($Order->getEntryMode())) {
            case 'keyed':
            case 'swipe':
                $this->addCreditCardInfo();
                break;

            case 'check':
                $this->addCheckInfo();
        }

        // Line break
        $this->Ln(4);

        $this->SetFont('Courier','B',16);
        $this->Cell(0,10,SiteConfig::$SITE_DEFAULT_MERCHANT_NAME . ': ' . $Merchant->getName(),0,1);

        $this->SetFont('Courier','',12);

        $this->Cell(0,6,'Address:      ' . $Merchant->getAddress() . $Merchant->getAddress2(),0,1);
        $this->Cell(0,6,'City:         ' . $Merchant->getCity(),0,1);
        $this->Cell(0,6,'State:        ' . $Merchant->getState(),0,1);
        $this->Cell(0,6,'Zip:          ' . $Merchant->getZipCode(),0,1);
        $this->Cell(0,6,'Phone:        ' . $Merchant->getTelephone(),0,1);


        $this->Cell(0,6,'Date:         ' . $Order->getDate($SessionUser->getTimeZone())->format("F jS Y"),0,1);
        $this->Cell(0,6,'Time:         ' . $Order->getDate($SessionUser->getTimeZone())->format("g:i:s A"),0,1);


        // Line break
        $this->Ln(4);

        $this->SetFont('Courier','B',16);
        $this->Cell(0,10,"Total:    $" . $Order->getAmount(),0,1);

        $this->SetFont('Courier', '', 12);
        if($Order->getConvenienceFee()) {
            $this->Cell(0, 6, 'Conv. Fee:    ' . $Order->getConvenienceFee(), 0, 1);
            $this->Cell(0, 6, 'Subtotal:     ' . number_format($Order->getAmount()+$Order->getConvenienceFee(), 2), 0, 1);
        }

        if($Order->getTotalReturnedAmount()>0) {
            $this->Cell(0, 6, 'Returned:     ' . number_format($Order->getTotalReturnedAmount(), 2), 0, 1);
        }

        // Line break
        $this->Ln(6);

        /** @var \Order\Model\TransactionRow $Transaction */
        $DB = DBConfig::getInstance();
        $TransactionQuery = $DB->prepare(TransactionRow::SQL_SELECT . "WHERE t.order_item_id = ? LIMIT 100");
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $TransactionQuery->setFetchMode(\PDO::FETCH_CLASS, TransactionRow::_CLASS);
        $TransactionQuery->execute(array($Order->getID()));


        $this->SetFont('Courier', 'B', 10);
        $this->Cell(0, 6,
            sprintf("%-' 24s %-' 24s %-' 24s %-' 24s ", "TID", "Date", "Amount", "Action")
            , 1, 1);


        $this->SetFont('Courier', '', 10);
        foreach($TransactionQuery as $Transaction) {
            $this->Cell(0, 6,
                sprintf("%-' 24s %-' 24s %-' 24s %-' 24s ",
                    $Transaction->getIntegrationRemoteID(),
                    $Transaction->getTransactionDate($SessionUser->getTimeZone())->format("M j g:i A"),
                    $Transaction->getAmount(),
                    $Transaction->getAction())
                , 0, 1);
        }


    }

    private function addCreditCardInfo() {
        $Order = $this->order;
        $this->SetFont('Courier','B',16);
        $this->Cell(0,10,SiteConfig::$SITE_DEFAULT_CUSTOMER_NAME . ': ' . $Order->getCustomerFullName(),0,1);

        $this->SetFont('Courier','',12);
        $this->Cell(0,6,'Credit Card   ' . $Order->getCardNumber(),0,1);
        $this->Cell(0,6,'Card Type     ' . $Order->getCardType(),0,1);
        $this->Cell(0,6,'Card Exp      ' . $Order->getCardExpMonth(). '/'. $Order->getCardExpYear(),0,1);
        if($Order->getInvoiceNumber())
            $this->Cell(0,6,'Invoice       ' . $Order->getInvoiceNumber(),0,1);


    }

    private function addCheckInfo() {

    }


    public function render($dest='', $name='', $isUTF8=false) {
        $this->Output($dest, $name, $isUTF8);
    }


    // Page header
    function Header()
    {
        $Merchant = $this->merchant;
        $Order = $this->order;
        $webDir = dirname(dirname(__DIR__));
        $pu = parse_url(@$_SERVER['REQUEST_URI']);
        $url = (@$pu["host"]?:SiteConfig::$SITE_URL?:'localhost') . '/order/receipt.php?uid='.$Order->getUID();

        if($Merchant->hasLogoPath()) {
            // Logo
            $this->Image($webDir . '/' . $Merchant->getLogoImageURL(),10,6,60, NULL, NULL, $url);
        }

        $this->Line(10, 32, 200, 32);

        // Move to the right
//        $this->Cell(130);

        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();
        $date = $Order->getDate($SessionUser->getTimeZone())->format("M dS Y");
        $time = $Order->getDate($SessionUser->getTimeZone())->format("g:i A e");

        $TEXT = <<<TEXT
{$Merchant->getShortName()}
{$date}
{$time}
TEXT;


        // Title
        $this->SetFont('Courier','',12);
//        $this->Cell(190,0,  $Merchant->getShortName(),0,1,'R');
        $this->MultiCell(190,5, $TEXT,0,'R');
        $this->SetTextColor(0, 0, 255);
        $this->Cell(190,5, $Order->getReferenceNumber(),0,1,'R', false, $url);

        // Line break
        $this->Ln(6);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
//        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}


