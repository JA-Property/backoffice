  <!-- Breadcrumb Container -->
  <div class="bg-white shadow-sm rounded-md p-3 md:p-4">
    <nav class="text-sm" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-2">
        <!-- Home -->
        <li>
          <a
            href="/staff"
            class="text-blue-600 hover:underline hover:text-blue-800 transition"
          >
            Home
          </a>
        </li>
        <!-- Separator -->
        <li class="text-gray-400">
          <svg
            class="w-4 h-4"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-hidden="true"
          >
            <path
              fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10
                 7.293 6.707a1 1 0 011.414-1.414l4 
                 4a1 1 0 010 1.414l-4 4a1 1 
                 0 01-1.414 0z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </li>

        <!-- Current page -->
        <li class="text-gray-500" aria-current="page">
          All Customers
        </li>
      </ol>
    </nav>
  </div>

  <!-- Unified Container -->
  <div class="bg-white shadow-sm rounded-md p-3 md:p-4 space-y-4">

    <!-- 2) QUICK STATS -->
    <div class="flex flex-row items-start sm:items-center justify-between space-y-0">
      <div>
        <span class="text-gray-600 font-semibold">Total Customers:</span>
        <!-- Dynamically show the count -->
        <span id="totalCustomers" class="text-gray-800">
          <?= htmlspecialchars($totalCustomers ?? 0) ?>
        </span>
      </div>
      <div>
        <span class="text-gray-600 font-semibold">Outstanding Balance:</span>
        <!-- Dynamically show the total balance -->
        <span id="totalBalance" class="text-gray-800">
          $<?= number_format($totalBalance ?? 0, 2) ?>
        </span>
      </div>
    </div>

    <!-- 3) FILTER / SEARCH / COLUMN TOGGLE -->
    <div class="flex flex-wrap items-center gap-3">

      <!-- Search Bar (use GET or a combined form approach) -->
      <div class="relative flex-grow min-w-[200px] sm:flex-grow-0 sm:w-1/3 lg:w-1/4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="fa fa-search"></i>
        </span>
        <input
          type="text"
          id="searchInput"
          name="search"
          value="<?= isset($searchTerm) ? htmlspecialchars($searchTerm) : '' ?>"
          class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md 
                 focus:outline-none focus:ring-1 focus:ring-blue-500 
                 transition text-sm"
          placeholder="Search customers..."
        />
      </div>

      <!-- Status Filter -->
      <div class="flex items-center space-x-2">
        <label for="statusFilter" class="text-sm text-gray-600 whitespace-nowrap">
          Status:
        </label>
        <select
          id="statusFilter"
          name="status"
          class="bg-white border border-gray-300 rounded-md px-2 py-1 
                 focus:outline-none focus:ring-1 focus:ring-blue-500 
                 transition text-sm"
        >
          <option value="">All</option>
          <option value="Active"   <?php if(($statusFilter ?? '') === 'Active') echo 'selected'; ?>>Active</option>
          <option value="Inactive" <?php if(($statusFilter ?? '') === 'Inactive') echo 'selected'; ?>>Inactive</option>
          <option value="Overdue"  <?php if(($statusFilter ?? '') === 'Overdue')  echo 'selected'; ?>>Overdue</option>
        </select>
      </div>

      <!-- Column Toggle Dropdown -->
      <div class="relative">
        <button
          class="bg-gray-100 text-gray-700 text-sm px-3 py-1.5 rounded-md border
                 border-gray-300 hover:bg-gray-200 transition"
          onclick="toggleColumnDropdown()"
        >
          Columns
        </button>
        <div
          id="columnDropdown"
          class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 
                 rounded-md shadow-lg p-2 z-50 transition ease-in-out duration-150"
        >
          <!-- Each checkbox controls visibility of a column -->
          <div class="flex flex-col space-y-1">
            <div class="p-1 rounded hover:bg-gray-50">
              <label for="toggleName" class="inline-flex items-center text-sm space-x-2 cursor-pointer">
                <input type="checkbox" id="toggleName" checked disabled />
                <span>Name (always on)</span>
              </label>
            </div>
            <div class="p-1 rounded hover:bg-gray-50">
              <label for="togglePropertyCount" class="inline-flex items-center text-sm space-x-2 cursor-pointer">
                <input type="checkbox" id="togglePropertyCount" checked />
                <span>Property Count</span>
              </label>
            </div>
            <div class="p-1 rounded hover:bg-gray-50">
              <label for="togglePhone" class="inline-flex items-center text-sm space-x-2 cursor-pointer">
                <input type="checkbox" id="togglePhone" checked />
                <span>Phone</span>
              </label>
            </div>
            <div class="p-1 rounded hover:bg-gray-50">
              <label for="toggleEmail" class="inline-flex items-center text-sm space-x-2 cursor-pointer">
                <input type="checkbox" id="toggleEmail" checked />
                <span>Email</span>
              </label>
            </div>
            <div class="p-1 rounded hover:bg-gray-50">
              <label for="toggleStatus" class="inline-flex items-center text-sm space-x-2 cursor-pointer">
                <input type="checkbox" id="toggleStatus" checked />
                <span>Status</span>
              </label>
            </div>
            <div class="p-1 rounded hover:bg-gray-50">
              <label for="toggleBalance" class="inline-flex items-center text-sm space-x-2 cursor-pointer">
                <input type="checkbox" id="toggleBalance" checked />
                <span>Balance</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Batch Actions + Table -->
  <!-- Typically you'd do a POST form for batch updates, so the table checkboxes can be submitted. -->
  <form method="POST" action="/customers" id="batchForm">
    <!-- Hidden field for which action is being taken -->
    <input type="hidden" name="batchAction" id="batchActionField" />

    <!-- Batch Actions Bar (Req #12) -->
    <div
      id="batchActionsBar"
      class="hidden bg-blue-50 border border-blue-200 text-blue-700
             rounded-md px-4 py-3 flex items-center justify-between"
    >
      <span class="text-sm">
        <span id="selectedCount">0</span> selected
      </span>
      <div class="flex gap-3">
        <button
          type="submit"
          class="text-sm px-3 py-1.5 border border-blue-300 rounded-md
                 hover:bg-blue-100 transition"
          onclick="document.getElementById('batchActionField').value='sendEmail';"
        >
          Send Email
        </button>
        <button
          type="submit"
          class="text-sm px-3 py-1.5 border border-blue-300 rounded-md
                 hover:bg-blue-100 transition"
          onclick="document.getElementById('batchActionField').value='export';"
        >
          Export
        </button>
        <button
          type="submit"
          class="text-sm px-3 py-1.5 border border-blue-300 rounded-md
                 hover:bg-blue-100 transition"
          onclick="document.getElementById('batchActionField').value='markOverdue';"
        >
          Mark as Overdue
        </button>
      </div>
    </div>

    <!-- Table + Pagination Container -->
    <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-x-auto">

      <!-- Sticky Header Table (Req #5), Row Striping (Req #6) -->
      <table
        class="min-w-full border-collapse text-sm
               divide-y divide-gray-200
               sm:table-fixed
               sticky-header"
        id="customersTable"
      >
        <thead class="sticky top-0 z-10 bg-gradient-to-r from-gray-800 to-gray-700 
                      text-white uppercase text-xs tracking-wider">
          <tr>
            <!-- Checkbox Column -->
            <th class="py-3 px-4 text-left w-6">
              <input
                type="checkbox"
                id="selectAll"
                onclick="toggleSelectAll(this)"
              />
            </th>

            <!-- Name (as link) -->
            <th
              class="py-3 px-4 text-left cursor-pointer sort-header"
              data-column="name"
              onclick="sortTable('name')"
            >
              Name
              <span class="sort-indicator" id="nameSortIndicator"></span>
            </th>

            <!-- Property Count (as link) -->
            <th
              class="py-3 px-4 text-left cursor-pointer sort-header"
              data-column="propertyCount"
              onclick="sortTable('propertyCount')"
            >
              Property Count
              <span class="sort-indicator" id="propertyCountSortIndicator"></span>
            </th>

            <!-- Phone (desktop only) -->
            <th
              class="py-3 px-4 text-left hidden md:table-cell cursor-pointer sort-header"
              data-column="phone"
              onclick="sortTable('phone')"
              id="phoneHeader"
            >
              Phone
              <span class="sort-indicator" id="phoneSortIndicator"></span>
            </th>

            <!-- Email (desktop only) -->
            <th
              class="py-3 px-4 text-left hidden md:table-cell cursor-pointer sort-header"
              data-column="email"
              onclick="sortTable('email')"
              id="emailHeader"
            >
              Email
              <span class="sort-indicator" id="emailSortIndicator"></span>
            </th>

            <!-- Status -->
            <th
              class="py-3 px-4 text-left cursor-pointer sort-header"
              data-column="status"
              onclick="sortTable('status')"
              id="statusHeader"
            >
              Status
              <span class="sort-indicator" id="statusSortIndicator"></span>
            </th>

            <!-- Balance -->
            <th
              class="py-3 px-4 text-right cursor-pointer sort-header"
              data-column="balance"
              onclick="sortTable('balance')"
              id="balanceHeader"
            >
              Balance
              <span class="sort-indicator" id="balanceSortIndicator"></span>
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200" id="customersTableBody">
          <?php if (!empty($customers)): ?>
            <?php foreach ($customers as $cust): ?>
              <?php
                // For background color, assume we have $cust['status'] from the DB
                switch ($cust['status'] ?? '') {
                  case 'Active':
                    $statusClass = 'bg-green-100 text-green-700';
                    break;
                  case 'Inactive':
                    $statusClass = 'bg-yellow-100 text-yellow-700';
                    break;
                  case 'Overdue':
                    $statusClass = 'bg-red-100 text-red-700';
                    break;
                  default:
                    $statusClass = 'bg-gray-100 text-gray-700';
                }
              ?>
              <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100 transition">
                <td class="py-3 px-4">
                  <input 
                    type="checkbox" 
                    class="rowCheckbox" 
                    name="customerIds[]" 
                    value="<?= (int)$cust['customer_id'] ?>"
                  />
                </td>
                <td class="py-3 px-4">
                  <a 
                    href="/staff/customers/<?= $cust['customer_id'] ?>"
                    class="text-blue-600 underline hover:text-blue-800"
                    data-column="name"
                  >
                    <?= htmlspecialchars($cust['name'] ?? 'Unnamed') ?>
                  </a>
                </td>
                <td class="py-3 px-4">
                  <a 
                    href="/customers/<?= $cust['customer_id'] ?>/properties"
                    class="text-blue-600 underline hover:text-blue-800"
                    data-column="propertyCount"
                  >
                    <?= (int)$cust['propertyCount'] ?>
                  </a>
                </td>
                <td class="py-3 px-4 hidden md:table-cell" data-column="phone">
                  <?= htmlspecialchars($cust['phone'] ?? '') ?>
                </td>
                <td class="py-3 px-4 hidden md:table-cell" data-column="email">
                  <?= htmlspecialchars($cust['email'] ?? '') ?>
                </td>
                <td class="py-3 px-4" data-column="status">
                  <span class="inline-block px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                    <?= htmlspecialchars($cust['status'] ?? 'Unknown') ?>
                  </span>
                </td>
                <td class="py-3 px-4 text-right" data-column="balance">
                  $<?= number_format($cust['balance'] ?? 0, 2) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="py-3 px-4 text-center text-gray-500">
                No customers found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination (Req #3) -->
    <div class="flex flex-col sm:flex-row items-center justify-between mt-2 gap-2">
      <!-- Rows per page -->
      <div class="flex items-center space-x-2">
        <label for="pageSize" class="text-sm">Rows per page:</label>
        <select
          id="pageSize"
          class="border border-gray-300 rounded-md px-2 py-1 text-sm"
        >
          <option value="10"  <?php if (($pageSize ?? 10) == 10) echo 'selected';?>>10</option>
          <option value="25"  <?php if (($pageSize ?? 10) == 25) echo 'selected';?>>25</option>
          <option value="50"  <?php if (($pageSize ?? 10) == 50) echo 'selected';?>>50</option>
          <option value="100" <?php if (($pageSize ?? 10) == 100) echo 'selected';?>>100</option>
        </select>
      </div>

      <!-- Jump to page -->
      <div class="flex items-center space-x-2 text-sm">
        <button
          id="prevPageBtn"
          class="px-3 py-1 border border-gray-300 rounded-md
                 hover:bg-gray-100 transition"
        >
          Prev
        </button>
        <span>
          Page 
          <span id="currentPage">
            <?= (int)($currentPage ?? 1) ?>
          </span> 
          of 
          <span id="totalPages">
            <?= (int)($totalPages ?? 1) ?>
          </span>
        </span>
        <button
          id="nextPageBtn"
          class="px-3 py-1 border border-gray-300 rounded-md
                 hover:bg-gray-100 transition"
        >
          Next
        </button>
        <div>
          <label for="jumpToPage" class="sr-only">Jump to page</label>
          <input
            type="number"
            id="jumpToPage"
            min="1"
            class="w-16 border border-gray-300 rounded-md px-2 py-1 text-sm"
            placeholder="Page #"
          />
        </div>
        <button
          id="goToPageBtn"
          class="px-3 py-1 border border-gray-300 rounded-md 
                 hover:bg-gray-100 transition"
        >
          Go
        </button>
      </div>
    </div>
  </form>

<!-- Possible Card Layout for Very Small Screens (Req #11) -->
<style>
  @media (max-width: 400px) {
    table, thead, tbody, th, td, tr {
      display: block;
    }
    thead {
      display: none;
    }
    tr {
      margin-bottom: 1rem;
      border: 1px solid #e5e7eb;
      border-radius: 0.375rem;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      padding: 0.5rem;
    }
    td {
      border: none !important;
      display: flex;
      justify-content: space-between;
      margin: 0.25rem 0;
    }
    td::before {
      font-weight: 600;
      text-transform: capitalize;
      /* content: attr(data-column); // If you want to display e.g. "Name: ..." */
    }
  }
  .sticky-header thead th {
    position: sticky;
    top: 0;
  }
</style>

<script>
  // All the same JS for search, sorting, pagination, etc.
  // (unchanged from your snippet)

  let sortOrder = {};
  let debounceTimer;
  const DEBOUNCE_DELAY = 300;

  const searchInput   = document.getElementById('searchInput');
  const statusFilter  = document.getElementById('statusFilter');
  const pageSizeSelect= document.getElementById('pageSize');
  const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
  const selectAllCheckbox = document.getElementById('selectAll');

  [searchInput, statusFilter].forEach(el => {
    if (!el) return;
    el.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(applyFilters, DEBOUNCE_DELAY);
    });
    el.addEventListener('change', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(applyFilters, DEBOUNCE_DELAY);
    });
  });

  // no-op for demonstration, or you can do client-side filtering
  function applyFilters() {
    // In a purely server-side approach, you'd do a form submit or window.location = ...
    // In a client-only approach, you'd filter the rows. 
    // For now, let's do nothing or keep your client filtering logic if you want
  }

  // highlightSearch, clearHighlight... etc. from your snippet
  // sortTable, pagination, etc. remain the same.

  function toggleSelectAll(source) {
    rowCheckboxes.forEach(cb => {
      cb.checked = source.checked;
    });
    updateBatchActionsBar();
  }

  function updateBatchActionsBar() {
    const selectedCount = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
    document.getElementById('selectedCount').textContent = selectedCount;
    const bar = document.getElementById('batchActionsBar');
    bar.classList.toggle('hidden', selectedCount === 0);
  }

  rowCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateBatchActionsBar);
  });

  // Example: on page load
  window.addEventListener('DOMContentLoaded', () => {
    applyFilters();    // optional
    renderTableByPage(); // your existing pagination function, if you keep it
  });

  // The rest of your existing JS (sortTable, renderTableByPage, etc.) can remain
  // as in your original snippet.
</script>
