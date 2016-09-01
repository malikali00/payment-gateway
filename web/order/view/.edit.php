<?php
/**
 * @var \Order\View\OrderView $this
 * @var PDOStatement $OrderQuery
 **/
$Order = $this->getOrder();
$odd = false;
$action_url = 'order?id=' . $Order->getID() . '&action=';
?>
    <section class="message">
        <h1>Edit <?php echo $Order->getID(); ?></h1>

        <?php if($this->hasException()) { ?>
            <h5><?php echo $this->hasException(); ?></h5>

        <?php } else if ($this->hasSessionMessage()) { ?>
            <h5><?php echo $this->popSessionMessage(); ?></h5>

        <?php } else { ?>
            <h5>Edit this Order Account...</h5>

        <?php } ?>
    </section>

    <section class="content">
        <form class="form-view-Order themed" method="POST">
            <fieldset class="action-fields">
                <legend>Actions</legend>
                <a href="order?" class="button">Order List</a>
                <a href="<?php echo $action_url; ?>view" class="button">View</a>
                <a href="<?php echo $action_url; ?>delete" class="button">Delete</a>
                <a href="<?php echo $action_url; ?>change" class="button">Change Password</a>

            </fieldset>
            <fieldset>
                <legend>Edit Order Fields</legend>
                <table class="table-order-info themed">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
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