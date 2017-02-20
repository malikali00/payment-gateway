<?php
namespace User\View;

use App\AppManager;
use User\Session\SessionManager;
use View\AbstractView;


class DashboardView extends AbstractView {

    protected function renderHTMLHeadLinks() {
		$SessionManager = new SessionManager();
		$SessionUser = $SessionManager->getSessionUser();
		$AppManager = new AppManager($SessionUser);
		$AppManager->renderHTMLHeadContent();

		echo "\t\t<link href='user/view/assets/dashboard.css' type='text/css' rel='stylesheet' />\n";
        parent::renderHTMLHeadLinks();
    }

	public function renderHTMLBody(Array $params) {
		// Render Page
		include ('.dashboard.php');
	}

	public function processFormRequest(Array $post) {
		try {
			$this->setSessionMessage("Unhandled Form Post");
			header("Location: /");

		} catch (\Exception $ex) {
			$this->setSessionMessage($ex->getMessage());
			header("Location: login.php");
		}
	}
}

