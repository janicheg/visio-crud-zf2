<?php

namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Model\TableGateway\Entity\Customer;
use VisioCrudModeler\Filter\CustomerFilter;
use \VisioCrudModeler\Table\CustomerTable as CustomerGrid;

class CustomerController extends AbstractActionController
{
    
    /**
     *  
     * @var Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter;
    
    
    
    /*********** CREATE *******************/ 
    /**************************************/
    
    public function addAction()
    {
        $form = new \VisioCrudModeler\Form\CustomerForm();

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $customer = new Customer();
            $customerFilter = new CustomerFilter();
            
            $form->setInputFilter($customerFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $customer->exchangeArray($form->getData());
                
                $this->getCustomerTable()->insert($customer);

                // Redirect to list of albums
                return $this->redirect()->toUrl('/visio-crud-modeler/customer/list');
            }
        }
        return array('form' => $form);
    }
    
    
    
    /**
     * ********* READ ********************
     * ***********************************
     */
    public function listAction()
    {
    }
    public function ajaxListAction()
    {
        
        $table = new CustomerGrid;
        $table->setAdapter($this->getDbAdapter())
                ->setSource($this->getCustomerTable()->getBaseQuery())
                ->setParamAdapter($this->getRequest()->getPost())
        ;
        return $this->htmlResponse($table->render());
    }
    
    
    /*********** EDIT *********************/ 
    /**************************************/
    
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toUrl('/visio-crud-modeler/customer/list');
        
        }
        $customer = $this->getCustomerTable()->getRow($id);
        
        $form = new \VisioCrudModeler\Form\CustomerForm();
        $form->bind($customer);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $customerFilter = new CustomerFilter();
            $customer->setInputFilter($customerFilter->getInputFilter());
            $form->setInputFilter($customerFilter->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $this->getCustomerTable()->update($form->getData());
                return $this->redirect()->toUrl('/visio-crud-modeler/customer/list');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    /*********** DELETE *********************/ 
    /**************************************/
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toUrl('/visio-crud-modeler/customer/list');
        }
        $customer = $this->getCustomerTable()->getRow($id);
        $this->getCustomerTable()->delete($customer);
        
        return $this->redirect()->toUrl('/visio-crud-modeler/customer/list');
        
    }
    
    
    /*********** HELPER METHODS*************/ 
    /**************************************/
    
    
    /**
     * 
     * @return \VisioCrudModeler\Model\TableGateway\Entity\CustomerTable
     */
    public function getCustomerTable()
    {
        $table = new \VisioCrudModeler\Model\TableGateway\Entity\CustomerTable($this->getDbAdapter());
        $table->setEventManager($this->getEventManager());
        return $table;
    }
    
    /**
     * 
     * @return Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        if (!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }
    
    public function htmlResponse($html)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($html);
        return $response;
    }
    
}
