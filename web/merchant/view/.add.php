<?php
use Merchant\Model\MerchantRow;

/**
 * @var \User\View\UserView $this
 * @var PDOStatement $UserQuery
 * @var \User\Model\UserRow $User
 **/
$Merchant = new MerchantRow();
$odd = false;


$Theme = $this->getTheme();
$Theme->addPathURL('merchant',      'Merchants');
$Theme->addPathURL('merchant/add.php',  'Add New Merchant');
$Theme->renderHTMLBodyHeader();
$Theme->printHTMLMenu('merchant-add');
?>


    <article class="themed">

        <section class="content">

            <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>

            <form class="form-add-merchant themed" method="POST">
                <input type="hidden" name="action" value="add" />
                <fieldset>
                    <div class="legend">New Merchant Fields</div>
                    <table class="table-merchant-info themed striped-rows" style="width: 100%;">

                        <tr>
                            <th colspan="2">Contact Info</th>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name" style="width: 20%;">Name</td>
                            <td><input type="text" name="name" value="" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Short Name</td>
                            <td><input type="text" name="short_name" value="" /></td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Main Contact</td>
                            <td><input type="text" name="main_contact" value="" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Main Email Contact</td>
                            <td><input type="email" name="main_email_id" value="" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">URL</td>
                            <td><input type="text" name="url" value="" required/></td>
                        </tr>
                        <tr>
                            <th colspan="2">Business Info</th>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Business Type</td>
                            <td>
                                <select name="business_type" required>
                                    <?php
                                    foreach(MerchantRow::$ENUM_BUSINESS_TYPE as $code=>$title)
                                        echo "<option value='", $code, "'",
                                        ($Merchant->getBusinessType() === $code ? ' selected="selected"' : ''),
                                        ">", $title, "</option>\n";
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Phone Number</td>
                            <td><input type="text" name="telephone" size="32" value="<?php echo $Merchant->getTelephone(); ?>" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Address</td>
                            <td><input type="text" name="address1" size="32" value="<?php echo $Merchant->getAddress(); ?>" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Address 2</td>
                            <td><input type="text" name="address2" size="32" value="<?php echo $Merchant->getAddress2(); ?>" /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">City</td>
                            <td><input type="text" name="city" size="32" value="<?php echo $Merchant->getCity(); ?>" required /></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">State</td>
                            <td>
                                <select name="state_id" required>
                                    <?php
                                    $default_state = $Merchant->getRegionCode() ?: 'FL';
                                    $StateQuery = \System\Model\StateRow::queryAll();
                                    foreach($StateQuery as $State)
                                        /** @var \System\Model\StateRow $State */
                                        echo "<option value='", $State->getID(), "'",
                                        ($State->getShortCode() == $default_state ? ' selected="selected"' : ''),
                                        ">", $State->getName(), "</option>\n";
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Zip</td>
                            <td><input type="text" name="zipcode" size="12" value="<?php echo $Merchant->getZipCode(); ?>" required /></td>
                        </tr>

                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Country</td>
                            <td>
                                <select name="country" required>
                                    <?php
                                    $default_country = $Merchant->getCountryCode() ?: 'USA';
                                    foreach(\System\Arrays\Locations::$COUNTRIES as $code => $name)
                                        if(strlen($code) === 3)
                                            echo "<option value='", $code, "'",
                                            ($code === $default_country ? ' selected="selected"' : ''),
                                            ">", $name, "</option>\n";
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td class="name">Create Merchant</td>
                            <td><input type="submit" value="Create Merchant" class="themed" /></td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </section>
    </article>

<?php         $this->getTheme()->renderHTMLBodyFooter(); ?>