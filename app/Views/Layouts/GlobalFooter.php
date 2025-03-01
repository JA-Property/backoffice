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