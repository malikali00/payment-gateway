<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 11/16/2016
 * Time: 8:19 PM
 */
namespace App\Ticket;

use App\AbstractApp;
use User\Model\UserRow;

class NewsApp extends AbstractApp
{
    const SESSION_KEY = __FILE__;

    private $user;
    private $config;

    public function __construct(UserRow $SessionUser, $config) {
        $this->user = $SessionUser;
        $this->config = $config;
    }

    public function getUser() { return $this->user; }

    /**
     * Print an HTML representation of this app
     * @param array $params
     */
    function renderAppHTML(Array $params = array())
    {
        $appClassName = 'app-ticket-news';
        echo <<<HTML
        <div class="app-ticket app-ticket-news">
            <form name="app-ticket-news">
                <div class="app-section-top">
                    <div class="app-section-text-large" style="text-align: center;"> News &AMP; Announcements</div>
                    <hr />
                </div>
                <ul class="app-ticket-list">
                    <li style="font-style: italic;">No news at this time</li>
                </ul>
            </form>
            <div class="app-button app-button-config app-button-top-right">
                <ul class="app-menu">
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appTicketAction('move-up', '{$appClassName}');">Move up</a></li>
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appTicketAction('move-down', '{$appClassName}');">Move down</a></li>
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appTicketAction('move-top', '{$appClassName}');">Move to top</a></li>
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appTicketAction('move-bottom', '{$appClassName}');">Move to bottom</a></li>
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appTicketAction('config', '{$appClassName}');">Configure...</a></li>
                    <li><div class='app-button app-button-config'></div><a href="#" onclick="appTicketAction('remove', '{$appClassName}');">Remove</a></li>
                </ul>
            </div>
        </div>
HTML;
    }

    /**
     * Render all HTML Head Assets relevant to this APP
     */
    function renderHTMLHeadContent() {
        TicketAppConfig::renderHTMLHeadContent();
    }
}