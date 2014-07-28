<?php
namespace VisioCrudModeler\DataSource\DAL;

use VisioCrudModeler\DataSource\DataSet\DataSetInterface;
use Zend\Db\TableGateway\TableGateway;
use VisioCrudModeler\DataSource\Descriptor\DataSetDescriptorInterface;

class TableGateway extends TableGateway implements DataSetInterface
{
    protected $descriptor=null;
    public function __construct(DataSetDescriptorInterface $descriptor)
    {
        // @todo add merge logic for descriptor
    }
    public function descriptor()
    {
        //@todo add descriptor logic
    }
	/* (non-PHPdoc)
     * @see \VisioCrudModeler\DataSource\DataSet\DataSetInterface::create()
     */
    public function create ($data = null)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \VisioCrudModeler\DataSource\DataSet\DataSetInterface::read()
     */
    public function read (\Zend\Db\Sql\Where $where = null)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \VisioCrudModeler\DataSource\DataSet\DataSetInterface::update()
     */
    public function update ($data,\Zend\Db\Sql\Where $where = null)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \VisioCrudModeler\DataSource\DataSet\DataSetInterface::delete()
     */
    public function delete (\Zend\Db\Sql\Where $where = null)
    {
        // TODO Auto-generated method stub
        
    }

}