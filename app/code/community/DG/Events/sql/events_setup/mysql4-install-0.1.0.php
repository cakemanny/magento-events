<?php

$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$installer->getTable('events/event')};
CREATE TABLE {$installer->getTable('events/event')} (
    `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL default '',
    `description` text,
    `destination` text,
    `date` date,
    `image` text,
    `store` varchar(255),
    PRIMARY KEY (`event_id`)
);

INSERT INTO {$installer->getTable('events/event')}
    (`event_id`, `title`, `description`, `destination`, `date`, `image`, `store`)
    VALUES (NULL, 'Example Event', 'Example description of event', '/blog', '2013-03-05', '/media/wysiwyg/Blog/Screen_Shot_2013-02-15_at_14.36.26.png', 'Tunbridge Wells');
");

$installer->endSetup();
