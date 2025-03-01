<!DOCTYPE html>
<html lang="en" x-data="{ 
    openNotifications: false, 
    openMessages: false,
    notifications: [
      { id: 1, text: 'Job #101 has been updated.', time: '2m ago' },
      { id: 2, text: 'New work order at Maple St.', time: '10m ago' },
      { id: 3, text: 'Job #102 was cancelled.', time: '30m ago' }
    ],
    messages: [
      { id: 1, from: 'Dispatch', subject: 'Check Equipment Inventory' },
      { id: 2, from: 'Customer', subject: 'Change mowing frequency' }
    ]
  }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Field Tech Dashboard</title>

  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome (Dev CDN) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js" crossorigin="anonymous"></script>
  <!-- Alpine.js (for the x-data logic) -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

  <!-- Include jQuery and jQuery UI CSS/JS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">
  <!-- HEADER / NAVBAR -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <div class="flex items-center">
      <a href="#" class="font-bold text-xl text-green-700">
        <i class="fa fa-leaf mr-2"></i> LawnPro Dash
      </a>
    </div>
    <!-- Right side icons: Notifications and Messages -->
    <div class="flex items-center space-x-4">
      <!-- Notifications Dropdown -->
      <div class="relative" @click.away="openNotifications = false">
        <button 
          @click="openNotifications = !openNotifications" 
          class="relative inline-flex items-center justify-center focus:outline-none"
        >
          <i class="fa-solid fa-bell text-xl"></i>
          <!-- Notification Badge -->
          <span 
            class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
            x-show="notifications.length"
            x-text="notifications.length"
          ></span>
        </button>

        <!-- Dropdown Panel -->
        <div 
          class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-md py-2"
          x-show="openNotifications"
          x-transition
        >
          <template x-if="notifications.length">
            <div>
              <template x-for="notification in notifications" :key="notification.id">
                <div class="px-4 py-2 hover:bg-gray-100 border-b text-sm">
                  <p x-text="notification.text"></p>
                  <span class="text-gray-500 text-xs" x-text="notification.time"></span>
                </div>
              </template>
            </div>
          </template>
          <template x-if="!notifications.length">
            <div class="px-4 py-2 text-gray-500 text-sm">
              No new notifications
            </div>
          </template>
        </div>
      </div>
      
      <!-- Messages Dropdown -->
      <div class="relative" @click.away="openMessages = false">
        <button 
          @click="openMessages = !openMessages" 
          class="relative inline-flex items-center justify-center focus:outline-none"
        >
          <i class="fa-solid fa-envelope text-xl"></i>
          <!-- Message Badge -->
          <span 
            class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-blue-600 rounded-full"
            x-show="messages.length"
            x-text="messages.length"
          ></span>
        </button>

        <!-- Dropdown Panel -->
        <div 
          class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-md py-2"
          x-show="openMessages"
          x-transition
        >
          <template x-if="messages.length">
            <div>
              <template x-for="message in messages" :key="message.id">
                <div class="px-4 py-2 hover:bg-gray-100 border-b text-sm">
                  <p class="font-semibold" x-text="message.from"></p>
                  <p x-text="message.subject"></p>
                </div>
              </template>
            </div>
          </template>
          <template x-if="!messages.length">
            <div class="px-4 py-2 text-gray-500 text-sm">
              No new messages
            </div>
          </template>
        </div>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <div class="flex flex-1">
    <!-- SIDEBAR -->
    <aside class="bg-white w-64 p-4 hidden lg:block">
      <nav class="space-y-2">
        <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-green-50">
          <i class="fa-solid fa-calendar-day mr-2"></i>
          <span>Daily Schedule</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-green-50">
          <i class="fa-solid fa-screwdriver-wrench mr-2"></i>
          <span>Work Orders</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-green-50">
          <i class="fa-solid fa-user-circle mr-2"></i>
          <span>Customers</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-green-50">
          <i class="fa-solid fa-warehouse mr-2"></i>
          <span>Inventory</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-green-50">
          <i class="fa-solid fa-file-invoice-dollar mr-2"></i>
          <span>Invoices</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-green-50">
          <i class="fa-solid fa-chart-line mr-2"></i>
          <span>Reports</span>
        </a>
      </nav>
    </aside>
    
    <!-- MAIN DASHBOARD AREA -->
    <main class="flex-1 p-4">
      <!-- DASHBOARD TITLE / BREADCRUMB -->
      <div class="mb-4">
        <h1 class="text-2xl font-bold text-green-800">Field Tech Dashboard</h1>
        <p class="text-sm text-gray-500">Overview of today’s operations</p>
      </div>
      
      <!-- GRID CARDS -->
      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
        <!-- Daily Schedule Overview -->
        <section class="bg-white rounded shadow p-4">
          <div class="flex items-center mb-2">
            <i class="fa-solid fa-calendar-day mr-2 text-green-600"></i>
            <h2 class="font-semibold text-lg">Daily Schedule</h2>
          </div>
          <ul class="text-sm space-y-2">
            <li class="border-b pb-2">
              <div class="flex justify-between">
                <span>8:00 AM - Job #101</span>
                <span class="text-gray-500">Main St Lawn</span>
              </div>
            </li>
            <li class="border-b pb-2">
              <div class="flex justify-between">
                <span>10:00 AM - Job #102</span>
                <span class="text-gray-500">Maple St Yard</span>
              </div>
            </li>
            <li class="border-b pb-2">
              <div class="flex justify-between">
                <span>1:00 PM - Job #103</span>
                <span class="text-gray-500">Oak Ave</span>
              </div>
            </li>
            <li>
              <div class="flex justify-between">
                <span>3:00 PM - Job #104</span>
                <span class="text-gray-500">Pine Dr</span>
              </div>
            </li>
          </ul>
        </section>

        <!-- Live Notifications (could also use a table or a feed) -->
        <section class="bg-white rounded shadow p-4">
          <div class="flex items-center mb-2">
            <i class="fa-solid fa-bell mr-2 text-yellow-500"></i>
            <h2 class="font-semibold text-lg">Live Notifications</h2>
          </div>
          <ul class="text-sm space-y-2">
            <li class="border-b pb-2">
              <p>Job #101 has been updated.</p>
              <span class="text-xs text-gray-400">2m ago</span>
            </li>
            <li class="border-b pb-2">
              <p>New work order at Maple St.</p>
              <span class="text-xs text-gray-400">10m ago</span>
            </li>
            <li class="border-b pb-2">
              <p>Job #102 was cancelled.</p>
              <span class="text-xs text-gray-400">30m ago</span>
            </li>
          </ul>
        </section>

        <!-- Unread Messages/Reminders -->
        <section class="bg-white rounded shadow p-4">
          <div class="flex items-center mb-2">
            <i class="fa-solid fa-envelope mr-2 text-blue-600"></i>
            <h2 class="font-semibold text-lg">Unread Messages</h2>
          </div>
          <ul class="text-sm space-y-2">
            <li class="border-b pb-2">
              <p class="font-semibold">Dispatch</p>
              <p class="text-gray-500">Please check inventory before heading out</p>
              <span class="text-xs text-gray-400">1hr ago</span>
            </li>
            <li class="pb-2">
              <p class="font-semibold">Customer</p>
              <p class="text-gray-500">Request to change mowing schedule</p>
              <span class="text-xs text-gray-400">2hrs ago</span>
            </li>
          </ul>
        </section>

        <!-- Weather Updates -->
        <section class="bg-white rounded shadow p-4">
          <div class="flex items-center mb-2">
            <i class="fa-solid fa-cloud-sun-rain mr-2 text-blue-400"></i>
            <h2 class="font-semibold text-lg">Weather Updates</h2>
          </div>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm">Current: 78°F, Partly Cloudy</p>
              <p class="text-xs text-gray-400">Chance of rain: 10%</p>
            </div>
            <i class="fa-solid fa-cloud-sun text-4xl text-yellow-300"></i>
          </div>
          <hr class="my-2" />
          <div>
            <p class="text-sm">Later Today: High 82°F, Low 60°F</p>
            <p class="text-xs text-gray-400">Rain possible late evening</p>
          </div>
        </section>

        <!-- System Alerts -->
        <section class="bg-white rounded shadow p-4">
          <div class="flex items-center mb-2">
            <i class="fa-solid fa-triangle-exclamation mr-2 text-red-600"></i>
            <h2 class="font-semibold text-lg">System Alerts</h2>
          </div>
          <ul class="text-sm space-y-2">
            <li class="border-b pb-2">
              <p>Low inventory: Grass seed is below threshold</p>
              <span class="text-xs text-gray-400">Check supply levels</span>
            </li>
            <li class="border-b pb-2">
              <p>Overdue Invoice for Customer #340</p>
              <span class="text-xs text-gray-400">2 days overdue</span>
            </li>
            <li>
              <p>Vehicle maintenance due in 5 days</p>
              <span class="text-xs text-gray-400">Truck #4</span>
            </li>
          </ul>
        </section>
      </div>
    </main>
  </div>

  <!-- FOOTER -->
  <footer class="bg-white shadow p-4 text-center mt-auto">
    <p class="text-xs text-gray-500">&copy; 2025 LawnPro. All rights reserved.</p>
  </footer>

</body>
</html>
