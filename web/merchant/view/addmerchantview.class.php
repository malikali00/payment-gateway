<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/29/2016
 * Time: 1:25 PM
 */
namespace Merchant\View;

use Merchant\Model\MerchantRow;
use User\Session\SessionManager;
use View\AbstractView;

class AddMerchantView extends AbstractView
{

    public function renderHTMLBody(Array $params) {
        $SessionManager = new SessionManager();
        $SessionUser = $SessionManager->getSessionUser();
        if(!$SessionUser->hasAuthority('ADMIN', 'SUB_ADMIN')) {
            // Only admins may add other merchants
            $SessionManager->setMessage("Unable to add merchant. Permission required: ADMIN or SUB_ADMIN");
            $baseHREF = defined("BASE_HREF") ? \BASE_HREF : '';
            header("Location: {$baseHREF}merchant?action=add&message=Unable to manage integration: Admin required");
            die();
        }

        // Render Page
        include('.add.php');
    }

    public function processFormRequest(Array $post) {
        $baseHREF = defined("BASE_HREF") ? \BASE_HREF : '';
        $SessionManager = new SessionManager();
        $SessionUser = SessionManager::get()->getSessionUser();
        if(!$SessionUser->hasAuthority('ADMIN', 'SUB_ADMIN')) {
            // Only admins may add merchants
            $SessionManager->setMessage("Unable to add merchant. Permission required: ADMIN or SUB_ADMIN");
            header("Location: {$baseHREF}merchant?action=add&message=Unable to manage integration: Admin required");
            die();
        }

        try {
            $Merchant = MerchantRow::createNewMerchant($post);
//        $SessionUser->addMerchantID($Merchant->getID());

            $SessionManager->setMessage("Merchant created successfully: " . $Merchant->getUID());
            header("Location: {$baseHREF}?uid=' . $Merchant->getUID() . '&action=edit");
            die();
        } catch (\Exception $ex) {
            $SessionManager->setMessage("<div class='error'>" . $ex->getMessage() . "</div>");
            header("Location: {$baseHREF}merchant?action=add&message=" . $ex->getMessage());
            die();
        }
    }
}