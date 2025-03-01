<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Customer View</title>
  <!-- Tailwind CSS via CDN (for demo) -->
  <link
    href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7/dist/tailwind.min.css"
    rel="stylesheet"
  />
  <!-- Alpine.js for reactivity -->
  <script
    defer
    src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"
  ></script>
</head>
<body class="bg-gray-100">

<!-- Parent container: Note the 'items-start' class -->
<div class="mx-auto mt-6 px-4 grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
  <!-- Left Card -->
  <div class="md:col-span-1 space-y-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Customer Info</h2>
      <p class="text-gray-600"><span class="font-semibold">Name:</span> John Doe</p>
      <p class="text-gray-600"><span class="font-semibold">Customer Type:</span> Residential</p>
      <p class="text-gray-600"><span class="font-semibold">Title:</span> Mr.</p>
      <p class="text-gray-600 mt-4">
        <span class="font-semibold">Email:</span> john.doe@example.com
      </p>
      <p class="text-gray-600">
        <span class="font-semibold">Phone:</span> (123) 456-7890
      </p>
      <p class="text-gray-600 mt-4">
        <span class="font-semibold">Address:</span> 123 Main St, Apt 4B, Springfield, IL, 62704
      </p>
    </div>
    <!-- 3 Small Cards Below Fixed Info -->
    <div class="grid grid-cols-1 gap-4">
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-bold text-gray-800">Card 1</h3>
        <p class="text-gray-600 text-sm">Additional info 1.</p>
      </div>
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-bold text-gray-800">Card 2</h3>
        <p class="text-gray-600 text-sm">Additional info 2.</p>
      </div>
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-bold text-gray-800">Card 3</h3>
        <p class="text-gray-600 text-sm">Additional info 3.</p>
      </div>
    </div>
  </div>

  <!-- Right Card -->
  <div class="md:col-span-3 bg-white rounded-lg shadow-lg">
    <!-- Custom Tabs Section -->
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
      </div>

      <!-- PROPERTIES TAB -->
      <div
        id="properties"
        class="hidden"
        x-data="{
          openModal: false,
          // Current new property form
          newProperty: {
          
            address: '',
            type: 'Residential',
            lines: '',
            notes: ''
          },
          // All properties
          properties: [],
          // Check if we have at least one property
          get hasProperties() {
            return this.properties.length > 0;
          },
          // Clears the form after submission
          resetForm() {
            this.newProperty = {
              address: '',
              type: 'Residential',
              lines: '',
              notes: ''
            }
          },
          // Add the new property
          addProperty() {
            // In a real app, you might do an API call here; for now, just push
            this.properties.push({...this.newProperty});
            this.resetForm();
            this.openModal = false;
          }
        }"
      >
        <!-- Header Row: Title, Search, Add New -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
          <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Properties</h2>
            <p class="text-gray-600 text-sm">
              View, search, and manage your property listings below.
            </p>
          </div>
          <div class="flex items-center space-x-2">
            <!-- Search Input (placeholder only) -->
            <input
              type="text"
              placeholder="Search properties..."
              class="border border-gray-300 rounded-lg p-2 text-sm focus:outline-none 
                     focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />

            <!-- Add New Button (opens modal) -->
            <button
              @click="openModal = true"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 
                     transition focus:outline-none flex items-center space-x-1"
            >
              <!-- Plus Icon -->
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
              <span>Add New</span>
            </button>
          </div>
        </div>

        <!-- NO PROPERTIES: Show a friendly message if the array is empty -->
        <template x-if="!hasProperties">
          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mt-4">
            <p class="text-yellow-700">
              <strong>No properties found.</strong> Add your first property now.
            </p>
          </div>
        </template>

        <!-- PROPERTIES EXIST: Show map + property cards if we have any -->
        <template x-if="hasProperties">
          <div class="space-y-4">
            <!-- Sub-Header: Count or Other Info -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
              <p class="text-gray-600 text-sm">
                <span class="font-bold text-gray-800">Total Properties:</span>
                <span x-text="properties.length"></span>
              </p>
            </div>

            <!-- Map Container with Pins (Placeholder) -->
            <div class="relative w-full h-64 bg-gray-100 rounded-lg overflow-hidden">
              <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                <!-- Replace with a real map library (e.g. Leaflet, Google Maps, etc.) -->
                <p class="text-sm">
                  Map with property boundaries or pins goes here
                </p>
              </div>
            </div>

<!-- Property Cards -->
<div class="grid grid-cols-1 gap-4">
  <!-- Loop through each property in the array -->
  <template x-for="(property, index) in properties" :key="index">
    <div class="bg-white shadow rounded-lg p-4 w-full">
      <div class="flex items-center justify-between">
        <div>
          <!-- Address as a larger title -->
          <h3 class="text-xl font-bold text-gray-800" x-text="property.addrLine1"></h3>
          <!-- City, State, Zip as a subtitle -->
          <p class="text-sm text-gray-500" 
             x-text="property.city + ', ' + property.state + ' ' + property.zip">
          </p>
        </div>
        <!-- Dropdown Menu -->
        <div x-data="{ open: false }" class="relative">
          <!-- Three-dot toggle button -->
          <button @click="open = !open" class="text-gray-500 focus:outline-none">
            <i class="fa fa-ellipsis-v"></i>
          </button>
          <!-- Dropdown options -->
          <div x-show="open" @click.away="open = false" 
               class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-10">
            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
              <i class="fa fa-briefcase mr-2"></i> New Job
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
              <i class="fa fa-file-invoice-dollar mr-2"></i> New Estimate
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
              <i class="fa fa-edit mr-2"></i> Edit Property
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
              <i class="fa fa-trash mr-2"></i> Delete Property
            </a>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>

          </div>
        </template>

<!-- MODAL BACKDROP -->
<div 
  class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" 
  x-show="openModal" 
  x-transition
>
  <!-- MODAL CONTENT -->
  <div 
    class="bg-white w-full max-w-4xl rounded-lg shadow-lg p-6 relative"
    x-trap.inert.noscroll="openModal"  
    x-transition
  >
    <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Property</h2>

    <!-- The form posts to your property controller's create action -->
    <form method="POST" action="/properties/create">
      <!-- Hidden field for customer_id -->
      <input 
        type="hidden" 
        name="customer_id" 
        x-model="newProperty.customer_id"
        value="<?= htmlspecialchars($customer['customer_id']) ?>"
        />

      <!-- Address Line 1 -->
      <div class="mb-4">
        <label for="addrLine1" class="block text-gray-700 font-semibold mb-1">Address Line 1</label>
        <input 
          type="text" 
          id="addrLine1"
          name="addrLine1"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          x-model="newProperty.addrLine1"
          required
        />
      </div>

      <!-- Address Line 2 -->
      <div class="mb-4">
        <label for="addrLine2" class="block text-gray-700 font-semibold mb-1">Address Line 2</label>
        <input 
          type="text" 
          id="addrLine2"
          name="addrLine2"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          x-model="newProperty.addrLine2"
        />
      </div>

      <!-- City, State, Zip -->
      <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- City -->
        <div>
          <label for="city" class="block text-gray-700 font-semibold mb-1">City</label>
          <input 
            type="text" 
            id="city"
            name="city"
            class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            x-model="newProperty.city"
            required
          />
        </div>
        <!-- State -->
        <div>
          <label for="state" class="block text-gray-700 font-semibold mb-1">State</label>
          <input 
            type="text" 
            id="state"
            name="state"
            class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            x-model="newProperty.state"
            required
          />
        </div>
        <!-- Zip -->
        <div>
          <label for="zip" class="block text-gray-700 font-semibold mb-1">Zip</label>
          <input 
            type="text" 
            id="zip"
            name="zip"
            class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            x-model="newProperty.zip"
            required
          />
        </div>
      </div>

      <!-- Type -->
      <div class="mb-4">
        <label for="type" class="block text-gray-700 font-semibold mb-1">Type</label>
        <select 
          id="type"
          name="type"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          x-model="newProperty.type"
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
          id="notes"
          name="notes"
          class="border rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          rows="3"
          x-model="newProperty.notes"
        ></textarea>
      </div>

      <!-- Modal Actions -->
      <div class="flex justify-end space-x-2 mt-6">
        <button 
          type="button"
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition"
          @click="openModal = false"
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

<!-- Tab Toggle Script -->
<script>
  // Grab all the tab links and the sections
  const tabLinks = document.querySelectorAll('.custom-tab');
  const sections = {
    overview:    document.getElementById('overview'),
    properties:  document.getElementById('properties'),
    details:     document.getElementById('details'),
    activity:    document.getElementById('activity')
  };

  // Function to show the correct tab and hide others
  function showTab(tabName) {
    // Clear any 'active' states from the links
    tabLinks.forEach(link => {
      link.classList.remove('active', 'border-blue-500');
      link.classList.add('border-transparent');
    });

    // Hide all tab content sections
    Object.values(sections).forEach(sec => sec.classList.add('hidden'));

    // Mark the correct link as active & show the correct content
    const activeLink = document.querySelector(`.custom-tab[href="#${tabName}"]`);
    if (activeLink) {
      activeLink.classList.add('active', 'border-blue-500');
      activeLink.classList.remove('border-transparent');
    }
    if (sections[tabName]) {
      sections[tabName].classList.remove('hidden');
    }
  }

  // 1. Detect the 'tab' query parameter from the URL
  const urlParams = new URLSearchParams(window.location.search);
  let activeTab = urlParams.get('tab') || 'overview';

  // 2. Show the requested (or default) tab
  showTab(activeTab);

  // 3. Attach click handlers for manual tab switching
  tabLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const targetTab = this.getAttribute('href').substring(1);
      showTab(targetTab);
    });
  });
</script>

</body>
</html>
