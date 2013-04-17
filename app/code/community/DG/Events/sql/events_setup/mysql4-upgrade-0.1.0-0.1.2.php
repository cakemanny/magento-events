<?php
/**
 * Here we update the model to include an optional enddate and ability to
 * disable
 *
 * @author Daniel Golding
 */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$installer->getTable('events/event')} 
    ADD COLUMN `enddate` date AFTER `date`,
    ADD COLUMN `disabled` bool default '0',
    ADD INDEX (`enddate`);
");

$installer->endSetup();
