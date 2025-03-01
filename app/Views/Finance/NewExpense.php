<div class="p-6 bg-white border border-gray-200 shadow-lg rounded-lg">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Expense Entry</h2>
  <form action="/finance/expense/submit" method="post" enctype="multipart/form-data">

<!-- Receipt Upload (Camera icon outlined circle) -->
<div class="mb-6">
  <label class="block text-sm font-semibold text-gray-700 mb-2 text-center">Receipt</label>
  <div class="flex justify-center">
    <!-- Hidden file input with camera capture -->
    <input
      id="receiptUpload"
      name="receiptUpload"
      type="file"
      accept="image/*"
      capture="environment"
      class="hidden"
    >
    <button
      type="button"
      onclick="document.getElementById('receiptUpload').click()"
      class="flex items-center justify-center w-16 h-16 border-2 border-blue-600 text-blue-600 rounded-full hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
      <i class="fa fa-camera text-2xl" aria-hidden="true"></i>
    </button>
  </div>
</div>

<!-- Date -->
<div class="mb-5">
  <label for="expenseDate" class="block text-sm font-semibold text-gray-700 mb-2">
    Date
  </label>
  <input
    type="text"
    id="expenseDate"
    name="expenseDate"
    placeholder="YYYY-MM-DD"
    required
    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
  >
</div>
<style>
#expenseDate {
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  appearance: none !important;
  background-color: #fff !important; /* Ensure a white background */
}

/* Remove any calendar picker indicator if it appears */
#expenseDate::-webkit-calendar-picker-indicator {
  opacity: 0 !important;
  display: none !important;
}

</style>
<!-- Include Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
  $(document).ready(function(){
    flatpickr("#expenseDate", {
      dateFormat: "Y-m-d"
    });
  });
</script>


<!-- Category (Auto-suggest text input) -->
<div class="mb-5">
  <label for="expenseCategory" class="block text-sm font-semibold text-gray-700 mb-2">
    Category
  </label>
  <input
    type="text"
    id="expenseCategory"
    name="expenseCategory"
    placeholder="Type to filter categories"
    required
    autocomplete="off"
    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
  >
</div>

<script>
$(function(){
  $("#expenseCategory").autocomplete({
    source: function(request, response) {
      console.log("Searching for Category:", request.term);
      $.ajax({
        url: '/finance/category/search', // Calls CategoryController::search()
        dataType: 'json',
        data: {
          term: request.term
        },
        success: function(data) {
          console.log("Category data received:", data);
          response(data);
        },
        error: function(xhr, status, error) {
          console.error("Category AJAX error:", error);
        }
      });
    },
    minLength: 0, // Allows suggestions to appear immediately (for testing)
    select: function(event, ui) {
      console.log("Selected Category ID:", ui.item.id);
      // Optionally, you can store the selected category ID in a hidden field
    }
  });
});
</script>

<!-- Vendor (Auto-suggest text input for Merchant) -->
<div class="mb-5">
  <label for="expenseVendor" class="block text-sm font-semibold text-gray-700 mb-2">
    Vendor
  </label>
  <input
    type="text"
    id="expenseVendor"
    name="expenseVendor"
    placeholder="Enter vendor name"
    required
    autocomplete="off"
    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
  >
</div>

<script>
$(function(){
  $("#expenseVendor").autocomplete({
    source: function(request, response) {
      console.log("Searching for Vendor:", request.term);
      $.ajax({
        url: '/finance/merchant/search', // Calls MerchantController::search()
        dataType: 'json',
        data: {
          term: request.term
        },
        success: function(data) {
          console.log("Vendor data received:", data);
          response(data);
        },
        error: function(xhr, status, error) {
          console.error("Vendor AJAX error:", error);
        }
      });
    },
    minLength: 0,
    select: function(event, ui) {
      console.log("Selected Vendor ID:", ui.item.id);
      // Optionally, store the selected vendor/merchant ID in a hidden field
    }
  });
});
</script>


 <!-- Amount (Text input with numeric input mode) -->
<div class="mb-5">
  <label for="expenseAmount" class="block text-sm font-semibold text-gray-700 mb-2">
    Amount
  </label>
  <input
    type="text"
    inputmode="decimal"
    id="expenseAmount"
    name="expenseAmount"
    placeholder="0.00"
    required
    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
  >
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new AutoNumeric('#expenseAmount', {
      currencySymbol: '$',            // Display a dollar sign
      decimalPlaces: 2,               // Two decimal places
      digitGroupSeparator: ',',       // Commas for thousands
      currencySymbolPlacement: 'p',   // Symbol placed before the number
      unformatOnSubmit: true          // Submits unformatted numeric value
    });
  });
</script>

  <!-- Payment Method (Dropdown from DB) -->
<div class="mb-5">
  <label for="expensePaymentMethod" class="block text-sm font-semibold text-gray-700 mb-2">
    Payment Method
  </label>
  <select
    id="expensePaymentMethod"
    name="expensePaymentMethod"
    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
    required
  >
    <option value="" disabled selected>Select Payment Method</option>
  </select>
</div>

<script>
$(function(){
  $.ajax({
    url: '/finance/payment-methods', // This calls PaymentMethodController::getAll()
    dataType: 'json',
    success: function(data) {
      var $select = $('#expensePaymentMethod');
      $.each(data, function(i, method) {
        $select.append($('<option>', {
          value: method.id,
          text: method.name
        }));
      });
    },
    error: function(xhr, status, error) {
      console.error("Error fetching payment methods:", error);
    }
  });
});
</script>

    <!-- Notes -->
    <div class="mb-5">
      <label for="expenseNotes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
      <textarea
        id="expenseNotes"
        name="expenseNotes"
        rows="3"
        placeholder="Additional details"
        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
      ></textarea>
    </div>



    <!-- Submit -->
    <div>
      <button
        type="submit"
        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500"
      >
        Save Expense
      </button>
    </div>
  </form>
</div>
