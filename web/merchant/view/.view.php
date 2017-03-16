<?php
use Integration\Model\IntegrationRow;
use Merchant\Model\MerchantRow;
use Order\Model\OrderRow;
use System\Config\SiteConfig;

/**
 * @var \Merchant\View\MerchantView $this
 * @var PDOStatement $MerchantQuery
 **/
$Merchant = $this->getMerchant();
$odd = false;
$action_url = 'merchant?uid=' . $Merchant->getUID() . '&action=';
$SessionManager = new \User\Session\SessionManager();
$SessionUser = $SessionManager->getSessionUser();

// Get Timezone diff
$offset = $SessionUser->getTimeZoneOffset('now');

$Theme = $this->getTheme();
$Theme->addPathURL('merchant',      SiteConfig::$SITE_DEFAULT_MERCHANT_NAME . 's');
$Theme->addPathURL($action_url,     $Merchant->getShortName());
$Theme->renderHTMLBodyHeader();
$Theme->printHTMLMenu('merchant-view', $action_url);

?>
    <article class="themed">

        <section class="content" >
            <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>

            <form class="form-view-merchant themed " method="POST">
                <input type="hidden" name="uid" value="<?php echo $Merchant->getUID(); ?>" />
                <fieldset style="position: relative;">
                    <div class="legend">
                        <a href="merchant?action=edit&id=<?php echo $Merchant->getID(); ?>" style="text-decoration: none;">
                            <div class="app-button app-button-edit" style="display: inline-block;"></div>
                        </a>
                        <?php echo SiteConfig::$SITE_DEFAULT_MERCHANT_NAME; ?>: <?php echo $Merchant->getName(); ?>
                    </div>


                    <div class="page-buttons order-page-buttons hide-on-print">
                        <a href="<?php echo $action_url; ?>view" class="page-button page-button-view disabled">
                            <div class="app-button large app-button-view" ></div>
                            View
                        </a>
                        <a href="<?php echo $action_url; ?>edit" class="page-button page-button-edit">
                            <div class="app-button large app-button-edit" ></div>
                            Edit
                        </a>
                        <a href="<?php echo $action_url; ?>email-templates" class="page-button page-button-edit">
                            <div class="app-button large app-button-edit" ></div>
                            Emails
                        </a>
                        <a href="<?php echo $action_url; ?>delete" class="page-button page-button-delete disabled">
                            <div class="app-button large app-button-delete" ></div>
                            Delete
                        </a>
                    </div>
                    <hr/>


                    <?php $odd = true; ?>
                    <table class="table-merchant-info themed small striped-rows stretch-box" style="width: 50%;">
                        <?php if($Merchant->hasLogoPath()) { ?>
                        <tr>
                            <th colspan="2" class="section-break">Logo</th>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td colspan="2" style="text-align: center;"><img src="<?php echo $Merchant->getLogoPathURL(); ?>" alt="Custom <?php echo SiteConfig::$SITE_DEFAULT_MERCHANT_NAME; ?> Logo" </td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <th colspan="2" class="section-break">Information</th>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">ID</td>
                            <td><?php echo $Merchant->getID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Short Name</td>
                            <td><?php echo $Merchant->getShortName(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">UID</td>
                            <td><?php echo $Merchant->getUID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Email</td>
                            <td><a href='mailto:<?php echo $Merchant->getMainEmailID(); ?>'><?php echo $Merchant->getMainEmailID(); ?></a></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">URL</td>
                            <td><a target="_blank" href='<?php echo $Merchant->getURL(); ?>'><?php echo $Merchant->getURL(); ?></a></td>
                        </tr>




                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Description</td>
                            <td><?php echo $Merchant->getDescription(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Branch</td>
                            <td><?php echo $Merchant->getBranch(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Item Label</td>
                            <td><?php echo $Merchant->getLabelItem(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Item Contact</td>
                            <td><?php echo $Merchant->getLabelContact(); ?></td>
                        </tr>


<!--                        <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
<!--                            <td class="name">Merchant ID</td>-->
<!--                            <td>--><?php //echo $Merchant->getMerchantID(); ?><!--</td>-->
<!--                        </tr>-->

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name"><?php echo SiteConfig::$SITE_DEFAULT_MERCHANT_NAME; ?> MCC</td>
                            <td style="max-width: 200px;"><?php echo $Merchant->getMerchantMCC(), ' - ', \System\Arrays\Merchants::getDescription($Merchant->getMerchantMCC(), false); ?></td>
                        </tr>

                        <tr>
                            <th colspan="2" class="section-break">Business</th>
                        </tr>
                        <?php $odd = true; ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Status</td>
                            <td><?php echo $Merchant->getStatusName(); ?></td>
                        </tr>
<!--                        <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
<!--                            <td class="name">Title</td>-->
<!--                            <td>--><?php //echo $Merchant->getTitle(); ?><!--</td>-->
<!--                        </tr>-->
<!--                        <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
<!--                            <td class="name">DOB</td>-->
<!--                            <td>--><?php //echo $Merchant->getDOB(); ?><!--</td>-->
<!--                        </tr>-->
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Tax ID</td>
                            <td><?php echo $Merchant->getTaxID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Business Tax ID</td>
                            <td><?php echo $Merchant->getBusinessTaxID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Business Type</td>
                            <td><?php echo MerchantRow::$ENUM_BUSINESS_TYPE[$Merchant->getBusinessType()]; ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Telephone Number</td>
                            <td><?php echo $Merchant->getTelephone(); ?></td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Store ID</td>
                            <td><?php echo $Merchant->getStoreID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Sale Rep</td>
                            <td><?php echo $Merchant->getSaleRep(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Main Contact</td>
                            <td><?php echo $Merchant->getMainContact(); ?></td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Address</td>
                            <td><?php echo $Merchant->getAddress(), '<br/>', $Merchant->getAddress2(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Location</td>
                            <td><?php echo $Merchant->getCity(), ' ' ,
                                @\System\Arrays\Locations::$STATES[$Merchant->getRegionCode()],
                                ', ', $Merchant->getZipCode(),
                                '<br/>', @\System\Arrays\Locations::$COUNTRIES[$Merchant->getCountryCode()]; ?>
                            </td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Agent Chain</td>
                            <td><?php echo $Merchant->getAgentChain(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Discover Ext</td>
                            <td><?php echo $Merchant->getDiscoverExt(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Amex Ext</td>
                            <td><?php echo $Merchant->getAmexExt(); ?></td>
                        </tr>

                    </table>

                    <table class="table-merchant-info themed small striped-rows stretch-box" style="width: 50%;">

                        <tr>
                            <th colspan="2" class="section-break">Fees</th>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Fee: Limit</td>
                            <td>$<?php echo number_format($Merchant->getConvenienceFeeLimit(), 2); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Fee: Flat</td>
                            <td>$<?php echo number_format($Merchant->getConvenienceFeeFlat(), 2); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Fee: Variable</td>
                            <td>$<?php echo number_format($Merchant->getConvenienceFeeVariable(), 2); ?></td>
                        </tr>

                        <tr>
                            <th colspan="2" class="section-break">Settings</th>
                        </tr>
                        <?php
                        foreach(MerchantRow::$FLAG_DESCRIPTIONS as $type => $description) {
                            ?>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name"><?php echo $description; ?></td>
                                <td>
                                    <?php echo $Merchant->hasFlag($type) ? '<strong>Yes</strong>' : 'No'; ?>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <th colspan="2" class="section-break">Fraud Limits</th>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Transaction High Limit (USD)</td>
                            <td><?php echo $Merchant->getFraudHighLimit() ?: ''; ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Transaction Low Limit (USD)</td>
                            <td><?php echo $Merchant->getFraudLowLimit() ?: ''; ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Transaction High Monthly Limit (USD)</td>
                            <td><?php echo $Merchant->getFraudHighMonthlyLimit() ?: ''; ?></td>
                        </tr>


<!--                        <tr>-->
<!--                            <th colspan="2" class="section-break">Batching</th>-->
<!--                        </tr>-->
<!--                        <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
<!--                            <td class="name">Batch Close</td>-->
<!--                            <td>--><?php //echo $Merchant->getBatchTime(), ' ', $Merchant->getBatchTimeZone(); ?><!--</td>-->
<!--                        </tr>-->
<!--                        <tr class="row---><?php //echo ($odd=!$odd)?'odd':'even';?><!--">-->
<!--                            <td class="name">Open Date</td>-->
<!--                            <td>--><?php //echo $Merchant->getOpenDate(); ?><!--</td>-->
<!--                        </tr>-->

                        <tr>
                            <th colspan="2" class="section-break">Notes</th>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td colspan="2">
                                <pre><?php echo $Merchant->getNotes() ?: "No Notes"; ?></pre>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset style="position: relative;">
                    <div class="legend">
                        <a href="merchant?action=edit&id=<?php echo $Merchant->getID(); ?>" style="text-decoration: none;">
                            <div class="app-button app-button-edit" style="display: inline-block;"></div>
                        </a>
                        Provisions: <?php echo $Merchant->getName(); ?>
                    </div>
                    <table class="table-merchant-info themed striped-rows" style="width: 100%;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>MID</th>
                            <th>Complete</th>
                            <th>Provisioned</th>
                            <th>Settle</th>
                            <th>Notes</th>
                        </tr>
                        <?php

                        $DB = \System\Config\DBConfig::getInstance();
                        $UserQuery = $DB->prepare(IntegrationRow::SQL_SELECT . IntegrationRow::SQL_WHERE . IntegrationRow::SQL_ORDER_BY);
                        /** @noinspection PhpMethodParametersCountMismatchInspection */
                        $UserQuery->setFetchMode(\PDO::FETCH_CLASS, IntegrationRow::_CLASS);
                        $UserQuery->execute(array($this->getMerchant()->getID()));

                        $odd = false;
                        /** @var IntegrationRow $UserRow **/
                        foreach($UserQuery as $UserRow) {
                            $id = $UserRow->getID();
                            $MerchantIdentity = $UserRow->getMerchantIdentity($Merchant);
                            if(!$MerchantIdentity->isProvisioned())
                                continue;
                            ?>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td><a href="integration?id=<?php echo $UserRow->getID(); ?>"><?php echo $UserRow->getID(); ?></a></td>
                                <td><a href="integration?id=<?php echo $UserRow->getID(); ?>"><?php echo $UserRow->getName(); ?></a></td>
                                <td><?php echo $UserRow->getAPIType(); ?></td>
                                <td><?php echo $MerchantIdentity->getRemoteID() ? '<strong>'.$MerchantIdentity->getRemoteID().'</strong>' : 'N/A'; ?></td>
                                <td><?php echo "<span style='color:", ($MerchantIdentity->isProfileComplete() ? "green'>Yes"  : "red'>No"), "</span>"; ?></td>
                                <td><?php echo "<span style='color:", ($MerchantIdentity->isProvisioned() ? "green'>Yes"  : "red'>No"), "</span>"; ?></td>
                                <td><?php echo "<span style='color:", ($MerchantIdentity->canSettleFunds() ? "green'>Yes"  : "red'>No"), "</span>"; ?></td>
                                <td style="max-width: 24em; overflow-x: hidden;"><?php echo $UserRow->getNotes(); ?></td>
                            </tr>

                        <?php } ?>

                    </table>
                </fieldset>

                <fieldset style="position: relative;">
                    <div class="legend">
                        <a href="merchant?action=edit&id=<?php echo $Merchant->getID(); ?>" style="text-decoration: none;">
                            <div class="app-button app-button-edit" style="display: inline-block;"></div>
                        </a>
                        Users: <?php echo $Merchant->getName(); ?>
                    </div>
                    <table class="table-merchant-users themed striped-rows" style="width: 100%;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <?php if($SessionUser->hasAuthority('ADMIN', 'SUB_ADMIN')) { ?>
                            <th>Admin Login</th>
                            <?php } ?>
                        </tr>
                        <?php

                        $DB = \System\Config\DBConfig::getInstance();
                        $UserQuery = $DB->prepare(
                            "SELECT * FROM user u "
                            . "\nWHERE u.merchant_id=?");
                        /** @noinspection PhpMethodParametersCountMismatchInspection */
                        $UserQuery->setFetchMode(\PDO::FETCH_CLASS, \User\Model\UserRow::_CLASS);
                        $UserQuery->execute(array($this->getMerchant()->getID()));

                        $odd = false;
                        /** @var \User\Model\UserRow $UserRow **/
                        foreach($UserQuery as $UserRow) {
                            ?>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td><a href="user?uid=<?php echo $UserRow->getUID(); ?>"><?php echo $UserRow->getID(); ?></a></td>
                                <td><a href="user?uid=<?php echo $UserRow->getUID(); ?>"><?php echo $UserRow->getUsername(); ?></a></td>
                                <?php if($SessionUser->hasAuthority('ADMIN', 'SUB_ADMIN')) { ?>
                                <td class="value">
                                    <?php if($SessionUser->getID() !== $UserRow->getID()) { ?>
                                    <button type="submit" class="themed" value="<?php echo $UserRow->getUID(); ?>" name="login_user_uid">Login</button>
                                    <?php } ?>
                                </td>
                                <?php } ?>
                            </tr>

                        <?php } ?>

                    </table>
                </fieldset>



            </form>
        </section>
    </article>

<?php $this->getTheme()->renderHTMLBodyFooter(); ?>