<?php /** @var \User\View\LoginView $this  **/
use User\Session\SessionManager;
use System\Config\SiteConfig;

$odd = true;
// Render Header
/** @var \View\AbstractView $this */
$Theme = $this->getTheme();
$Theme->renderHTMLBodyHeader(\View\Theme\AbstractViewTheme::FLAG_HEADER_MINIMAL);

$SessionManager = new SessionManager();
?>

<article>

    <section class="not-content login-section">


        <form name="form-login" class="themed bounceIn" action='login.php?action=login' method='POST' id='form-login'>
            <div class="logo"></div>

            <hr />
            <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>

            <fieldset style=" padding: 0.5em; margin: 0.3em; ">

                <table class="table-user-info themed" style="text-align: left;">
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <strong style="font-size: larger;">Sign in to your account</strong>

                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <input type="text" name="username" id="username" placeholder="Username" value="<?php echo SiteConfig::$SITE_DEFAULT_LOGIN_USERNAME; ?>" autofocus required class="themed"/>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <input type="password" name="password" id="password" value="<?php echo SiteConfig::$SITE_DEFAULT_LOGIN_PASSWORD; ?>" placeholder="Password" autocomplete="off" required class="themed" />
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <label class="login-cookie-text">
                                <input type="checkbox" name="cookie" id="cookie" value="1" class="themed" />
                                Log me in automatically
                            </label>
                            <input type="submit" value="Login" class="themed" style="float: right;"/>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="login-text">
                            <a href="/reset.php">Password Reset</a>
                        </td>
                    </tr>

                </table>
            </fieldset>
        </form>
    </section>

</article>

<?php
/** @var \View\AbstractView $this */
$Theme->renderHTMLBodyFooter();
?>
