<?php
use Merchant\Model\MerchantRow;
use User\Model\AuthorityRow;
use User\Model\UserAuthorityRow;
use User\Model\UserRow;
use User\Session\SessionManager;
use User\Model\Authority;

/**
 * @var \User\View\UserView $this
 * @var PDOStatement $UserQuery
 **/

$SessionManager = new SessionManager();
$SessionUser = $SessionManager->getSessionUser();
$User = $this->getUser();

$odd = false;
$action_url = 'user/index.php?uid=' . $User->getUID() . '&action=';
$category = $User->getID() == $SessionUser->getID() ? 'user-account-edit' : 'user-edit';

$Theme = $this->getTheme();
$Theme->addPathURL('user',          'Users');
$Theme->addPathURL($action_url,     $User->getUsername());
$Theme->addPathURL($action_url.'edit',     'Edit');
$Theme->renderHTMLBodyHeader();
$Theme->printHTMLMenu($category,    $action_url);
?>
        <article class="themed">

            <section class="content">

                <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>

                <form class="form-view-user themed" method="POST" action="<?php echo $action_url; ?>edit">
                    <input type="hidden" name="id" class="themed" value="<?php echo $User->getID(); ?>" />
                    <input type="hidden" name="action" class="themed" value="edit" />
                    <fieldset>
                        <div class="legend">Edit User Fields</div>


                        <div class="page-buttons order-page-buttons hide-on-print">
                            <a href="<?php echo $action_url; ?>view" class="page-button page-button-view">
                                <div class="app-button large app-button-view" ></div>
                                View
                            </a>
                            <a href="<?php echo $action_url; ?>edit" class="page-button page-button-edit disabled">
                                <div class="app-button large app-button-edit" ></div>
                                Edit
                            </a>
                            <?php if($SessionUser->hasAuthority('ADMIN', 'SUB_ADMIN')) { ?>
                                <a href="<?php echo $action_url; ?>delete" class="page-button page-button-delete">
                                    <div class="app-button large app-button-delete" ></div>
                                    Delete
                                </a>
                            <?php } ?>
                        </div>

                        <hr/>


                        <table class="table-user-info themed striped-rows" style="width: 100%;">
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">ID</td>
                                <td class="value"><?php echo $User->getID(); ?></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">UID</td>
                                <td class="value"><?php echo $User->getUID(); ?></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Username</td>
                                <td><input type="text" class="themed" disabled="disabled" name="username" value="<?php echo @$_POST['username'] ?: $User->getUsername(); ?>" autofocus  /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Email</td>
                                <td><input type="text" class="themed" name="email" value="<?php echo @$_POST['email'] ?: $User->getEmail(); ?>" /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">First Name</td>
                                <td><input type="text" class="themed" name="fname" value="<?php echo @$_POST['fname'] ?: $User->getFirstName(); ?>" /></td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Last Name</td>
                                <td><input type="text" class="themed" name="lname" value="<?php echo @$_POST['lname'] ?: $User->getLastName(); ?>" /></td>
                            </tr>


                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">User Timezone</td>
                                <td>
                                    <select name="timezone" required class="themed" style="max-width: 14em;">
                                        <?php
                                        $curtimezone = date_default_timezone_get();
                                        foreach(\System\Arrays\TimeZones::$TimeZones as $timezone => $name) {
                                            try {
                                                $time = new \DateTime(NULL, new \DateTimeZone($timezone));
                                                $name .= " (" . $time->format('g:i A') . ")";
                                                $selected = $timezone === $User->getTimeZone() ? ' selected="selected"' : '';
                                                echo "\n\t\t\t<option value='{$timezone}'{$selected}>{$name}</option>";
                                            } catch (Exception $ex) {
                                                // Only show available timezones. Where did greenland go anyway
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <?php if($SessionUser->hasAuthority("ADMIN")) { ?>

                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Admin</td>
                                <td>
                                    <select name="admin_id" class="themed" required>
                                        <?php
                                        $SQL = UserRow::SQL_SELECT
                                            . "\n\tWHERE FIND_IN_SET('admin', u.authority) OR FIND_IN_SET('sub_admin', u.authority) "
                                            . "\n\tORDER BY u.authority ASC";
                                        $DB = \System\Config\DBConfig::getInstance();
                                        $stmt = $DB->prepare($SQL);
                                        /** @noinspection PhpMethodParametersCountMismatchInspection */
                                        $stmt->setFetchMode(\PDO::FETCH_CLASS, UserRow::_CLASS);
                                        $stmt->execute();
                                        foreach($stmt as $AdminUser) {
                                            /** @var UserRow $AdminUser */
                                            $selected = $AdminUser->getID() === $User->getAdminID() ? ' selected="selected"' : '';
                                            echo "\n\t\t\t<option value='{$AdminUser->getID()}'{$selected}>{$AdminUser->getFullName()}</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Merchant</td>
                                <td class="value">
                                    <select name="merchant_id" class="themed">
                                    <?php
                                    $MerchantQuery = MerchantRow::queryAll();
                                    foreach($MerchantQuery as $Merchant)
                                        /** @var \Merchant\Model\MerchantRow $Merchant */
                                        echo "<option value='", $Merchant->getID(), "'",
                                        ($Merchant->getID() === $User->getMerchantID() ? 'selected="selected"' : ''),
                                        ">", $Merchant->getName(), "</option>";
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } ?>


                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Change Password</td>
                                <td>
                                    <input type="password" name="password" value="" autocomplete="off" class="themed" style="max-width: 7em;" />
                                    <button type="button" onclick="this.form.password.value = this.form.password_confirm.value = randomPassword(12); this.form.send_email_welcome.checked = true; " class="themed">Generate</button>
                                </td>
                            </tr>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Confirm Password</td>
                                <td><input type="password" name="password_confirm" value="" autocomplete="off" class="themed" style="max-width: 7em;" /></td>
                            </tr>

                            <?php if($SessionUser->hasAuthority("ADMIN", "SUB_ADMIN")) { ?>

                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Authorities</td>
                                <td class="value">
                                    <?php
                                    foreach(Authority::$AUTHORITIES as $authority => $name) {
                                        $authority = strtoupper($authority);
                                        if(in_array($authority, array('ADMIN', 'SUB_ADMIN'))
                                            && !$SessionUser->hasAuthority("ADMIN"))
                                            continue;
                                        /** @var UserAuthorityRow $Authority */
                                        echo "<label>",
                                        "\n\t<input type='hidden' name='authority[", $authority, "]' value='0' />",
                                        "\n\t<input type='checkbox' name='authority[", $authority, "]' value='1'",
                                        ($User->hasAuthority($authority) ? ' checked="checked"' : ''),
                                        "/>", $name, "</label><br/>\n";
                                    }
                                    ?>
                                </td>
                            </tr>

                                <?php if($SessionUser->getID() != $User->getID()) { ?>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name"><?php echo $SessionUser->getUsername(); ?> Password</td>
                                    <td><input type="password" name="admin_password" value="" required autocomplete="on" class="themed" /></td>
                                </tr>
                                <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                    <td class="name">Send email</td>
                                    <td>
                                        <input type="checkbox" name="send_email_welcome" value="1" style="transform: scale(1.5);" class="themed" />
                                        <span style="font-size: smaller"><?php echo $User->getEmail(); ?></span>
                                    </td>
                                </tr>
                                <?php } ?>


                            <?php } ?>
                            <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                                <td class="name">Update</td>
                                <td><input type="submit" value="Update" class="themed"/></td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </section>
        </article>
        <script>
            function randomPassword(length) {
                var chars = "abcdefghijklmnopqrstuvwxyz!@#$%&ABCDEFGHIJKLMNOP1234567890";
                var pass = "";
                for (var x = 0; x < length; x++) {
                    var i = Math.floor(Math.random() * chars.length);
                    pass += chars.charAt(i);
                }
                return pass;
            }

        </script>


<?php $Theme->renderHTMLBodyFooter(); ?>