<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome (Dev CDN) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js" crossorigin="anonymous"></script>
  <!-- Alpine.js (for the x-data logic) -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex">
  <!-- SIDEBAR -->
  <aside
    id="sidebar"
    class="bg-gray-800 text-white fixed inset-y-0 left-0 w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-200 z-50 flex flex-col"
  >
    <!-- Header / Logo -->
    <div class="p-4 bg-gray-900 flex items-center">
      <i class="fa fa-home mr-2"></i>
      <span class="font-semibold text-lg">JJ Turfs</span>
    </div>

    <!-- Main Menu (Scroll if needed) -->
    <nav id="sidebarMenu" class="flex-1 overflow-y-auto px-2 py-3 space-y-1">
      <!-- Dashboard Link -->
      <a href="/" class="flex items-center px-3 py-2 rounded hover:bg-gray-700 transition">
        <i class="fa fa-tachometer-alt mr-2 w-4 text-center"></i>
        <span>Dashboard</span>
      </a>

      <!-- Customers Section -->
      <div class="acc-parent">
        <button class="w-full flex items-center px-3 py-2 rounded hover:bg-gray-700 focus:outline-none transition" data-accordion-btn>
          <i class="fa fa-users mr-2 w-4 text-center"></i>
          <span class="flex-1 text-left">Customers</span>
          <i class="fa fa-chevron-down ml-auto transform transition-all duration-200"></i>
        </button>
        <div class="acc-children hidden flex-col ml-6 border-l border-gray-700" data-accordion-content>
          <a href="/staff/customers/all" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">All Customers</a>
          <a href="/staff/customers/new" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">New Customer</a>
          <div class="acc-parent">
            <button class="w-full flex items-center px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700 focus:outline-none transition" data-accordion-btn>
              Segments
              <i class="fa fa-chevron-down ml-auto transform transition-all duration-200 text-xs"></i>
            </button>
            <div class="acc-children hidden flex-col ml-4 border-l border-gray-700" data-accordion-content>
              <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Retail</a>
              <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Commercial</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Accounting Section -->
      <div class="acc-parent">
        <button class="w-full flex items-center px-3 py-2 rounded hover:bg-gray-700 focus:outline-none transition" data-accordion-btn>
          <i class="fas fa-calculator mr-2 w-4 text-center"></i>
          <span class="flex-1 text-left">Accounting</span>
          <i class="fa fa-chevron-down ml-auto transform transition-all duration-200"></i>
        </button>
        <div class="acc-children hidden flex-col ml-6 border-l border-gray-700" data-accordion-content>
          <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Journal Entries</a>
          <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">General Ledger</a>
          <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Chart of Accounts</a>
        </div>
      </div>

      <!-- Invoices Section -->
      <div class="acc-parent">
        <button class="w-full flex items-center px-3 py-2 rounded hover:bg-gray-700 focus:outline-none transition" data-accordion-btn>
          <i class="fa fa-file-invoice-dollar mr-2 w-4 text-center"></i>
          <span class="flex-1 text-left">Invoices</span>
          <i class="fa fa-chevron-down ml-auto transform transition-all duration-200"></i>
        </button>
        <div class="acc-children hidden flex-col ml-6 border-l border-gray-700" data-accordion-content>
          <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Unpaid</a>
          <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Paid</a>
          <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Overdue</a>
        </div>
      </div>
    </nav>

    <!-- Footer w/ Profile Info -->
    <div class="p-4 border-t border-gray-700 flex items-center space-x-3">
      <!-- Colored red circle with initials -->
      <div class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center font-semibold">
        <?= $_SESSION['user']['initials']; ?>
      </div>
      <div class="flex-1">
        <div class="text-sm font-semibold"><?= $_SESSION['user']['display_name']; ?></div>
        <div class="text-xs text-gray-300"><?= ucfirst($_SESSION['user']['role']); ?></div>
      </div>
      <a href="https://auth.japropertysc.com/logout" class="hover:text-gray-400">
        <i class="fa fa-sign-out-alt"></i>
      </a>
    </div>
  </aside>

  <!-- OVERLAY (mobile) -->
  <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

  <!-- MAIN CONTENT WRAPPER -->
  <div class="flex-1 flex flex-col md:ml-64">
    <!-- Top Nav -->
    <header class="bg-white text-gray-700 shadow-sm px-4 py-2 flex items-center justify-between">
      <!-- Left side: title and mobile toggle -->
      <div class="flex items-center space-x-3">
        <button id="hamburgerBtn" class="md:hidden text-gray-500 focus:outline-none">
          <i class="fa fa-bars"></i>
        </button>
        <div class="page-heading py-1" id="pageHeading">
          <h2 class="page-title text-xl font-semibold flex items-center space-x-2" id="pageTitle">
            <i id="headerIcon" class="fa fa-lg <?php echo htmlspecialchars($headerIcon); ?>" aria-hidden="true"></i> <!-- Dynamic header icon -->
            <span id="headerTitle"><?php echo htmlspecialchars($headerTitle); ?></span> <!-- Dynamic header title -->
          </h2>
        </div>
      </div>

      <!-- Right side: icon links -->
      <div class="flex space-x-4 items-center text-gray-500">
        <a href="/scheduler/add-new-todo" title="Create a To-Do" class="hover:text-gray-800">
          <i class="fas fa-tasks" aria-hidden="true"></i>
        </a>
        <a href="#" id="messenger-open" class="relative hover:text-gray-800">
          <i class="fas fa-comment" aria-hidden="true"></i>
          <div id="notify-badge-msg" class="absolute top-0 right-0" style="display: none;">
            <div class="notify-pulse"></div>
            <div class="notify-marker"></div>
          </div>
        </a>
        <a href="/settings" class="hover:text-gray-800">
          <i class="fa fa-lg fa-cog" aria-hidden="true"></i>
        </a>
        <a href="/notifications" class="relative hover:text-gray-800">
          <i class="fa fa-lg fa-bell" aria-hidden="true"></i>
          <div id="notify-badge" class="absolute top-0 right-0" style="display: none;">
            <div class="notify-pulse"></div>
            <div class="notify-marker"></div>
          </div>
        </a>
        <a href="/resources/employees/edit/62149" class="hover:text-gray-800">
          <i class="fa fa-lg fa-user" aria-hidden="true"></i>
        </a>
      </div>
    </header>

    <!-- View Content -->
    <main id="viewContent" class="flex-1">
      <!-- Outer Container -->
<div class="flex flex-col p-4 space-y-6 pb-16">




  
      <?php echo $content; ?> <!-- Inject the dynamic content here -->
      </div>
    </main>
<!-- Mobile Footer Menu -->
<div class="fixed bottom-0 w-full bg-white border-t border-gray-200 md:hidden py-2">
  <!-- Menu groups container -->
  <div class="flex justify-between items-center px-4">
    <!-- Left Group -->
    <ul class="flex space-x-10 ml-12">
      <li>
        <a class="flex flex-col items-center text-gray-600" href="/scheduler/month" style="font-size: 12px;">
          <i class="fas fa-calendar-alt" aria-hidden="true"></i>
          <span>Schedule</span>
        </a>
      </li>
      <li>
        <a class="flex flex-col items-center text-gray-600" href="/finances/invoices" style="font-size: 12px;">
          <i class="fas fa-users" aria-hidden="true"></i>
          <span>Customers</span>
        </a>
      </li>
    </ul>

    <!-- Right Group -->
    <ul class="flex space-x-10 mr-12">
      <li>
        <a class="flex flex-col items-center text-gray-600" href="/finances/payments" style="font-size: 12px;">
          <i class="fas fa-lg fa-dollar-sign" aria-hidden="true"></i>
          <span>Payments</span>
        </a>
      </li>
      <!-- "More" Dropdown -->
      <li x-data="{ open: false }" class="relative">
        <button 
          @click="open = !open" 
          class="flex flex-col items-center text-gray-600" 
          style="font-size: 12px;"
        >
          <i class="fas fa-ellipsis" aria-hidden="true"></i>
          <span>More</span>
        </button>
        <!-- Dropdown goes up and aligns to the right -->
        <div
          x-show="open"
          @click.away="open = false"
          class="absolute bottom-full right-0 mb-2 bg-white border border-gray-200 shadow-md text-sm w-48 z-50"
          x-transition
        >
          <a class="block px-4 py-2 hover:bg-gray-100" href="/customers">
            <i class="fal fa-users mr-1" aria-hidden="true"></i> Customers
          </a>
          <a class="block px-4 py-2 hover:bg-gray-100" href="/assets">
            <i class="fal fa-home mr-2" aria-hidden="true"></i> Properties
          </a>
          <a class="block px-4 py-2 hover:bg-gray-100" href="/finances/estimates">
            <i class="fal fa-file-alt ml-1 mr-2" aria-hidden="true"></i> Estimates
          </a>
        </div>
      </li>
    </ul>
  </div>

  <!-- Floating New Plus Button with Drop Menu -->
  <div x-data="{ openPlus: false }" class="">
    <button
      @click="openPlus = !openPlus"
      class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white rounded-full h-12 w-12 
             flex items-center justify-center shadow-lg z-50"
    >
      <i class="fa fa-plus text-2xl"></i>
    </button>
    <!-- Dropdown opens upward, centered -->
    <div
      x-show="openPlus"
      @click.away="openPlus = false"
      class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 bg-white border border-gray-200 
             shadow-md text-sm w-48 z-50"
      x-transition
    >
      <ul class="py-2 space-y-1">
        <li>
          <a href="/add/event" class="block px-4 py-2 hover:bg-gray-100">Add Event</a>
        </li>
        <li>
          <a href="/add/task" class="block px-4 py-2 hover:bg-gray-100">Add Task</a>
        </li>
        <li>
          <a href="/add/note" class="block px-4 py-2 hover:bg-gray-100">Add Note</a>
        </li>
      </ul>
    </div>
  </div>
</div>




  </div>

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

  <!-- Header Update Script -->
  <script>
    // Function to update the header icon and title
    function updateHeader(iconClass, title) {
      const headerIcon = document.getElementById('headerIcon');
      const headerTitle = document.getElementById('headerTitle');

      if (headerIcon && headerTitle) {
        headerIcon.className = `fa fa-lg ${iconClass}`;
        headerTitle.textContent = title;
      }
    }

    // Event delegation for navigation links
    document.addEventListener('click', function(event) {
      const target = event.target.closest('a');
      if (target && target.getAttribute('href')) {
        const linkHref = target.getAttribute('href');
        // Only prevent default for hash links (which are used to update the header)
        if(linkHref === '#' || linkHref.startsWith('#')) {
          event.preventDefault(); // Prevent default link behavior
        } else {
          return; // Allow normal navigation for actual links
        }

        // Update header based on the link clicked
        switch (linkHref) {
          case '#':
            updateHeader('fa-tachometer-alt', 'Dashboard');
            break;
          case '#customers':
            updateHeader('fa-users', 'Customers');
            break;
          case '#accounting':
            updateHeader('fa-calculator', 'Accounting');
            break;
          case '#invoices':
            updateHeader('fa-file-invoice-dollar', 'Invoices');
            break;
          default:
            updateHeader('fa-home', 'MyApp');
        }

        // Log the link href (for debugging)
        console.log('Loading view:', linkHref);
      }
    });
  </script>
  <script>
    // Accordion logic with single-open behavior
    // For each .acc-parent, clicking [data-accordion-btn] toggles that sub-menu
    // and closes siblings at the same level.
    const parentMenus = document.querySelectorAll('.acc-parent');

    parentMenus.forEach((parent) => {
      const toggleBtn = parent.querySelector('[data-accordion-btn]');
      const childrenContainer = parent.querySelector('[data-accordion-content]');

      if (toggleBtn && childrenContainer) {
        toggleBtn.addEventListener('click', (e) => {
          e.stopPropagation();

          // Close siblings on the same level
          const siblings = parent.parentElement.querySelectorAll('.acc-parent');
          siblings.forEach((sib) => {
            if (sib !== parent) {
              const sibBtn = sib.querySelector('[data-accordion-btn]');
              const sibContent = sib.querySelector('[data-accordion-content]');
              if (sibContent && !sibContent.classList.contains('hidden')) {
                sibContent.classList.add('hidden');
                // Rotate chevron back
                const sibChevron = sibBtn.querySelector('.fa-chevron-down');
                sibChevron && sibChevron.classList.remove('rotate-180');
              }
            }
          });

          // Toggle this sub-menu
          childrenContainer.classList.toggle('hidden');

          // Rotate chevron
          const chevron = toggleBtn.querySelector('.fa-chevron-down');
          chevron && chevron.classList.toggle('rotate-180');
        });
      }
    });
  </script>
</body>
</html>
