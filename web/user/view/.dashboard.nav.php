<?php
use User\Session\SessionManager;
/** @var \User\View\LoginView $this  **/

$SessionManager = new SessionManager();
$SessionUser = $SessionManager->getSessionUser();
$button_current = @$button_current ?: 'dashboard';
$ca = array();
$ca[@$button_current] = ' current';
?>

    <!-- Page Navigation -->
    <nav class="page-menu hide-on-print">
        <a href="/" class="button<?php echo @$ca['dashboard']; ?> hide-on-layout-horizontal">Dashboard <div class="submenu-icon submenu-icon-dashboard"></div></a>
        <a href="user/account.php#content" class="button<?php echo @$ca['account']; ?>">My Account <div class="submenu-icon submenu-icon-account"></div></a>

        <?php if($SessionUser->hasAuthority('ROLE_ADMIN', 'ROLE_SUB_ADMIN', 'ROLE_RUN_REPORTS')) { ?>
            <a href="order#content" class="button<?php echo @$ca['order']; ?>">Transactions <div class="submenu-icon submenu-icon-transaction"></div></a>
        <?php } ?>
        <?php if($SessionUser->hasAuthority('ROLE_ADMIN', 'ROLE_SUB_ADMIN', 'ROLE_POST_CHARGE')) { ?>
            <a href="transaction/charge.php#content" class="button<?php echo @$ca['charge']; ?>">Charge<div class="submenu-icon submenu-icon-charge"></div></a>
        <?php } ?>
        <?php if($SessionUser->hasAuthority('ROLE_ADMIN', 'ROLE_SUB_ADMIN', 'ROLE_SUB_ADMIN')) { ?>
            <a href="merchant#content" class="button<?php echo @$ca['merchant']; ?>">Merchants <div class="submenu-icon submenu-icon-merchant"></div></a>
            <a href="user#content" class="button<?php echo @$ca['user']; ?>">Users <div class="submenu-icon submenu-icon-user"></div></a>
        <?php } ?>

        <?php if($SessionUser->hasAuthority('ROLE_ADMIN')) { ?>
            <a href="integration#content" class="button<?php echo @$ca['integration']; ?>">Integration <div class="submenu-icon submenu-icon-integration"></div></a>
        <?php } ?>
        <a href="user/logout.php#content" class="button">Log out<div class="submenu-icon submenu-icon-logout"></div></a>
    </nav>

