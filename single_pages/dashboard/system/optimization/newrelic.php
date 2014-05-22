<?php
defined('C5_EXECUTE') or die('Access Denied.');

$uh = Loader::helper('concrete/urls');
$ih = Loader::helper('concrete/interface');
$dh = Loader::helper('concrete/dashboard');
?>

<?php echo $dh->getDashboardPaneHeaderWrapper(t('New Relic Settings'), t('Specify your custom New Relic settings in this screen.'), 'span5 offset3', false); ?>

<form method="post" id="form_newrelic_settings" action="<?php echo $this->action('save') ?>">
    <?php echo $vt->output(); ?>

    <div class="ccm-pane-body">
        <h5><?php echo t('Appname used in New Relic') ?></h5>
        <div class="clearfix inputs-list">
            <label for="appNameNone">
                <input type="radio" id="appNameNone" name="appName" <?php echo $appName === 'NONE' ? 'checked="checked"' : '' ?> value="NONE"/> <?php echo t('Default (don\'t override appname)') ?>
            </label>

            <label for="appNameSitename">
                <input type="radio" id="appNameSitename" name="appName" <?php echo $appName === 'SITENAME' ? 'checked="checked"' : '' ?> value="SITENAME"/> <?php echo t('Sitename (%s)', SITE) ?>
            </label>

            <label for="appNameHostname">
                <input type="radio" id="appNameHostname" name="appName" <?php echo $appName === 'HOSTNAME' ? 'checked="checked"' : '' ?> value="HOSTNAME"/> <?php echo t('Hostname (%s)', $_SERVER['HTTP_HOST']) ?>
            </label>

            <label for="appNameCustom">
                <input type="radio" id="appNameCustom" name="appName" <?php echo $appName === 'CUSTOM' ? 'checked="checked"' : '' ?> value="CUSTOM"/> <?php echo t('Custom') ?>
                <input type="text" name="appNameValue" value="<?php echo $appNameValue ?>"/>
            </label>
        </div>
        
        <h5><?php echo t('Discard transaction on appliation name change') ?></h5>
        <div class="clearfix inputs-list">
            <label for="xmitYes">
                <input type="radio" id="xmitYes" name="xmit" <?php echo $xmit === '1' ? 'checked="checked"' : '' ?> value="1"/> <?php echo t('Yes') ?>
            </label>
            <label for="xmitNo">
                <input type="radio" id="xmitNo" name="xmit" <?php echo $xmit === '1' ? '' : 'checked="checked"' ?> value="0"/> <?php echo t('No') ?>
            </label>
        </div>

        <h5><?php echo t('Mark as background job') ?></h5>
        <textarea name="backgroundJobs"><?php echo $backgroundJobs ?></textarea>
        <h5><?php echo t('Ignore Transaction') ?></h5>
        <textarea name="ignoreTransactions"><?php echo $ignoreTransactions ?></textarea>
    </div>
    <div class="ccm-pane-footer">
        <?php print $ih->submit(t('Save'), 'form_newrelic_settings', 'right', 'primary'); ?>
    </div>
</form>

<?php echo $dh->getDashboardPaneFooterWrapper(false); ?>
