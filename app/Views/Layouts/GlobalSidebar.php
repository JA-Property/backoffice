  <!-- SIDEBAR -->
  <aside
    id="sidebar"
    class="bg-gray-800 text-white fixed inset-y-0 left-0 w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-200 z-50 flex flex-col"
  >
    <!-- Header / Logo -->
    <div class="p-4 bg-gray-900 flex items-center">
      <i class="fa fa-leaf mr-2"></i>
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
          <a href="/customers/all" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">All Customers</a>
          <a href="/customers/new" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">New Customer</a>
        </div>
      </div>
      <!-- Marketing Section -->
      <div class="acc-parent">
        <button class="w-full flex items-center px-3 py-2 rounded hover:bg-gray-700 focus:outline-none transition" data-accordion-btn>
          <i class="fa fa-users mr-2 w-4 text-center"></i>
          <span class="flex-1 text-left">Marketing</span>
          <i class="fa fa-chevron-down ml-auto transform transition-all duration-200"></i>
        </button>
        <div class="acc-children hidden flex-col ml-6 border-l border-gray-700" data-accordion-content>
          <div class="acc-parent">
            <button class="w-full flex items-center px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700 focus:outline-none transition" data-accordion-btn>
              Public Site
              <i class="fa fa-chevron-down ml-auto transform transition-all duration-200 text-xs"></i>
            </button>
            <div class="acc-children hidden flex-col ml-4 border-l border-gray-700" data-accordion-content>
              <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Edit</a>
              <a href="#" class="block px-3 py-1.5 mt-1 text-sm rounded hover:bg-gray-700">Preview</a>
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