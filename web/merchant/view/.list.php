<?php
use \Merchant\Model\MerchantRow;
/**
 * @var \View\AbstractListView $this
 **/?>
    <section class="content">
        <div class="action-fields">
            <a href="merchant?" class="button current">Merchant List</a>
        </div>

        <?php if($this->hasException()) { ?>
            <h5><?php echo $this->hasException(); ?></h5>

        <?php } else if ($this->hasSessionMessage()) { ?>
            <h5><?php echo $this->popSessionMessage(); ?></h5>

        <?php } ?>

        <form class="form-search themed">
            <fieldset class="search-fields">
                <legend>Search</legend>
                MERCHANT NAME:
                <input type="text" name="search" value="<?php echo @$_GET['search']; ?>" />
                <select name="limit">
                    <?php
                    $limit = @$_GET['limit'] ?: 50;
                    foreach(array(10,25,50,100,250) as $opt)
                        echo "<option", $limit == $opt ? ' selected="selected"' : '' ,">", $opt, "</option>\n";
                    ?>
                </select>
                <input type="submit" value="Search" />

            </fieldset>
            <fieldset>
                <legend>Search Results</legend>
                <table class="table-results themed small">
                    <tr>
                        <th><a href="merchant?<?php echo $this->getSortURL(MerchantRow::SORT_BY_ID); ?>">ID</a></th>
                        <th><a href="merchant?<?php echo $this->getSortURL(MerchantRow::SORT_BY_NAME); ?>">Name</a></th>
                        <th>URL</th>
                        <th>State</th>
                        <th>Zipcode</th>
                    </tr>
                    <?php
                    /** @var \Merchant\Model\MerchantRow $Merchant */
                    $odd = false;
                    foreach($this->getListQuery() as $Merchant) { ?>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td><a href='merchant?id=<?php echo $Merchant->getID(); ?>'><?php echo $Merchant->getID(); ?></a></td>
                        <td><a href='merchant?id=<?php echo $Merchant->getID(); ?>'><?php echo $Merchant->getShortName(); ?></a></td>
                        <td><a target="_blank" href='<?php echo $Merchant->getURL(); ?>'><?php echo preg_replace('/^https?:\/\//i', '', $Merchant->getURL()); ?></a></td>
                        <td><?php echo $Merchant->getRegionCode(); ?></td>
                        <td><?php echo $Merchant->getZipCode(); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </fieldset>
            <fieldset class="paginate">
                <legend>Pagination</legend>
                <?php $this->printPagination('merchant?'); ?>


                <?php if($this->hasException()) { ?>
                    <h5><?php echo $this->hasException(); ?></h5>

                <?php } else if($this->hasMessage()) { ?>
                    <h6><?php echo $this->getMessage() ?></h6>

                <?php } ?>

            </fieldset>
        </form>
    </section>