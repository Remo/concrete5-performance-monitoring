<?php

defined('C5_EXECUTE') or die('Access Denied.');

class DashboardSystemOptimizationNewrelicController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $this->addCommonElements();
    }

    public function on_start() {
        parent::on_start();
        $this->set('error', Loader::helper('validation/error'));
        $this->set('vt', Loader::helper('validation/token'));
    }

    protected function addCommonElements() {
        $html = Loader::helper('html');

        $pkg = Package::getByHandle('newrelic');

        $this->set('appName', $pkg->config('NEWRELIC_APPNAME'));
        $this->set('appNameValue', $pkg->config('NEWRELIC_APPNAME_VALUE'));
        $this->set('backgroundJobs', $pkg->config('NEWRELIC_BACKGROUND_JOBS'));
        $this->set('ignoreTransactions', $pkg->config('NEWRELIC_IGNORE_TRANSACTIONS'));

        $this->addHeaderItem($html->javascript('newrelic.settings.js', 'newrelic'));
        $this->addHeaderItem($html->css('newrelic.settings.css', 'newrelic'));
    }

    public function save_complete() {
        $this->addCommonElements();
        $this->set('message', t('Settings saved'));
    }

    public function save() {
        $vf = Loader::helper('validation/form');
        $vf->setData($this->post());
        $vf->addRequired('appName', t("You haven't specified an appname or appname method!"));
        $vf->addRequiredToken('');

        if ($vf->test()) {
            $pkg = Package::getByHandle('newrelic');
            $pkg->saveConfig('NEWRELIC_APPNAME', $this->post('appName'));
            $pkg->saveConfig('NEWRELIC_APPNAME_VALUE', $this->post('appNameValue'));
            $pkg->saveConfig('NEWRELIC_BACKGROUND_JOBS', $this->post('backgroundJobs'));
            $pkg->saveConfig('NEWRELIC_IGNORE_TRANSACTIONS', $this->post('ignoreTransactions'));

            $this->redirect('/dashboard/system/optimization/newrelic/', 'save_complete');
        } else {
            $this->error = $vf->getError();
        }
    }

}