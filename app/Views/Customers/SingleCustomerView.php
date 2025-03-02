<!-- 1) Container Layout -->
<div class="mt-6 px-4 grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
  <!-- Left Card (Customer Info) -->
  <div class="md:col-span-1 space-y-6">
    <div class="bg-white rounded-lg shadow p-4 w-full max-w-md">
      <div class="flex items-center mb-4">
        <i class="fa fa-address-card text-xl text-gray-700 mr-3"></i>
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Customer Info</h2>
        </div>
      </div>

      <ul class="space-y-3 text-gray-700 text-sm">
        <!-- Name -->
        <li class="flex items-center">
          <i class="fa fa-user mr-2 text-gray-500 w-5 flex-shrink-0"></i>
          <span>
            <span class="font-semibold">Name:</span>
            <?= htmlspecialchars($customer['display_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
          </span>
        </li>
        <!-- Customer Type -->
        <li class="flex items-center">
          <i class="fa fa-tags mr-2 text-gray-500 w-5 flex-shrink-0"></i>
          <span>
            <span class="font-semibold">Customer Type:</span>
            <?= htmlspecialchars($customer['customer_type'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
          </span>
        </li>
        <!-- Email -->
        <li class="flex items-start">
          <i class="fa fa-envelope mr-2 text-gray-500 w-5 flex-shrink-0 mt-0.5"></i>
          <div>
            <span class="font-semibold">Email:</span>
            <?php if (!empty($customer['primary_email'])): ?>
              <span class="ml-1">
                <?= htmlspecialchars($customer['primary_email'], ENT_QUOTES, 'UTF-8') ?>
                <?php if (!empty($customer['email_count']) && $customer['email_count'] > 1): ?>
                  <br>
                  <a
                    href="/customers/view-emails?id=<?= (int)$customer['customer_id'] ?>"
                    class="text-blue-600 hover:underline text-xs mt-1 inline-block"
                  >
                    View all emails
                  </a>
                <?php endif; ?>
              </span>
            <?php else: ?>
              <span class="flex items-center text-red-600">
                <i class="fa fa-exclamation-circle mr-1"></i>
                No primary email on file.
                <a
                  href="/customers/add-email?id=<?= (int)$customer['customer_id'] ?>"
                  class="text-blue-600 hover:underline ml-2"
                >
                  Add Now
                </a>
              </span>
            <?php endif; ?>
          </div>
        </li>
        <!-- Phone -->
        <li class="flex items-start">
          <i class="fa fa-phone mr-2 text-gray-500 w-5 flex-shrink-0 mt-0.5"></i>
          <div>
            <span class="font-semibold">Phone:</span>
            <?php if (!empty($customer['primary_phone'])): ?>
              <span class="ml-1">
                <?= htmlspecialchars($customer['primary_phone'], ENT_QUOTES, 'UTF-8') ?>
                <?php if (!empty($customer['phone_count']) && $customer['phone_count'] > 1): ?>
                  <br>
                  <a
                    href="/customers/view-phones?id=<?= (int)$customer['customer_id'] ?>"
                    class="text-blue-600 hover:underline text-xs mt-1 inline-block"
                  >
                    View all phones
                  </a>
                <?php endif; ?>
              </span>
            <?php else: ?>
              <span class="flex items-center text-red-600">
                <i class="fa fa-exclamation-circle mr-1"></i>
                No primary phone on file.
                <a
                  href="/customers/add-phone?id=<?= (int)$customer['customer_id'] ?>"
                  class="text-blue-600 hover:underline ml-2"
                >
                  Add Now
                </a>
              </span>
            <?php endif; ?>
          </div>
        </li>
        <!-- Billing Address -->
        <li class="flex items-start">
          <i class="fa fa-home mr-2 text-gray-500 w-5 flex-shrink-0 mt-0.5"></i>
          <div>
            <span class="font-semibold">Billing Address:</span>
            <?php if (!empty($customer['primary_billing'])): ?>
              <span class="ml-1">
                <?= htmlspecialchars($customer['primary_billing'], ENT_QUOTES, 'UTF-8') ?>
                <?php if (!empty($customer['billing_count']) && $customer['billing_count'] > 1): ?>
                  <br>
                  <a
                    href="/customers/view-addresses?id=<?= (int)$customer['customer_id'] ?>"
                    class="text-blue-600 hover:underline text-xs mt-1 inline-block"
                  >
                    View all billing addresses
                  </a>
                <?php endif; ?>
              </span>
            <?php else: ?>
              <span class="flex items-center text-red-600">
                <i class="fa fa-exclamation-circle mr-1"></i>
                No primary billing address on file.
                <a
                  href="/customers/add-address?id=<?= (int)$customer['customer_id'] ?>&type=Billing"
                  class="text-blue-600 hover:underline ml-2"
                >
                  Add Now
                </a>
              </span>
            <?php endif; ?>
          </div>
        </li>
      </ul>
    </div>

    <!-- Example of small cards under the info card -->
    <div class="grid grid-cols-1 gap-4">
   <!-- BILLING SUMMARY CARD (Billion-Dollar Company Example) -->
<div class="bg-white rounded-lg shadow p-4 w-full max-w-md">
  <!-- Header Row -->
  <div class="flex items-center justify-between mb-3">
    <div class="flex items-center space-x-2">
      <i class="fa fa-credit-card text-xl text-gray-700"></i>
      <h3 class="text-lg font-bold text-gray-800">Billing Summary</h3>
    </div>
    <!-- Example “Overdue” Badge (conditional) -->
    <span
      class="ml-2 inline-block px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"
    >
      Overdue
    </span>
  </div>

  <!-- Main Stats Row -->
  <div class="mb-4 flex items-center justify-between">
    <!-- Left: Balance + Next Due -->
    <div>
      <p class="text-gray-600 text-sm">Current Balance</p>
      <p class="text-2xl font-bold text-gray-900 leading-none">
        $3,542.18
      </p>
      <p class="text-sm text-gray-500 mt-1">
        Due Date: <strong class="text-red-600">Aug 15, 2025</strong>
      </p>
      <p class="text-xs text-gray-500 mt-2">
        Last Payment: 7/20/2025 ($500)
      </p>
    </div>
    <!-- Right: Payment Controls -->
    <div class="text-right">
      <!-- Make Payment Button -->
      <button
        class="bg-blue-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-blue-500 focus:outline-none"
      >
        Pay Now
      </button>
      <button
  class="border border-blue-600 bg-transparent text-blue-600 text-sm px-3 py-2 rounded-lg hover:bg-blue-50 focus:outline-none mt-4 transition-colors duration-200"
>
  Enroll AutoPay
</button>
      
    </div>
  </div>

  <!-- Quick Stats / Additional Info -->
  <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
    <div>
      <p class="text-gray-600">Open Invoices</p>
      <p class="font-semibold text-gray-800">3</p>
    </div>
    <div>
      <p class="text-gray-600">Total Invoiced</p>
      <p class="font-semibold text-gray-800">$58,920.00</p>
    </div>
    <div>
      <p class="text-gray-600">Auto-Pay</p>
      <p class="font-semibold text-gray-800">Disabled</p>
    </div>
    <div>
      <p class="text-gray-600">Last Statement</p>
      <p class="font-semibold text-gray-800">Jul 31, 2025</p>
    </div>
  </div>

  <!-- Actions / Links Row -->
  <div class="flex items-center justify-between mt-2 space-x-2">
    <!-- View All Invoices -->
    <a
      href="#"
      class="bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-md hover:bg-gray-200 flex items-center space-x-1"
    >
      <i class="fa fa-file-invoice-dollar"></i>
      <span>View Invoices</span>
    </a>

    <!-- Download Statement -->
    <a
      href="#"
      class="bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-md hover:bg-gray-200 flex items-center space-x-1"
    >
      <i class="fa fa-download"></i>
      <span>Download Statement</span>
    </a>

  </div>
</div>

<!-- COMMUNICATION PREFERENCES CARD -->
<div class="bg-white rounded-lg shadow p-4">
  <div class="flex items-center mb-2">
    <i class="fa fa-envelope text-xl text-gray-700 mr-2"></i>
    <h3 class="text-lg font-bold text-gray-800">Communication Preferences</h3>
  </div>

  <!-- Quick Info Row -->
  <div class="text-sm text-gray-600 space-y-1">
    <p>
      <span class="font-semibold">Email:</span> Enabled
      <span class="text-xs text-gray-400">(Marketing & Invoices)</span>
    </p>
    <p>
      <span class="font-semibold">SMS:</span> Enabled
      <span class="text-xs text-gray-400">(Account Updates Only)</span>
    </p>
    <p>
      <span class="font-semibold">Phone Calls:</span> Disabled
    </p>
    <p>
      <span class="font-semibold">Do Not Disturb:</span> Off
    </p>
  </div>

  <!-- Edit Button -->
  <div class="mt-3">
    <a
      href="#"
      class="inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-500 transition"
    >
      Edit Preferences
    </a>
  </div>
</div>

<!-- CUSTOMER NOTES CARD -->
<div class="bg-white rounded-lg shadow p-4">
  <div class="flex items-center mb-2">
    <i class="fa fa-sticky-note text-xl text-gray-700 mr-2"></i>
    <h3 class="text-lg font-bold text-gray-800">Customer Notes</h3>
  </div>

  <!-- Example of a short snippet of the most recent note -->
  <div class="text-sm text-gray-600 mb-2">
    <p class="mb-1 font-semibold">
      <span class="text-gray-800">2025-07-15:</span> “Spoke with John about upcoming service schedule...”
    </p>
    <p class="text-gray-500">
      <em>See all notes for full details.</em>
    </p>
  </div>

  <!-- Action Buttons -->
  <div class="flex space-x-2 text-sm">
    <a
      href="#"
      class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 flex items-center space-x-1"
    >
      <i class="fa fa-eye"></i>
      <span>View All Notes</span>
    </a>
    <a
      href="#"
      class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 flex items-center space-x-1"
    >
      <i class="fa fa-plus"></i>
      <span>Add New Note</span>
    </a>
  </div>
</div>

    </div>
  </div>

  <!-- Right Card (Tabs + Content) -->
  <div class="md:col-span-3 bg-white rounded-lg shadow-lg">
    <!-- Custom Tabs -->
    <div class="bg-gray-800 text-white px-4 py-3 rounded-t-lg">
      <div class="flex justify-start space-x-6">
        <a
          href="#overview"
          class="custom-tab active text-sm font-semibold border-b-2 border-blue-500 pb-1 hover:text-blue-400"
        >
          Overview
        </a>
        <a
          href="#properties"
          class="custom-tab text-sm font-semibold border-b-2 border-transparent pb-1 hover:text-blue-400"
        >
          Properties
        </a>
        <a
          href="#details"
          class="custom-tab text-sm font-semibold border-b-2 border-transparent pb-1 hover:text-blue-400"
        >
          Details
        </a>
        <a
          href="#activity"
          class="custom-tab text-sm font-semibold border-b-2 border-transparent pb-1 hover:text-blue-400"
        >
          Activity
        </a>
      </div>
    </div>

    <!-- Tab Content -->
    <div class="p-4">
      <!-- Overview Section -->
      <div id="overview">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Overview</h2>
        <p class="text-gray-600">
          This is the overview content. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>
        <form action="/customers/impersonate" method="POST">
  <!-- hidden input for which customer to impersonate -->
  <input type="hidden" name="customer_id" value="25" />
  <button type="submit">Impersonate this Customer</button>
</form>

      </div>

<!-- PROPERTIES TAB (Vanilla JS version, no Alpine) -->
<div id="properties" class="hidden">
  <!-- Header Row: Title, Search, Add New -->
  <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
    <div>
      <div class="flex items-center mb-1">
        <i class="fa fa-home text-xl text-gray-700 mr-2"></i>
        <h2 class="text-2xl font-bold text-gray-800">Properties</h2>
      </div>
      <p class="text-gray-600 text-sm">
        Manage and view all properties associated with this customer.
      </p>
    </div>

    <div class="flex items-center space-x-2 mt-4 md:mt-0">
      <!-- Search Input (placeholder only) -->
      <div class="relative">
        <input
          type="text"
          placeholder="Search properties..."
          class="border border-gray-300 rounded-lg p-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <!-- Optional search icon inside input -->
        <i class="fa fa-search absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
      </div>

      <!-- Add New Button (opens modal) -->
      <button
        id="openModalBtn"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition focus:outline-none flex items-center space-x-1"
      >
        <svg
          class="w-4 h-4 fill-current inline-block"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M12 5v14M5 12h14"></path>
        </svg>
        <span>Add Property</span>
      </button>
    </div>
  </div>

  <!-- NO PROPERTIES: Show a friendly message if array is empty -->
  <div
    id="propertiesNone"
    class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg"
    style="display: none;"
  >
    <div class="flex items-center">
      <i class="fa fa-exclamation-circle text-yellow-400 mr-2"></i>
      <p class="text-yellow-700 text-sm">
        <strong>No properties found.</strong> Add your first property now!
      </p>
    </div>
  </div>

  <!-- PROPERTIES EXIST -->
  <div id="propertiesExist" style="display: none;">
    <!-- Sub-Header: Count or Other Info -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
      <p class="text-gray-600 text-sm">
        <span class="font-bold text-gray-800">Total Properties:</span>
        <span id="propCount"></span>
      </p>
    </div>

    <!-- Map Placeholder -->
    <div class="relative w-full h-64 bg-gray-100 rounded-lg overflow-hidden mt-4">
      <div class="absolute inset-0 flex items-center justify-center text-gray-500">
        <p class="text-sm">
          <i class="fa fa-map-marker-alt"></i>
          Map with property boundaries/pins goes here
        </p>
      </div>
    </div>

    <!-- Property Cards Container (full-width cards) -->
    <div
      class="grid grid-cols-1 gap-4 mt-4"
      id="propertiesList"
    >
      <!-- JavaScript will insert card elements here -->
    </div>
  </div>
</div>
<!-- End of #properties tab -->


      <!-- Details Section -->
      <div id="details" class="hidden">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Details</h2>
        <p class="text-gray-600">
          This is the details content. Praesent euismod sem a purus laoreet, nec fermentum est porta.
        </p>
      </div>

      <!-- Activity Section -->
      <div id="activity" class="hidden">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Activity</h2>
        <ul class="space-y-2">
          <li class="p-3 bg-gray-50 rounded-md">
            <p class="text-gray-600 text-sm">
              <span class="font-semibold">2025-01-01:</span> Customer record created.
            </p>
          </li>
          <li class="p-3 bg-gray-50 rounded-md">
            <p class="text-gray-600 text-sm">
              <span class="font-semibold">2025-01-02:</span> Welcome email sent.
            </p>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- MODAL BACKDROP (Vanilla JS) -->
<div
  id="propertyModalBackdrop"
  class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
  style="display: none;"
>
  <!-- MODAL CONTENT -->
  <div
    id="propertyModal"
    class="bg-white w-full max-w-4xl rounded-lg shadow-lg p-6 relative"
    style="display: none;"
  >
    <h2 class="text-xl font-bold text-gray-800 mb-4">
      <i class="fa fa-home mr-2 text-blue-500"></i>
      Add New Property
    </h2>

    <!-- The form posts to your property controller's create action -->
    <form method="POST" action="/properties/create">
      <!-- Hidden field for customer_id -->
      <input type="hidden" name="customer_id" id="prop_customer_id" />

      <!-- Address Line 1 -->
      <div class="mb-4">
        <label for="addrLine1" class="block text-gray-700 font-semibold mb-1">Address Line 1</label>
        <input
          type="text"
          id="prop_addrLine1"
          name="addrLine1"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          required
        />
      </div>

      <!-- Address Line 2 -->
      <div class="mb-4">
        <label for="addrLine2" class="block text-gray-700 font-semibold mb-1">Address Line 2</label>
        <input
          type="text"
          id="prop_addrLine2"
          name="addrLine2"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
      </div>

      <!-- City, State, Zip -->
      <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- City -->
        <div>
          <label for="city" class="block text-gray-700 font-semibold mb-1">City</label>
          <input
            type="text"
            id="prop_city"
            name="city"
            class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
        <!-- State -->
        <div>
          <label for="state" class="block text-gray-700 font-semibold mb-1">State</label>
          <input
            type="text"
            id="prop_state"
            name="state"
            class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
        <!-- Zip -->
        <div>
          <label for="zip" class="block text-gray-700 font-semibold mb-1">Zip</label>
          <input
            type="text"
            id="prop_zip"
            name="zip"
            class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
      </div>

      <!-- Type -->
      <div class="mb-4">
        <label for="type" class="block text-gray-700 font-semibold mb-1">Type</label>
        <select
          id="prop_type"
          name="type"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option value="Residential">Residential</option>
          <option value="Commercial">Commercial</option>
          <option value="Industrial">Industrial</option>
        </select>
      </div>

      <!-- Notes -->
      <div class="mb-4">
        <label for="notes" class="block text-gray-700 font-semibold mb-1">Notes</label>
        <textarea
          id="prop_notes"
          name="notes"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          rows="3"
        ></textarea>
      </div>

      <!-- Modal Actions -->
      <div class="flex justify-end space-x-2 mt-6">
        <button
          type="button"
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition"
          id="cancelModalBtn"
        >
          Cancel
        </button>
        <button
          type="submit"
          class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition"
        >
          Add Property
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Tab Toggle Script (unchanged) -->
<script>
  // Grab all the tab links and the sections
  const tabLinks = document.querySelectorAll('.custom-tab');
  const sections = {
    overview: document.getElementById('overview'),
    properties: document.getElementById('properties'),
    details: document.getElementById('details'),
    activity: document.getElementById('activity')
  };

  // Show/hide tab function
  function showTab(tabName) {
    tabLinks.forEach(link => {
      link.classList.remove('active', 'border-blue-500');
      link.classList.add('border-transparent');
    });
    Object.values(sections).forEach(sec => sec.classList.add('hidden'));

    const activeLink = document.querySelector(`.custom-tab[href="#${tabName}"]`);
    if (activeLink) {
      activeLink.classList.add('active', 'border-blue-500');
      activeLink.classList.remove('border-transparent');
    }
    if (sections[tabName]) {
      sections[tabName].classList.remove('hidden');
    }
  }

  // On page load, pick the correct tab from URL or default
  const urlParams = new URLSearchParams(window.location.search);
  let activeTab = urlParams.get('tab') || 'overview';
  showTab(activeTab);

  tabLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const targetTab = this.getAttribute('href').substring(1);
      showTab(targetTab);
    });
  });
</script>

<!-- Properties + Modal Script -->
<script>
  // We'll get the PHP data as a JSON string
  const propertiesData = JSON.parse('<?= json_encode($properties ?? []) ?>');
  const customerId = <?= (int)$customer['customer_id'] ?>;

  // DOM elements for properties section
  const propertiesSection    = document.getElementById('properties');
  const propertiesNone       = document.getElementById('propertiesNone');
  const propertiesExist      = document.getElementById('propertiesExist');
  const propertiesList       = document.getElementById('propertiesList');
  const propCountSpan        = document.getElementById('propCount');

  // DOM elements for modal
  const propertyModalBackdrop= document.getElementById('propertyModalBackdrop');
  const propertyModal        = document.getElementById('propertyModal');
  const openModalBtn         = document.getElementById('openModalBtn');
  const cancelModalBtn       = document.getElementById('cancelModalBtn');

  // Form fields in the modal
  const prop_customer_id     = document.getElementById('prop_customer_id');
  const prop_addrLine1       = document.getElementById('prop_addrLine1');
  const prop_addrLine2       = document.getElementById('prop_addrLine2');
  const prop_city            = document.getElementById('prop_city');
  const prop_state           = document.getElementById('prop_state');
  const prop_zip             = document.getElementById('prop_zip');
  const prop_type            = document.getElementById('prop_type');
  const prop_notes           = document.getElementById('prop_notes');

  // On DOM load, fill out the properties tab
  document.addEventListener('DOMContentLoaded', () => {
    // If no properties, show the 'no properties' message
    if (propertiesData.length === 0) {
      propertiesNone.style.display = 'block';
      propertiesExist.style.display = 'none';
    } else {
      // Show the property list
      propertiesNone.style.display = 'none';
      propertiesExist.style.display = 'block';

      // Show how many
      propCountSpan.textContent = propertiesData.length;

      // Build each property card in the #propertiesList
      propertiesData.forEach((prop, index) => {
        const card = buildPropertyCard(prop);
        propertiesList.appendChild(card);
      });
    }

    // The 'openModalBtn' opens the modal
    openModalBtn.addEventListener('click', () => {
      openModal();
    });

    // The 'cancel' button closes modal
    cancelModalBtn.addEventListener('click', () => {
      closeModal();
    });
  });

  function buildPropertyCard(prop) {
  // Outer container
  const div = document.createElement('div');
  div.className = 'bg-white shadow rounded-lg p-4 w-full flex flex-col';

  // Top row: Title & 3-dot menu
  const topRow = document.createElement('div');
  topRow.className = 'flex items-start justify-between';

  // Left column: address lines
  const leftCol = document.createElement('div');

  // Create a link element for the card title with hover underline
  const link = document.createElement('a');
  link.className = 'text-xl font-bold text-gray-800 hover:underline';
  // Adjust the href below as needed; using prop.id or prop.property_id if available
  link.href = `/properties/view?id=${prop.property_id || prop.id || ''}`;
  link.textContent = prop.addrLine1 || '(No address line 1)';

  // Append the link instead of plain text title
  leftCol.appendChild(link);

  // Create subtitle element for additional location info
  const subtitle = document.createElement('p');
  subtitle.className = 'text-sm text-gray-500';
  subtitle.textContent = `${prop.city || ''}, ${prop.state || ''} ${prop.zip || ''}`.trim();

  leftCol.appendChild(subtitle);

  // Right column: 3-dot menu (unchanged)
  const rightCol = document.createElement('div');
  rightCol.className = 'relative';
  rightCol.innerHTML = `
    <button class="text-gray-500 focus:outline-none ml-2">
      <i class="fa fa-ellipsis-v"></i>
    </button>
  `;

  // Combine left and right columns
  topRow.appendChild(leftCol);
  topRow.appendChild(rightCol);

  // Next row: type badge
  const typeSpan = document.createElement('span');
  typeSpan.className = 'mt-2 inline-block text-xs font-semibold px-2 py-1 rounded-full';
  let bgClass = 'bg-green-100 text-green-800';
  if (prop.type === 'Commercial') bgClass = 'bg-blue-100 text-blue-800';
  if (prop.type === 'Industrial') bgClass = 'bg-purple-100 text-purple-800';
  typeSpan.classList.add(...bgClass.split(' '));
  typeSpan.textContent = (prop.type || 'Residential') + ' Property';

  // Next row: notes
  const notesDiv = document.createElement('div');
  notesDiv.className = 'mt-2 text-sm text-gray-600';
  notesDiv.textContent = prop.notes || '';

  // Assemble the card
  div.appendChild(topRow);
  div.appendChild(typeSpan);
  div.appendChild(notesDiv);

  return div;
}

  function openModal() {
    // Reset the form to blank if desired
    prop_customer_id.value = customerId;
    prop_addrLine1.value   = '';
    prop_addrLine2.value   = '';
    prop_city.value        = '';
    prop_state.value       = '';
    prop_zip.value         = '';
    prop_type.value        = 'Residential';
    prop_notes.value       = '';

    propertyModalBackdrop.style.display = 'flex';
    propertyModal.style.display         = 'block';
  }

  function closeModal() {
    propertyModalBackdrop.style.display = 'none';
    propertyModal.style.display         = 'none';
  }
</script>
