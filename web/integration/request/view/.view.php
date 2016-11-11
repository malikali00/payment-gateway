<?php
use Integration\Request\View\IntegrationRequestView;
/**
 * @var IntegrationRequestView $this
 **/
$Request = $this->getRequest();
$odd = false;
$action_url = 'integration/request?id=' . $Request->getID() . '&action=';
$this->getTheme()->printHTMLMenu('integration-request-view', $action_url);
?>


    <!-- Bread Crumbs -->
    <aside class="bread-crumbs">
        <a href="integration" class="nav_integration">Integration</a>
        <a href="request" class="nav_integration_request">Requests</a>
        <a href="<?php echo $action_url; ?>view" class="nav_request_view">#<?php echo $Request->getID(); ?></a>
    </aside>


    <article class="themed">
        <section class="content">
            <?php if($this->hasMessage()) echo "<h5>", $this->getMessage(), "</h5>"; ?>

            <form class="form-view-integration-request themed" onsubmit="return false;">
                <fieldset>
                    <legend>Request Information</legend>
                    <table class="table-integration-request-info themed striped-rows">
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
                            <td>URL</td>
                            <td><a href="<?php echo $Request->getRequestURL(); ?>" target="_blank"><?php echo $Request->getRequestURL(); ?></a></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td>Result</td>
                            <td><?php echo $Request->getResult(); ?></td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td>Request</td>
                            <td>
                                <textarea rows="30" cols="58" onclick="this.rows++; this.cols+=3;"><?php
                                    echo $Request->getRequest();
                                    ?></textarea>
                            </td>
                        </tr>
                        <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                            <td>Response</td>
                            <td>
                                <textarea rows="30" cols="58" onclick="this.rows++; this.cols+=3;"><?php
                                    echo $Request->getResponse();
                                    ?></textarea>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </section>
    </article>