<?php
use Integration\Request\View\IntegrationRequestView;
/**
 * @var IntegrationRequestView $this
 **/
$Request = $this->getRequest();
$odd = false;
$action_url = 'integration/request?id=' . $Request->getID() . '&action=';
?>
    <section class="content">
        <div class="action-fields">
            <a href="integration?" class="button">Integrations</a>
            <a href="integration/request?" class="button">Requests</a>
            <a href="<?php echo $action_url; ?>edit" class="button">Edit</a>
        </div>

        <h1>View Integration Request</h1>

        <?php if($this->hasException()) { ?>
            <h5><?php echo $this->hasException(); ?></h5>

        <?php } else if ($this->hasSessionMessage()) { ?>
            <h5><?php echo $this->popSessionMessage(); ?></h5>

        <?php } else { ?>
            <h5>View an integration request...</h5>

        <?php } ?>

        <form class="form-view-integration-request themed" onsubmit="return false;">
            <fieldset>
                <legend>Request Information</legend>
                <table class="table-integration-request-info themed">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>ID</td>
                        <td><?php echo $Request->getID(); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Integration</td>
                        <td><a href='integration?id=<?php echo $Request->getIntegrationID(); ?>'><?php echo $Request->getIntegrationName(); ?></a></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Type</td>
                        <td><a href='integration/request?type_id=<?php echo $Request->getIntegrationTypeID(); ?>'><?php echo $Request->getIntegrationType(); ?></a></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Type ID</td>
                        <td>
                            <a href='<?php echo strtolower($Request->getIntegrationType()); ?>?id=<?php echo $Request->getIntegrationTypeID(); ?>'>
                                <?php echo $Request->getIntegrationTypeID(); ?>
                            </a>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Date</td>
                        <td><?php echo date("M jS Y G:i:s", strtotime($Request->getDate())); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Result</td>
                        <td><?php echo $Request->getResult(); ?></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>Response</td>
                        <td>
                            <textarea rows="30" cols="48" onclick="this.rows++; this.cols+=3;"><?php
                                //                                echo "Response:\n";
                                echo $Request->getResponse();
                                echo "\n\nRequest:\n";
                                echo $Request->getRequest();
                                ?></textarea>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </section>