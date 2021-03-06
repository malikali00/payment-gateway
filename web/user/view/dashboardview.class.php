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
		$SessionManager = new SessionManager();
		$SessionUser = $SessionManager->getSessionUser();

		$AppManager = new AppManager($SessionUser);

		$Theme = $this->getTheme();
		$Theme->addPathURL('/', $SessionUser->getFullName());
		$Theme->renderHTMLBodyHeader();
		$Theme->printHTMLMenu('dashboard');
		?>
		<article class="themed">
			<section class="content dashboard-section">
				<?php
				if($SessionManager->hasMessage()) echo "<h5>", $SessionManager->popMessage(), "</h5>";
				$AppManager->renderAppHTMLContent();
				?>
			</section>
		</article>
		<?php
		$Theme->renderHTMLBodyFooter();
	}

	public function processFormRequest(Array $post) {
		$SessionManager = new SessionManager();
		try {
			$SessionManager->setMessage("Unhandled Form Post: " . __CLASS__);
			header("Location: index.php");

		} catch (\Exception $ex) {
			$SessionManager->setMessage($ex->getMessage());
			header("Location: login.php");
		}
	}
}

