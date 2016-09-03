<?php
/**
 * @var \Batch\View\BatchView $this
 * @var PDOStatement $OrderQuery
 **/
$Batch = $this->getBatch();
$odd = false;
$action_url = 'batch?id=' . $Batch->getID() . '&action=';
?>
    <section class="message">
        <h1>View Batch</h1>

        <?php if($this->hasException()) { ?>
            <h5><?php echo $this->hasException(); ?></h5>

        <?php } else if ($this->hasSessionMessage()) { ?>
            <h5><?php echo $this->popSessionMessage(); ?></h5>

        <?php } else { ?>
            <h5>View a batch entry...</h5>

        <?php } ?>
    </section>

    <section class="content">
        <form class="form-view-batch themed" onsubmit="return false;">
            <fieldset class="action-fields">
                <legend>Actions</legend>
                <a href="batch?" class="button">Batch List</a>
                <a href="<?php echo $action_url; ?>edit" class="button">Edit</a>
            </fieldset>
            <fieldset>
                <legend>Batch Information</legend>
                <table class="table-batch-info themed">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>ID</td>
                        <td><?php echo $Batch->getID(); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>UID</td>
                        <td><?php echo $Batch->getUID(); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Status</td>
                        <td><?php echo $Batch->getBatchStatus() ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Date</td>
                        <td><?php echo date("M jS Y G:i:s", strtotime($Batch->getDate())); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Merchant</td>
                        <td><a href='merchant?id=<?php echo $Batch->getMerchantID(); ?>'><?php echo $Batch->getMerchantShortName(); ?></a></td>
                    </tr>

                </table>
            </fieldset>
            <fieldset>
                <legend>Orders</legend>
                <table class="table-results themed">
                    <tr>
                        <th>ID</th>
                        <th>Card Holder</th>
                        <th>Date</th>
                        <th>Invoice ID</th>
                        <th>User Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Merchant</th>
                    </tr>
                    <?php
                    /** @var \Order\Model\OrderRow $Order */
                    $odd = false;
                    foreach($OrderQuery as $Order) { ?>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td><a href='order?id=<?php echo $Order->getID(); ?>'><?php echo $Order->getID(); ?></a></td>
                            <td><?php echo $Order->getHolderFullFullName(); ?></td>
                            <td><?php echo date("M jS Y G:i:s", strtotime($Order->getDate())); ?></td>
                            <td><?php echo $Order->getInvoiceNumber(); ?></td>
                            <td><?php echo $Order->getUsername(); ?></td>
                            <td><?php echo $Order->getAmount(); ?></td>
                            <td><?php echo $Order->getStatus(); ?></td>
                            <td><a href='merchant?id=<?php echo $Order->getMerchantID(); ?>'><?php echo $Order->getMerchantShortName(); ?></a></td>

                        </tr>
                    <?php } ?>
                </table>
            </fieldset>

        </form>
    </section>