<!-- Top Nav -->
<header class="bg-gray-900 text-white shadow-sm p-3 flex items-center justify-between relative">
  <!-- Left side: title and mobile toggle -->
  <div class="flex items-center space-x-3">
    <button id="hamburgerBtn" class="md:hidden text-gray-300 focus:outline-none">
      <i class="fa fa-bars text-2xl"></i>
    </button>
    <div class="page-heading py-1" id="pageHeading">
      <h2 class="page-title text-xl font-semibold flex items-center space-x-2" id="pageTitle">
        <i id="headerIcon" class="fa fa-lg <?php echo htmlspecialchars($headerIcon); ?> text-white" aria-hidden="true"></i>
        <span id="headerTitle" class="font-semibold"><?php echo htmlspecialchars($headerTitle); ?></span>
      </h2>
    </div>
  </div>

  <!-- Right side: icon links with dropdowns -->
  <div class="flex space-x-6 items-center">
    
    <!-- Inbox Dropdown -->
    <div class="relative">
      <button id="chatDropdownBtn" class="hover:text-gray-300 focus:outline-none relative">
        <i class="fa fa-comment text-2xl text-gray-50" id="inboxIcon"></i>
        <span id="inboxBadge" class="hidden absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">2</span>
      </button>
      <div id="chatDropdownMenu"
           class="hidden fixed top-[4rem] left-1/2 transform -translate-x-1/2 w-[calc(100vw-1rem)] 
                  md:absolute md:mt-2 md:right-0 md:left-auto md:transform-none md:w-96 
                  bg-white shadow-xl rounded-lg overflow-hidden z-10">
        <!-- Dropdown Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white">
          <h3 class="text-lg font-bold">Inbox</h3>
        </div>
        <!-- Items Container -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
          <!-- Inbox Item -->
          <a href="#" class="block px-6 py-4 transition duration-150 odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fa fa-user-circle text-blue-500 text-2xl"></i>
              </div>
              <div class="ml-4 flex-1">
                <div class="flex justify-between items-center">
                  <h4 class="font-semibold text-gray-900">John Doe</h4>
                  <span class="text-xs text-gray-500">2:15 PM</span>
                </div>
                <p class="text-sm text-gray-700 mt-1">Project update: Meeting rescheduled to 3 PM.</p>
              </div>
              <div class="ml-4">
                <span class="bg-blue-500 h-3 w-3 rounded-full inline-block"></span>
              </div>
            </div>
          </a>
          <!-- Additional inbox items... -->
        </div>
        <!-- Footer -->
        <div class="px-6 py-3 bg-gray-100 text-center">
          <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
        </div>
      </div>
    </div>
    
    <!-- Notifications Dropdown -->
    <div class="relative">
      <button id="bellDropdownBtn" class="hover:text-gray-300 focus:outline-none relative">
        <i class="fa fa-bell text-2xl text-white" id="notifIcon"></i>
        <span id="bellBadge" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
      </button>
      <div id="bellDropdownMenu"
           class="hidden fixed top-[4rem] left-1/2 transform -translate-x-1/2 w-[calc(100vw-1rem)]
                  md:absolute md:mt-2 md:right-0 md:left-auto md:transform-none md:w-96
                  bg-white shadow-xl rounded-lg overflow-hidden z-10">
        <!-- Dropdown Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-500 text-white">
          <h3 class="text-lg font-bold">Notifications</h3>
        </div>
        <!-- Items Container -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
          <!-- Notification Item -->
          <a href="#" class="block px-6 py-4 transition duration-150 odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fa fa-info-circle text-green-500 text-2xl"></i>
              </div>
              <div class="ml-4 flex-1">
                <div class="flex justify-between items-center">
                  <h4 class="font-semibold text-gray-900">Notification Title 1</h4>
                  <span class="text-xs text-gray-500">Just now</span>
                </div>
                <p class="text-sm text-gray-700 mt-1">A brief description of the notification.</p>
              </div>
              <div class="ml-4">
                <span class="bg-green-500 h-3 w-3 rounded-full inline-block"></span>
              </div>
            </div>
          </a>
          <!-- Additional notification items... -->
        </div>
        <!-- Footer -->
        <div class="px-6 py-3 bg-gray-100 text-center">
          <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
        </div>
      </div>
    </div>
    
    <!-- Worklist Dropdown -->
    <div class="relative">
      <button id="clipboardDropdownBtn" class="hover:text-gray-300 focus:outline-none relative">
        <i class="fa fa-clipboard text-2xl text-white" id="worklistIcon"></i>
        <span id="worklistBadge" class="hidden absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">1</span>
      </button>
      <div id="clipboardDropdownMenu"
           class="hidden fixed top-[4rem] left-1/2 transform -translate-x-1/2 w-[calc(100vw-1rem)]
                  md:absolute md:mt-2 md:right-0 md:left-auto md:transform-none md:w-96
                  bg-white shadow-xl rounded-lg overflow-hidden z-10">
        <!-- Dropdown Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-500 text-white">
          <h3 class="text-lg font-bold">Worklist</h3>
        </div>
        <!-- Items Container -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
          <!-- Worklist Item -->
          <a href="#" class="block px-6 py-4 transition duration-150 odd:bg-white even:bg-gray-50 hover:bg-gray-100">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fa fa-clipboard text-purple-500 text-2xl"></i>
              </div>
              <div class="ml-4 flex-1">
                <div class="flex justify-between items-center">
                  <h4 class="font-semibold text-gray-900">Task Title 1</h4>
                  <span class="text-xs text-gray-500">Today</span>
                </div>
                <p class="text-sm text-gray-700 mt-1">Short task description or subtitle.</p>
              </div>
              <div class="ml-4">
                <span class="bg-purple-500 h-3 w-3 rounded-full inline-block"></span>
              </div>
            </div>
          </a>
          <!-- Additional worklist items... -->
        </div>
        <!-- Footer -->
        <div class="px-6 py-3 bg-gray-100 text-center">
          <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
        </div>
      </div>
    </div>
    
  </div>
  
  <!-- Dropdown Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const dropdowns = [
        { btn: document.getElementById('chatDropdownBtn'), menu: document.getElementById('chatDropdownMenu') },
        { btn: document.getElementById('bellDropdownBtn'), menu: document.getElementById('bellDropdownMenu') },
        { btn: document.getElementById('clipboardDropdownBtn'), menu: document.getElementById('clipboardDropdownMenu') }
      ];

      function closeAllDropdowns() {
        dropdowns.forEach(d => d.menu.classList.add('hidden'));
      }

      dropdowns.forEach(d => {
        d.btn.addEventListener('click', function(event) {
          event.stopPropagation();
          dropdowns.forEach(item => {
            if (item.menu !== d.menu) item.menu.classList.add('hidden');
          });
          d.menu.classList.toggle('hidden');
        });
      });

      document.addEventListener('click', function() {
        closeAllDropdowns();
      });
    });
  </script>

      <!-- Toggle Script -->
      <script>
    const hamburgerBtn = document.querySelector("#hamburgerBtn");
    const sidebar = document.querySelector("#sidebar");
    const overlay = document.querySelector("#sidebarOverlay");

    hamburgerBtn.addEventListener("click", () => {
      sidebar.classList.toggle("-translate-x-full");
      overlay.classList.toggle("hidden");
    });

    overlay.addEventListener("click", () => {
      sidebar.classList.add("-translate-x-full");
      overlay.classList.add("hidden");
    });
  </script>
</header>
