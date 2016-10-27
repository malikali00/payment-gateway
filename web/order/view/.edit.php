<?php
/**
 * @var \Order\View\OrderView $this
 * @var PDOStatement $OrderQuery
 **/
$Order = $this->getOrder();
$odd = false;
$action_url = 'order?uid=' . $Order->getUID() . '&action=';
?>
    <!-- Page Navigation -->
    <nav class="page-menu hide-on-print">
        <a href="transaction/charge.php?" class="button">Charge <div class="submenu-icon submenu-icon-charge"></div></a>
        <a href="order?" class="button">Transactions <div class="submenu-icon submenu-icon-list"></div></a>
        <a href="transaction/charge.php?" class="button">Charge <div class="submenu-icon submenu-icon-charge"></div></a>
        <a href="<?php echo $action_url; ?>view" class="button">View <div class="submenu-icon submenu-icon-view"></div></a>
        <a href="<?php echo $action_url; ?>receipt" class="button">View Receipt  <div class="submenu-icon submenu-icon-receipt"></div></a>
        <a href="<?php echo $action_url; ?>edit" class="button current">Edit <div class="submenu-icon submenu-icon-edit"></div></a>
    </nav>

    <article class="themed">

        <section class="content">
            <!-- Bread Crumbs -->
            <aside class="bread-crumbs">
                <a href="order" class="nav_order">Transactions</a>
                <a href="<?php echo $action_url; ?>view" class="nav_order_view">#<?php echo $Order->getID(); ?></a>
                <a href="<?php echo $action_url; ?>edit" class="nav_order_edit">Edit</a>
            </aside>
            <?php if($this->hasMessage()) echo "<h5>", $this->getMessage(), "</h5>"; ?>

            <form class="form-view-Order themed" method="POST">
                <fieldset style="display: inline-block;">
                    <legend>Edit Order Fields</legend>
                    <table class="table-order-info themed">
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td>ID</td>
                            <td><?php echo $Order->getID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td>UID</td>
                            <td><?php echo $Order->getUID(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td>Update</td>
                            <td><input type="submit" value="Update" /></td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </section>
    </article>