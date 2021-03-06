<?php /** @var \User\View\LoginView $this  **/
use User\Session\SessionManager;

$odd = true;
// Render Header
/** @var \View\AbstractView $this */
$this->getTheme()->renderHTMLBodyHeader(\View\Theme\AbstractViewTheme::FLAG_HEADER_MINIMAL);

$SessionManager = new SessionManager();
?>

<article>

    <section class="not-content login-section">

        <?php if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>"; ?>

        <?php if(!empty($_GET['key']) && !empty($_GET['email'])) { ?>
        <form name="form-reset" class="themed" action='reset.php?action=reset' method='POST' id='form-reset'>
            <div class="logo" style="margin: 17px auto;"></div>

            <input type="hidden" name="action" value="reset" />
            <input type="hidden" name="key" value="<?php echo $_GET['key']; ?>" />
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" />
            <fieldset style="display: inline-block; padding: 0.5em; margin: 0.3em; text-align: left;">
                <div class="legend">RESET Password</div>

                <h3>Please enter your email address to receive a password reset link</h3>

                <table class="table-user-info themed">
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <input type="email" name="email" id="email" disabled="disabled" value="<?php echo $_GET['email']; ?>" class="themed"/>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td><input type="password" name="password" value="" autocomplete="off" placeholder="New Password" required/></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td><input type="password" name="password_confirm" value="" autocomplete="off" placeholder="Confirm Password" required/></td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <input type="submit" value="SUBMIT" class="themed"/>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="login-text">
                            <a href=".">Back to Login</a>
                        </td>
                    </tr>

                </table>
            </fieldset>
        </form>

        <?php } else { ?>

        <form name="form-reset" class="themed" action='reset.php?action=reset' method='POST' id='form-reset'>
            <div class="logo" style="margin: 17px auto;"></div>

            <input type="hidden" name="action" value="reset" />
            <fieldset style="display: inline-block; padding: 0.5em; margin: 0.3em; text-align: left;">
                <div class="legend">Password Reset</div>

                <div class="info" style="width: 300px;">Please enter your email address to receive a password reset link</div>

                <table class="table-user-info themed">
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td>
                            <input type="email" name="email" id="email" class="themed" placeholder="Recovery Email Address" value="" autofocus required/>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>" style="text-align: right;">
                        <td>
                            <input type="submit" value="SUBMIT" class="themed"/>
                        </td>
                    </tr>
                    <tr class="row-<?php echo ($odd=!$odd)?'odd':'even';?>">
                        <td class="login-text">
                            <a href=".">Back to Login</a>
                        </td>
                    </tr>

                </table>
            </fieldset>
        </form>
        <?php }  ?>
    </section>

</article>

<?php
/** @var \View\AbstractView $this */
$this->getTheme()->renderHTMLBodyHeader(\View\Theme\AbstractViewTheme::FLAG_FOOTER_MINIMAL);
?>
