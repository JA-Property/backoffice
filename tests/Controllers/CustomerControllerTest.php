<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\CustomerController;
use App\Models\Customer;

/**
 * Class CustomerControllerTest
 *
 * Tests for our CustomerController, focusing on:
 * - renderAllCustomers (search, sort, pagination, etc.)
 * - handleBatchActions (POST actions)
 * - renderSingleCustomer
 * - renderNewCustomer / createAction
 *
 * We'll mock Customer model calls as needed.
 */
class CustomerControllerTest extends TestCase
{
    /** @var CustomerController */
    private $controller;

    protected function setUp(): void
    {
        // Create an instance of our controller before each test
        $this->controller = new CustomerController();
    }

    /**
     * Because the controller relies on superglobals ($_GET, $_POST),
     * we can override them directly. For each test, we’ll reset them.
     */
    protected function tearDown(): void
    {
        $_GET  = [];
        $_POST = [];
        // If session usage is tested, you might also reset $_SESSION = [];
    }

    /**
     * We’ll define a small helper to mock Customer’s static methods.
     * In real usage, you might rely on a DI container or a refactoring.
     */
    private function mockCustomerModel()
    {
        // We'll use "static::getMockForAbstractClass()" or "mock the class" is tricky with static methods.
        // Another approach is to use "namespace aliasing" or a library like Mockery.
        // For demonstration, we do partial: we replace "Customer" with a double that we call manually.
        // This is the simplest approach in a bare test scenario:
        $mock = $this->getMockBuilder(Customer::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods([
                         'getCustomers', 'getCustomersCount', 'getTotalBalance',
                         'getCustomerById', 'getCustomerAddresses', 'getCustomerProperties',
                         'getCustomerNotes', 'createCustomer', 'getStatusIdByName',
                         'batchUpdateStatus', 'batchSendEmail'
                     ])
                     ->getMock();

        return $mock;
    }

    /* -----------------------------------------------
     * 1) renderAllCustomers() - Basic scenario
     * ----------------------------------------------- */
    public function testRenderAllCustomersBasic()
    {
        // Setup mocks
        $mockCustomer = $this->mockCustomerModel();

        // Expect getCustomers called with default offset/limit
        $mockCustomer->method('getCustomers')
                     ->willReturn([
                         // example row
                         ['customer_id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '555-1234', 'propertyCount' => 2],
                         // add more if needed
                     ]);

        $mockCustomer->method('getCustomersCount')->willReturn(1);
        $mockCustomer->method('getTotalBalance')->willReturn(1500.0);

        // Prepare $_GET so the controller uses them
        $_GET['page']      = '1';
        $_GET['pageSize']  = '10';

        // We'll capture output using output buffering
        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();

        // Basic check: we expect some HTML with "All Customers" or similar
        $this->assertStringContainsString('All Customers', $output, "Expected 'All Customers' in output.");
    }

    /* -----------------------------------------------
     * 2) renderAllCustomers() - With search
     * ----------------------------------------------- */
    public function testRenderAllCustomersWithSearch()
    {
        $mockCustomer = $this->mockCustomerModel();

        // If "John" is the searchTerm, only rows with "John" are returned
        $mockCustomer->method('getCustomers')
                     ->willReturn([
                        ['customer_id' => 1, 'name' => 'John Smith', 'email' => 'john@example.com', 'phone' => '555-1234', 'propertyCount' => 1],
                     ]);
        $mockCustomer->method('getCustomersCount')->willReturn(1);
        $mockCustomer->method('getTotalBalance')->willReturn(999.99);

        $_GET['search'] = 'John';

        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();

        // Check that "John" is presumably in the output somewhere
        $this->assertStringContainsString('John', $output);
    }

    /* -----------------------------------------------
     * 3) renderAllCustomers() - With statusFilter
     * ----------------------------------------------- */
    public function testRenderAllCustomersWithStatusFilter()
    {
        $mockCustomer = $this->mockCustomerModel();

        // Overdue status filter scenario
        $mockCustomer->method('getCustomers')
                     ->willReturn([
                         ['customer_id' => 2, 'name' => 'Jane Overdue', 'email' => 'jane@example.com', 'propertyCount' => 0],
                     ]);
        $mockCustomer->method('getCustomersCount')->willReturn(1);
        $mockCustomer->method('getTotalBalance')->willReturn(500.0);

        $_GET['status'] = 'Overdue';

        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();

        // Basic check
        $this->assertStringContainsString('Jane Overdue', $output);
    }

    /* -----------------------------------------------
     * 4) renderAllCustomers() - Sorting
     * ----------------------------------------------- */
    public function testRenderAllCustomersSorting()
    {
        $mockCustomer = $this->mockCustomerModel();

        // Sorting by email DESC
        $mockCustomer->method('getCustomers')->willReturn([
            ['customer_id' => 100, 'name' => 'Zeta Email', 'email' => 'zeta@example.com'],
            ['customer_id' => 99,  'name' => 'Alpha Email','email' => 'alpha@example.com'],
        ]);
        $mockCustomer->method('getCustomersCount')->willReturn(2);
        $mockCustomer->method('getTotalBalance')->willReturn(0.0);

        $_GET['sort'] = 'email';
        $_GET['order'] = 'desc';

        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();

        // We might check that "zeta" appears before "alpha" or just confirm we see them
        $this->assertStringContainsString('zeta@example.com', $output);
        $this->assertStringContainsString('alpha@example.com', $output);
        $this->assertTrue(
            strpos($output, 'zeta@example.com') < strpos($output, 'alpha@example.com'),
            "Expected zeta to appear before alpha in DESC order"
        );
    }

    /* -----------------------------------------------
     * 5) handleBatchActions() - Mark Overdue
     * ----------------------------------------------- */
    public function testHandleBatchActionsMarkOverdue()
    {
        $mockCustomer = $this->mockCustomerModel();
        // If Overdue ID is, say, 2
        $mockCustomer->method('getStatusIdByName')->willReturn(2);

        // We'll assume batchUpdateStatus is called once
        $mockCustomer->expects($this->once())
                     ->method('batchUpdateStatus')
                     ->with($this->equalTo([1,2,3]), 2);

        // Prepare POST data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['batchAction']  = 'markOverdue';
        $_POST['customerIds']  = [1,2,3];

        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();

        // Because handleBatchActions does a redirect, we won't see normal HTML output
        // We can check output or check if headers_list() contains "Location: /customers"
        $this->assertStringNotContainsString("Fatal error", $output);
    }

    /* -----------------------------------------------
     * 6) handleBatchActions() - sendEmail
     * ----------------------------------------------- */
    public function testHandleBatchActionsSendEmail()
    {
        $mockCustomer = $this->mockCustomerModel();
        $mockCustomer->expects($this->once())
                     ->method('batchSendEmail')
                     ->with([4,5]);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['batchAction'] = 'sendEmail';
        $_POST['customerIds'] = [4,5];

        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();
        // If no errors, test passes
        $this->assertTrue(true);
    }

    /* -----------------------------------------------
     * 7) renderSingleCustomer() - Valid
     * ----------------------------------------------- */
    public function testRenderSingleCustomerValid()
    {
        // Mock the static call
        $mockCustomer = $this->getMockBuilder(\App\Models\Customer::class)
                             ->disableOriginalConstructor()
                             ->onlyMethods(['getCustomerById'])
                             ->getMock();
    
        $mockCustomer->method('getCustomerById')
                     ->willReturn(['customer_id' => 42, 'display_name' => 'John Example']);
    
        // Now set up $_GET
        $_GET['id'] = '42';
    
        // Call the method
        ob_start();
        $this->controller->renderSingleCustomer();
        $output = ob_get_clean();
    
        $this->assertStringContainsString('John Example', $output);
    }
    

    /* -----------------------------------------------
     * 8) renderSingleCustomer() - Invalid ID
     * ----------------------------------------------- */
    public function testRenderSingleCustomerInvalidId()
    {
        $_GET['id'] = '0';

        ob_start();
        $this->controller->renderSingleCustomer();
        $output = ob_get_clean();

        $this->assertStringContainsString('Invalid customer ID.', $output);
    }

    /* -----------------------------------------------
     * 9) renderSingleCustomer() - Nonexistent
     * ----------------------------------------------- */
    public function testRenderSingleCustomerNonexistent()
    {
        $mockCustomer = $this->mockCustomerModel();
        $mockCustomer->method('getCustomerById')->willReturn(null);

        $_GET['id'] = '99999';

        ob_start();
        $this->controller->renderSingleCustomer();
        $output = ob_get_clean();

        $this->assertStringContainsString('Customer not found.', $output);
    }

    /* -----------------------------------------------
     * 10) renderNewCustomer() - Simple
     * ----------------------------------------------- */
    public function testRenderNewCustomer()
    {
        ob_start();
        $this->controller->renderNewCustomer();
        $output = ob_get_clean();

        // We expect to see "New Customer" somewhere
        $this->assertStringContainsString('New Customer', $output);
    }

    /* -----------------------------------------------
     * 11) createAction() - POST success
     * ----------------------------------------------- */
    public function testCreateActionPostSuccess()
    {
        $mockCustomer = $this->mockCustomerModel();
        // If all goes well, we get a new ID = 123
        $mockCustomer->method('createCustomer')->willReturn(123);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'res_first_name' => 'John',
            'res_last_name'  => 'Smith'
        ];

        ob_start();
        $this->controller->createAction();
        $output = ob_get_clean();

        // The method calls redirect("/customers/view?id=123&tab=properties")
        // So let's see if we can check the "headers_list()" for the location
        $headers = xdebug_get_headers() ?: [];  // xdebug_get_headers() sometimes needed
        $this->assertContains('Location: /customers/view?id=123&tab=properties', $headers);
    }

    /* -----------------------------------------------
     * 12) createAction() - POST exception
     * ----------------------------------------------- */
    public function testCreateActionPostException()
    {
        $mockCustomer = $this->mockCustomerModel();
        // Force an exception
        $mockCustomer->method('createCustomer')
                     ->willThrowException(new Exception("DB Error"));

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'res_first_name' => 'Bad',
            'res_last_name'  => 'Data'
        ];

        ob_start();
        $this->controller->createAction();
        $output = ob_get_clean();

        $this->assertStringContainsString('Error creating customer: DB Error', $output);
    }

    /* -----------------------------------------------
     * 13) createAction() - GET
     * ----------------------------------------------- */
    public function testCreateActionGet()
    {
        // Method should call renderNewCustomer
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start();
        $this->controller->createAction();
        $output = ob_get_clean();

        $this->assertStringContainsString('New Customer', $output);
    }

    /* -----------------------------------------------
     * 14) handleBatchActions() - No action
     * ----------------------------------------------- */
    public function testHandleBatchActionsNoAction()
    {
        $mockCustomer = $this->mockCustomerModel();

        // No calls expected
        $mockCustomer->expects($this->never())->method('batchUpdateStatus');
        $mockCustomer->expects($this->never())->method('batchSendEmail');

        // If POST has no batchAction or no customerIds, it does nothing
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // no batchAction set
        ob_start();
        $this->controller->renderAllCustomers();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('Fatal error', $output);
    }

    /* -----------------------------------------------
     * 15) Performance or large dataset tests
     * ----------------------------------------------- */
    public function testRenderAllCustomersPerformance()
    {
        // This is more of an integration test. We can do a simple time-based check.
        // We'll mock a scenario with 1000 customers. Real test might measure time.
        $mockCustomer = $this->mockCustomerModel();

        $fakeCustomers = [];
        for ($i=0; $i < 1000; $i++) {
            $fakeCustomers[] = [
                'customer_id' => $i+1, 
                'name' => 'Fake Cust '.$i, 
                'email' => 'fake'.$i.'@test.com',
                'phone' => null, 
                'propertyCount' => 0,
            ];
        }
        $mockCustomer->method('getCustomers')->willReturn($fakeCustomers);
        $mockCustomer->method('getCustomersCount')->willReturn(1000);
        $mockCustomer->method('getTotalBalance')->willReturn(50000.0);

        // Rough timing check
        $start = microtime(true);

        ob_start();
        $this->controller->renderAllCustomers();
        ob_end_clean();

        $duration = microtime(true) - $start;
        // Suppose we want it under 2 seconds
        $this->assertLessThan(2.0, $duration, "renderAllCustomers took too long with 1000 records");
    }
}

