<?php
/**
 * open license
 */

/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();
$categoryTableName = 'icc_vchannel/category';
$itemTableName     = 'icc_vchannel/item';
/** @var $connection Varien_Db_Adapter_Interface */
$connection = $this->getConnection();

$categoryTable = $connection->newTable($this->getTable($categoryTableName));
$itemTable     = $connection->newTable($this->getTable($itemTableName));

$categoryTable->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => true,
));

$categoryTable->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => false,
    'unsigned' => true,
    'nullable' => true,
    'primary'  => false,
    'default'  => 0,
));

$categoryTable->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
    'identity' => false,
    'unsigned' => false,
    'nullable' => false,
    'primary'  => false,
));

$categoryTable->addColumn('thumbnail', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
    'identity' => false,
    'nullable' => false,
));

$categoryTable->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'identity' => false,
    'unsigned' => false,
    'nullable' => true,
    'primary'  => false,
));


$itemTable->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => true,
));

$itemTable->addColumn('type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64, array(
    'identity' => false,
    'nullable' => false,
));

$itemTable->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
    'identity' => false,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => false,
));

$itemTable->addColumn('featured', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
    'identity' => false,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => false,
    'default'  => 0,
));

$itemTable->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => false,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => false,
));

$itemTable->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
    'identity' => false,
    'nullable' => false,
));

$itemTable->addColumn('thumbnail', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
    'identity' => false,
    'nullable' => false,
));

$itemTable->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'identity' => false,
    'nullable' => false,
));

$itemTable->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'identity' => false,
    'nullable' => false,
));

$itemTable->addColumn('additional', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'identity' => false,
    'nullable' => false,
));

$connection->createTable($categoryTable);
$connection->createTable($itemTable);

$this->endSetup();
