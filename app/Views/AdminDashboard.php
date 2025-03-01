<?php
// Start output buffering
ob_start();
?>


<!-- View-specific content -->
<div class="flex flex-col p-4">
<!-- Alpine.js for interactivity -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<!-- Font Awesome CSS (CDN) -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
  integrity="sha512-SZ+zUPs6Xy+9ukRfRZigJ0+Bl1FdvtpV/A3u7iv0JGQNAKRraMNVSReQ5BUwFdCVpZdmvQfxu7/MeiJ6JOyhkQ=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>

<div class="space-y-2">
  <!-- Title & Subtitle -->
  <h1 class="text-3xl font-semibold text-gray-800 mb-1">
    Dashboard
  </h1>
</div>

<!-- High-Level Overview Section -->
<section class="mt-6" x-data="kpiData()">
  <!-- KPI Cards Container -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

    <!-- Card #1: Total Jobs -->
    <div
      class="relative bg-white rounded-lg shadow-sm p-5 flex flex-col
             hover:shadow-lg hover:-translate-y-1 transform transition"
      @mouseenter="toggleDetail('jobs')"
      @mouseleave="toggleDetail('jobs')"
    >
      <!-- Icon & Title -->
      <div class="flex items-center space-x-3">
        <!-- Font Awesome icon -->
        <i class="fa-solid fa-briefcase text-blue-600 text-2xl"></i>
        <h3 class="text-gray-700 font-bold text-lg">Total Jobs</h3>
      </div>

      <!-- Main Stat -->
      <div class="mt-2 flex items-baseline space-x-2">
        <!-- Animated Count (optional) -->
        <span class="text-3xl font-extrabold text-blue-600" x-text="formatNumber(totalJobs)"></span>
        <span class="text-sm text-gray-500">jobs</span>
      </div>

      <!-- Sub-info -->
      <p class="text-sm text-gray-400 mt-1" x-show="!showDetail.jobs">
        Completed: 90 | In-progress: 20 | Pending: 10
      </p>

      <!-- Expanded Detail on Hover -->
      <div
        class="mt-3 text-sm text-gray-600 space-y-1 bg-gray-50 p-2 rounded hidden"
        x-show="showDetail.jobs"
        x-transition.opacity
      >
        <p><strong>Completed:</strong> 90</p>
        <p><strong>In-progress:</strong> 20</p>
        <p><strong>Pending:</strong> 10</p>
        <p class="text-blue-600 cursor-pointer hover:underline">
          View all jobs &rarr;
        </p>
      </div>
    </div>

    <!-- Card #2: Revenue (This Month) -->
    <div
      class="relative bg-white rounded-lg shadow-sm p-5 flex flex-col
             hover:shadow-lg hover:-translate-y-1 transform transition"
      @mouseenter="toggleDetail('revenue')"
      @mouseleave="toggleDetail('revenue')"
    >
      <div class="flex items-center space-x-3">
        <i class="fa-solid fa-dollar-sign text-green-600 text-2xl"></i>
        <h3 class="text-gray-700 font-bold text-lg">Revenue (This Month)</h3>
      </div>
      <div class="mt-2 flex items-baseline space-x-2">
        <span
          class="text-3xl font-extrabold text-green-600"
          x-text="formatCurrency(totalRevenue)"
        ></span>
      </div>
      <p class="text-sm text-gray-400 mt-1" x-show="!showDetail.revenue">
        Daily Avg: $820
      </p>

      <!-- Expanded Detail on Hover -->
      <div
        class="mt-3 text-sm text-gray-600 space-y-1 bg-gray-50 p-2 rounded hidden"
        x-show="showDetail.revenue"
        x-transition.opacity
      >
        <p><strong>Daily Avg:</strong> $820</p>
        <p><strong>Total Last Month:</strong> $22,000</p>
        <p class="text-green-700 cursor-pointer hover:underline">
          View financials &rarr;
        </p>
      </div>
    </div>

    <!-- Card #3: Customer Satisfaction -->
    <div
      class="relative bg-white rounded-lg shadow-sm p-5 flex flex-col
             hover:shadow-lg hover:-translate-y-1 transform transition"
      @mouseenter="toggleDetail('satisfaction')"
      @mouseleave="toggleDetail('satisfaction')"
    >
      <div class="flex items-center space-x-3">
        <i class="fa-solid fa-star text-pink-600 text-2xl"></i>
        <h3 class="text-gray-700 font-bold text-lg">Customer Satisfaction</h3>
      </div>
      <div class="mt-2 flex items-baseline space-x-2">
        <span class="text-3xl font-extrabold text-pink-600">4.6</span>
        <span class="text-lg text-pink-500">/ 5</span>
      </div>
      <p class="text-sm text-gray-400 mt-1" x-show="!showDetail.satisfaction">
        Based on 230 reviews
      </p>

      <!-- Expanded Detail on Hover -->
      <div
        class="mt-3 text-sm text-gray-600 space-y-1 bg-gray-50 p-2 rounded hidden"
        x-show="showDetail.satisfaction"
        x-transition.opacity
      >
        <p><strong>Positive Reviews:</strong> 200</p>
        <p><strong>Neutral Reviews:</strong> 20</p>
        <p><strong>Negative Reviews:</strong> 10</p>
        <p class="text-pink-700 cursor-pointer hover:underline">
          View all feedback &rarr;
        </p>
      </div>
    </div>

    <!-- Card #4: Average Job Duration -->
    <div
      class="relative bg-white rounded-lg shadow-sm p-5 flex flex-col
             hover:shadow-lg hover:-translate-y-1 transform transition"
      @mouseenter="toggleDetail('duration')"
      @mouseleave="toggleDetail('duration')"
    >
      <div class="flex items-center space-x-3">
        <i class="fa-regular fa-clock text-indigo-600 text-2xl"></i>
        <!-- Or fa-solid if you prefer -->
        <h3 class="text-gray-700 font-bold text-lg">Average Job Duration</h3>
      </div>
      <div class="mt-2 flex items-baseline space-x-2">
        <span class="text-3xl font-extrabold text-indigo-600">2.3</span>
        <span class="text-lg text-indigo-500">hrs</span>
      </div>
      <p class="text-sm text-gray-400 mt-1" x-show="!showDetail.duration">
        Per Technician
      </p>

      <!-- Expanded Detail on Hover -->
      <div
        class="mt-3 text-sm text-gray-600 space-y-1 bg-gray-50 p-2 rounded hidden"
        x-show="showDetail.duration"
        x-transition.opacity
      >
        <p><strong>Peak Season Avg:</strong> 2.8 hrs</p>
        <p><strong>Off-Season Avg:</strong> 1.9 hrs</p>
        <p class="text-indigo-700 cursor-pointer hover:underline">
          View scheduling data &rarr;
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Alpine.js logic -->
<script>
  function kpiData() {
    return {
      // Example data
      totalJobs: 120,
      totalRevenue: 24500,
      showDetail: {
        jobs: false,
        revenue: false,
        satisfaction: false,
        duration: false
      },
      toggleDetail(key) {
        this.showDetail[key] = !this.showDetail[key];
      },
      // Utility functions
      formatNumber(num) {
        return num.toLocaleString();
      },
      formatCurrency(amount) {
        return '$' + amount.toLocaleString();
      }
    }
  }
</script>


<!-- FullCalendar CSS & JS (example CDN links) -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.0.0/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.0.0/index.global.min.js"></script>

<div class="bg-white p-4 rounded shadow mt-4">
  <h3 class="font-bold text-gray-700">Calendar/Scheduling</h3>
  <div id="calendar" class="mt-2"></div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: [
        {
          title: 'Job #101 - Lawn Care',
          start: '2025-03-25'
        },
        {
          title: 'Job #102 - Repair',
          start: '2025-03-26'
        }
      ],
      // Add more advanced features like drag-and-drop, resource scheduling, etc.
      editable: true,
      droppable: true
    });
    calendar.render();
  });
</script>


<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<div x-data="{ searchTerm: '', jobs: [
  { id: '#101', type: 'Lawn Care', status: 'In-Progress', due: 'Mar 25' },
  { id: '#102', type: 'Repair', status: 'Pending', due: 'Mar 26' },
  { id: '#103', type: 'Maintenance', status: 'Completed', due: 'Mar 24' },
  // ...
]}">

  <!-- Search bar -->
  <input type="text" placeholder="Search jobs..." 
         class="border p-2 rounded mb-2 w-full"
         x-model="searchTerm">

  <!-- Job List Table -->
  <table class="w-full text-left border-collapse mt-2">
    <thead>
      <tr>
        <th class="border-b py-2">Job ID</th>
        <th class="border-b py-2">Type</th>
        <th class="border-b py-2">Status</th>
        <th class="border-b py-2">Due Date</th>
      </tr>
    </thead>
    <tbody>
      <template x-for="job in jobs.filter(j => j.id.toLowerCase().includes(searchTerm.toLowerCase()) 
                                                || j.type.toLowerCase().includes(searchTerm.toLowerCase())
                                                || j.status.toLowerCase().includes(searchTerm.toLowerCase()))" :key="job.id">
        <tr>
          <td class="py-2 border-b" x-text="job.id"></td>
          <td class="py-2 border-b" x-text="job.type"></td>
          <td class="py-2 border-b" x-text="job.status"></td>
          <td class="py-2 border-b" x-text="job.due"></td>
        </tr>
      </template>
    </tbody>
  </table>
</div>



    <!-- Alerts & Notifications -->
    <div class="mt-6">
      <h3 class="text-lg font-bold text-gray-700">Alerts & Notifications</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li><strong>Maintenance:</strong> Vehicle #12 requires oil change in 2 days.</li>
        <li><strong>Equipment:</strong> Lawn mower blades running low in inventory.</li>
        <li><strong>Support Ticket:</strong> Ticket #435 is unresolved for 3 days.</li>
      </ul>
    </div>
  </section>
  <!-- END: High-Level Overview Cards -->


  <!-- BEGIN: Location & Mapping Tools -->
  <section>
    <h2 class="text-xl font-semibold mb-2">Location & Mapping Tools</h2>

    <div class="bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Interactive Map (Placeholder)</h3>
      <p class="text-gray-600"><em>Map displaying real-time GPS tracking here...</em></p>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Real-time GPS tracking of technicians or service vehicles</li>
        <li>Job location markers with quick view details</li>
      </ul>
    </div>

    <div class="mt-4 bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Route Optimization</h3>
      <p class="text-gray-600"><em>Optimized route suggestions displayed here...</em></p>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Suggested routes for upcoming jobs to reduce travel time</li>
      </ul>
    </div>
  </section>
  <!-- END: Location & Mapping Tools -->

  <!-- BEGIN: Detailed Analytics & Trends -->
  <section>
    <h2 class="text-xl font-semibold mb-2">Detailed Analytics & Trends</h2>
    
    <div class="bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Financial Charts</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Revenue trends over time (Line chart placeholder)</li>
        <li>Profit margins by service category (Bar chart placeholder)</li>
      </ul>
    </div>

    <div class="mt-4 bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Service Performance Charts</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Pie chart: Job types (Maintenance 35%, Repair 45%, Lawn Care 20%)</li>
        <li>Bar graph: Technician performance over time (Placeholder)</li>
      </ul>
    </div>

    <div class="mt-4 bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Customer Feedback Trends</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Aggregated review scores (Last month: 4.5, This month: 4.6)</li>
        <li>Satisfaction trends (Chart placeholder)</li>
      </ul>
    </div>
  </section>
  <!-- END: Detailed Analytics & Trends -->

  <!-- BEGIN: Operational Metrics & Reports -->
  <section>
    <h2 class="text-xl font-semibold mb-2">Operational Metrics & Reports</h2>

    <div class="bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Work Order Details</h3>
      <p class="text-gray-600"><em>Summary of pending/overdue jobs here...</em></p>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Pending Work Orders: 8</li>
        <li>Overdue Tasks: 3</li>
        <li>Recently Completed Jobs: 15</li>
      </ul>
    </div>

    <div class="mt-4 bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Resource Management</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Inventory levels (e.g., Lawn fertilizer: 20 bags left, Screws: 1,000 left)</li>
        <li>Staff utilization reports (Average 32 hrs/week vs. scheduled 40 hrs)</li>
      </ul>
    </div>
  </section>
  <!-- END: Operational Metrics & Reports -->

  <!-- BEGIN: Other Functional Widgets -->
  <section>
    <h2 class="text-xl font-semibold mb-2">Other Functional Widgets</h2>

    <div class="bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Weather Widget</h3>
      <p class="text-gray-600"><em>Local weather data fetched here...</em></p>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Today's Forecast: Sunny, High 72°F</li>
        <li>Tomorrow's Forecast: Partly Cloudy, High 68°F</li>
      </ul>
    </div>
    
    <div class="mt-4 bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Live Chat or Communication Feed</h3>
      <p class="text-gray-600"><em>Chat interface with field teams here...</em></p>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>Technician 1: <em>"Checking in at customer's location"</em></li>
        <li>Dispatcher: <em>"Make sure to confirm inventory after the job"</em></li>
      </ul>
    </div>

    <div class="mt-4 bg-white p-4 rounded shadow">
      <h3 class="font-bold text-gray-700">Announcements/Company News Feed</h3>
      <ul class="list-disc list-inside text-gray-600 mt-2">
        <li>New Policy: Safety training updates will be mandatory starting April 1st.</li>
        <li>Team Outing: Company picnic scheduled for May 5th.</li>
      </ul>
    </div>
  </section>
  <!-- END: Other Functional Widgets -->
</div>

<?php
// Capture the buffered content into a variable
$content = ob_get_clean();

// Set dynamic header and page title values
$headerIcon = 'fa-tachometer-alt'; // Example: Dashboard icon
$headerTitle = 'Dashboard';        // Example: Dashboard title
$pageTitle = 'Dashboard - MyApp';  // Example: Page title for the browser tab

// Include the global layout file and pass the content and variables to it
include __DIR__ . '/Layouts/Staff.php';
?>
