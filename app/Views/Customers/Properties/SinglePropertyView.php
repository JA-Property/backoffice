<?php
/**
 * /app/Views/Properties/SinglePropertyView.php
 *
 * This view displays the details and actions for a single service property.
 *
 * Expected variable:
 *   - $property: an associative array containing:
 *       - property_id
 *       - addrLine1
 *       - addrLine2 (optional)
 *       - city
 *       - state
 *       - zip
 *       - type (e.g., Residential, Commercial, Industrial)
 *       - notes
 *       - created_at
 *
 * Actions provided:
 *   - Edit Property
 *   - Delete Property (with confirmation)
 *   - Schedule Service
 *   - View History
 *
 * @package App\Views\Properties
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Service Property Details - <?= htmlspecialchars($property['addrLine1'] ?? 'Service Property Details', ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7/dist/tailwind.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SZ+zUPs6Xy+9ukRfRZigJ0+Bl1FdvtpV/A3u7iv0JGQNAKRraMNVSReQ5BUwFdCVpZdmvQfxu7/MeiJ6JOyhkQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100">

  <!-- Breadcrumb Navigation -->
  <div class="bg-white shadow-sm rounded-md p-4 mb-6">
    <nav class="text-sm" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-2">
        <li>
          <a href="/dashboard" class="text-blue-600 hover:underline transition">Home</a>
        </li>
        <li class="text-gray-400">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </li>
        <li>
          <a href="/properties/all" class="text-blue-600 hover:underline transition">Service Properties</a>
        </li>
        <li class="text-gray-400">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </li>
        <li class="text-gray-500" aria-current="page">
          <?= htmlspecialchars($property['addrLine1'] ?? 'Service Property Details', ENT_QUOTES, 'UTF-8') ?>
        </li>
      </ol>
    </nav>
  </div>

  <!-- Main Property Details Container -->
  <div class="mt-6 px-4 grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
    <!-- Left Card (Property Info) -->
    <div class="md:col-span-1 space-y-6">
      <div class="grid grid-cols-1 gap-4">
        <!-- PROPERTY NOTES CARD -->
        <div class="bg-white rounded-lg shadow p-4">
          <div class="flex items-center mb-2">
            <h1 class="text-3xl font-bold text-gray-800">
              <a href="/properties/view?id=<?= (int)$property['property_id'] ?>" class="hover:underline">
                <?= htmlspecialchars($property['addrLine1'] ?? 'No Address Provided', ENT_QUOTES, 'UTF-8') ?>
              </a>
              <?php if (!empty($property['addrLine2'])): ?>
                <span class="text-xl font-normal text-gray-600">, <?= htmlspecialchars($property['addrLine2'], ENT_QUOTES, 'UTF-8') ?></span>
              <?php endif; ?>
            </h1>
            <p class="text-gray-600 mt-2">
              <?= htmlspecialchars(($property['city'] ?? '') . ', ' . ($property['state'] ?? '') . ' ' . ($property['zip'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </p>
          </div>
          <div class="text-sm text-gray-600 mb-2">
            <p class="mb-1 font-semibold">
              <span class="text-gray-800">2025-07-15:</span> “Spoke with John about upcoming service schedule...”
            </p>
            <p class="text-gray-500">
              <em>See all notes for full details.</em>
            </p>
          </div>
          <div class="flex space-x-2 text-sm">
            <a href="#" class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 flex items-center space-x-1">
              <i class="fa fa-eye"></i>
              <span>View All Notes</span>
            </a>
            <a href="#" class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 flex items-center space-x-1">
              <i class="fa fa-plus"></i>
              <span>Add New Note</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Card (Tabs + Content) -->
    <div class="md:col-span-3 bg-white rounded-lg shadow-lg">
      <!-- Header with Tabs and Dropdown -->
      <div class="flex justify-between items-center bg-gray-800 text-white px-4 py-3 rounded-t-lg">
        <div class="flex justify-start space-x-6">
          <a href="#overview" class="custom-tab active text-sm font-semibold border-b-2 border-blue-500 pb-1 hover:text-blue-400">
            Overview
          </a>
          <a href="#properties" class="custom-tab text-sm font-semibold border-b-2 border-transparent pb-1 hover:text-blue-400">
            Properties
          </a>
          <a href="#details" class="custom-tab text-sm font-semibold border-b-2 border-transparent pb-1 hover:text-blue-400">
            Details
          </a>
          <a href="#activity" class="custom-tab text-sm font-semibold border-b-2 border-transparent pb-1 hover:text-blue-400">
            Activity
          </a>
        </div>
        <!-- Dropdown Button for Service Options -->
        <div class="relative inline-block text-left">
          <button id="serviceDropdownBtn" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" aria-expanded="true" aria-haspopup="true">
            Service Options
            <i class="ml-2 fas fa-caret-down"></i>
          </button>
          <!-- Dropdown menu -->
          <div id="serviceDropdownMenu" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="serviceDropdownBtn">
              <a href="#" id="newServiceOption" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">New Service</a>
              <!-- Additional dropdown options can be added here -->
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Content -->
      <div class="p-4">
        <!-- Overview Section -->
        <div id="overview">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Overview</h2>
          <p class="text-gray-600">
            This section provides an overview of the service details for the property.
          </p>
          <form action="/customers/impersonate" method="POST">
            <!-- hidden input for which customer to impersonate -->
            <input type="hidden" name="customer_id" value="25" />
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Impersonate this Customer</button>
          </form>
        </div>

        <!-- Details Section -->
        <div id="details" class="hidden">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Details</h2>
          <p class="text-gray-600">
            Detailed information about the service property. Praesent euismod sem a purus laoreet, nec fermentum est porta.
          </p>
        </div>

        <!-- Activity Section -->
        <div id="activity" class="hidden">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Activity</h2>
          <ul class="space-y-2">
            <li class="p-3 bg-gray-50 rounded-md">
              <p class="text-gray-600 text-sm">
                <span class="font-semibold">2025-01-01:</span> Service property record created.
              </p>
            </li>
            <li class="p-3 bg-gray-50 rounded-md">
              <p class="text-gray-600 text-sm">
                <span class="font-semibold">2025-01-02:</span> Initial service schedule set.
              </p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript for Dropdown and Popout Window -->
  <script>
    // Toggle dropdown menu
    document.getElementById('serviceDropdownBtn').addEventListener('click', function(event) {
      event.stopPropagation();
      var menu = document.getElementById('serviceDropdownMenu');
      menu.classList.toggle('hidden');
    });
    // Close dropdown if clicking outside
    document.addEventListener('click', function() {
      var menu = document.getElementById('serviceDropdownMenu');
      if (!menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
      }
    });

    // Open a popout window for "New Service"
    document.getElementById('newServiceOption').addEventListener('click', function(event) {
  event.preventDefault();
  // Get the current URL and ensure it ends with a slash before appending
  var currentUrl = window.location.href;
  if (currentUrl.charAt(currentUrl.length - 1) !== '/') {
    currentUrl += '/';
  }
  var url = currentUrl + 'add-service';
  var windowFeatures = 'width=600,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no';
  window.open(url, 'NewServiceWindow', windowFeatures);
});

  </script>
</body>
</html>
