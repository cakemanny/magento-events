<?php
/**
 * Events Installation Script
 *
 * @author Daniel Golding
 */

/**
 * @var $installer Mage_Core_Mage_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

/**
 * Creating table dg_events
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('events/event'))
    ->addColumn('event_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity id')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'Title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        'nullable' => true,
        'default' => null,
    ), 'Short description')
    ->addColumn('destination', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        'nullable' => true,
        'default' => null,
    ), 'URI for feature page')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => true,
        'default' => null,
    ), 'URI for feature page')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
        'default' => null,
    ), 'URI for title image')
    /* Rinse and repeat for columns*/
    // Calendar image
    // stores
    ->addColumn('store', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
        'default' => null,
    ), 'Store the event relates to')
    ->addIndex($installer->getIdxName(
            $installer->getTable('events/event'),
            array('date'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('date'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->setComment('Event item');
// debug -- loud
    echo "Installing:". get_class($this) . "\n";
// debug
$installer->getConnection()->createTable($table);

$installer->endSetup();

