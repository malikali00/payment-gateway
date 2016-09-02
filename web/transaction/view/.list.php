<?php /**
 * @var \User\View\LoginView $this
 * @var PDOStatement $Query
 * @var \Transaction\Model\TransactionQueryStats $Stats
 **/?>
    <section class="message">
        <h1>Transaction List</h1>

        <?php if($this->hasException()) { ?>
            <h5><?php echo $this->hasException(); ?></h5>

        <?php } else if ($this->hasSessionMessage()) { ?>
            <h5><?php echo $this->popSessionMessage(); ?></h5>

        <?php } else if($Stats) { ?>
            <h5><?php echo $Stats->getMessage(); ?></h5>

        <?php } else { ?>
            <h5>Search for Transaction Accounts...</h5>

        <?php } ?>
    </section>

    <section class="content">
        <form class="form-search themed">
            <fieldset class="action-fields">
                <legend>Actions</legend>
                <a href="transaction?" class="button">Transaction List</a>
                <a href="transaction/charge.php?" class="button">Charge</a>
            </fieldset>
            <fieldset class="search-fields">
                <legend>Search</legend>
                <table>
                    <tbody>
                        <tr>
                            <th>From</th>
                            <td>
                                <input type="datetime-local" name="date_from" value="<?php echo @$_GET['date_from'] ?: date('Y-m-d\TH:i:s', time()-30*24*60*60);?>" /> to
                                <input type="datetime-local" name="date_to"   value="<?php echo @$_GET['date_to']   ?: date('Y-m-d\TH:i:s');?>"  />
                            </td>
                        </tr>
                        <tr>
                            <th>Value</th>
                            <td>
                                <input type="text" name="search" value="<?php echo @$_GET['search']; ?>" placeholder="TID, MID, Amount, Card Number, Batch ID" size="51" />
                                <select name="limit">
                                    <?php
                                    $limit = @$_GET['limit'] ?: 50;
                                    foreach(array(10,25,50,100,250) as $opt)
                                        echo "<option", $limit == $opt ? ' selected="selected"' : '' ,">", $opt, "</option>\n";
                                    ?>
                                </select>
                                <input type="submit" value="Search" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset class="paginate">
                <legend>Pagination</legend>
                <?php $Stats->printPagination('transaction?'); ?>
            </fieldset>
            <fieldset>
                <legend>Search Results</legend>
                <table class="table-results themed">
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>Batch</th>
                        <th>Card Holder</th>
                        <th>Date / TID</th>
                        <th>Invoice ID</th>
                        <th>User Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Merchant</th>
                    </tr>
                    <?php
                    /** @var \Transaction\Model\TransactionRow $Transaction */
                    $odd = false;
                    foreach($Query as $Transaction) { ?>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td><a href='transaction?id=<?php echo $Transaction->getID(); ?>'><?php echo $Transaction->getID(); ?></a></td>
                        <td><?php if($Transaction->getOrderID()) { ?><a href='order?id=<?php echo $Transaction->getOrderID(); ?>'><?php echo $Transaction->getOrderID(); ?></a><?php } else echo 'N/A'; ?></td>
                        <td><?php if($Transaction->getBatchID()) { ?><a href='batch?id=<?php echo $Transaction->getBatchID(); ?>'><?php echo $Transaction->getBatchID(); ?></a><?php } else echo 'N/A'; ?></td>
                        <td><?php echo $Transaction->getHolderFullFullName(); ?></td>
                        <td><?php echo date("M jS Y G:i:s", strtotime($Transaction->getTransactionDate())); ?></td>
                        <td><?php echo $Transaction->getInvoiceNumber(); ?></td>
                        <td><?php echo $Transaction->getUsername(); ?></td>
                        <td><?php echo $Transaction->getAmount(); ?></td>
                        <td><?php echo $Transaction->getStatus(); ?></td>
                        <td><a href='merchant?id=<?php echo $Transaction->getMerchantID(); ?>'><?php echo $Transaction->getMerchantShortName(); ?></a></td>
                    </tr>
                    <?php } ?>
                </table>
            </fieldset>
            <fieldset class="paginate">
                <legend>Pagination</legend>
                <?php $Stats->printPagination('transaction?'); ?>
            </fieldset>
        </form>
    </section>