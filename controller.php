<?php

defined('C5_EXECUTE') or die("Access Denied.");

class NewrelicPackage extends Package {

    protected $pkgHandle = 'newrelic';
    protected $appVersionRequired = '5.6.0';
    protected $pkgVersion = '0.9.1';
    private $package;

    public function getPackageName() {
        return t("New Relic");
    }

    public function getPackageDescription() {
        return t("Installs the New Relic integration add-on.");
    }

    private function addSinglePage($path, $name, $description = '', $icon = '') {
        Loader::model('single_page');
        $page = Page::getByPath($path);
        if (is_object($page) && $page->getCollectionID() > 0) {
            return;
        }
        $sp = SinglePage::add($path, $this->package);
        $sp->update(array('cName' => $name, 'cDescription' => $description));

        if ($icon != '') {
            $sp->setAttribute('icon_dashboard', $icon);
        }
    }

    public function install() {
        $this->package = parent::install();

        // install dashboard pages
        $this->addSinglePage('/dashboard/system/optimization/newrelic', t('New Relic'), t('New Relic perfomance monitoring.'));

        // add default configuration values
        $this->package->saveConfig('NEWRELIC_APPNAME', 'NONE');
        $this->package->saveConfig('NEWRELIC_BACKGROUND_JOBS', '\/tools\/required\/jobs');
        $this->package->saveConfig('NEWRELIC_IGNORE_TRANSACTIONS', '^\/dashboard\/');
    }

    public function on_start() {

        if (!extension_loaded('newrelic')) {
            // don't try to call newrelic functions if extension is not loaded
            return;
        }

        $r = Request::get();

        // make sure newrelic knows on which line we are
        $url = '/' . $r->getRequestPath();
        newrelic_name_transaction($url);

        // load newrelic configuration
        $pkg = Package::getByHandle('newrelic');
        $xmit = (bool) $pkg->config('NEWRELIC_XMIT');

        switch ($pkg->config('NEWRELIC_APPNAME')) {
            case 'HOSTNAME':
                newrelic_set_appname($_SERVER['HOST_NAME'], '', $xmit);
                break;
            case 'SITENAME':
                newrelic_set_appname(SITE, '', $xmit);
                break;
            case 'CUSTOM':
                newrelic_set_appname($pkg->config('NEWRELIC_APPNAME_VALUE'), '', $xmit);
                break;
            default:
                // NONE
                break;
        }

        $backgroundJobs = preg_split('/$\R?^:/m', $pkg->config('NEWRELIC_BACKGROUND_JOBS'));
        foreach ($backgroundJobs as $backgroundJob) {
            if (preg_match('/' . $backgroundJob . '/', $url)) {
                newrelic_background_job(true);
            }
        }
        $ignoreTransactions = preg_split('/$\R?^:/m', $pkg->config('NEWRELIC_IGNORE_TRANSACTIONS'));
        foreach ($ignoreTransactions as $ignoreTransaction) {
            if (preg_match('/' . $ignoreTransaction . '/', $url)) {
                newrelic_ignore_transaction();
            }
        }
    }

}
