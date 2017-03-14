<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/27/2016
 * Time: 10:47 PM
 */

namespace Integration\Element\Test;

use Integration\Model\IntegrationRow;
use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use Order\Model\TransactionRow;
use User\Model\SystemUser;

echo "\nBatch ... ", __FILE__, PHP_EOL;


// Go to root directory
$cwd1 = getcwd();
chdir('../../..');

// Enable class autoloader
spl_autoload_extensions('.class.php');
spl_autoload_register();

try {
    echo "\nStarting Element Batch...";

    $ElementAPI = IntegrationRow::fetchByUID('73caa82c-c423-428b-927b-15a796bbc0c7'); // Element.io Staging

    $SessionUser = new SystemUser();

    $MerchantQuery = MerchantRow::queryAll();
    foreach ($MerchantQuery as $Merchant) {

        try {
            /** @var MerchantRow $Merchant */
            $MerchantIdentity = $ElementAPI->getMerchantIdentity($Merchant);
            if (!$MerchantIdentity->isProvisioned())
                continue;

            echo "\n\nMerchant: ", $Merchant->getName(), " MID=", $MerchantIdentity->getRemoteID();

            $stats = $MerchantIdentity->performTransactionQuery($SessionUser,
                array(
                    'status' => 'Settled',
                    'reverse' => 'True',
//                'date_start' => date('Y-m-d H:i:s.v', time() - 24*60*60*7),
//                'date_end' => date('Y-m-d H:i:s.v', time()),
                ),
                function (OrderRow $OrderRow, TransactionRow $TransactionRow, $item) {
                    echo "\n\tOrder #" . $OrderRow->getID(), ' ', $TransactionRow->getIntegrationRemoteID(), ' ', $OrderRow->getStatus(), ' => ', $item['TransactionStatus'];
                    return true;
                }
            );

            echo "\nTotal Returned: ", $stats['total'];
            echo "\nFound Locally: ", $stats['found'];
            if ($stats['not_found'] > 0)
                echo "\n!! Not Found Locally: ", $stats['not_found'], '!!';
            if ($stats['updated'] > 0)
                echo "\n!! Transactions Updated: ", $stats['updated'], '!!';

        } catch (\Exception $ex) {
            echo $ex;
        }
    }
    echo "\nElement Batching finished";

} catch (\Exception $ex) {

    echo $ex->getMessage();
}
//// Don't run long tests on anything but dev
//if(@$_SERVER['COMPUTERNAME'] !== 'KADO')
//    $tests = array();

chdir($cwd1);
