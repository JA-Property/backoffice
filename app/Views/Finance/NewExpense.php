<div class="p-6 bg-white border border-gray-200 shadow-lg rounded-lg">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Expense Entry</h2>
  <form>

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
      <label for="expenseDate" class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
      <input
        type="date"
        id="expenseDate"
        name="expenseDate"
        required
        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
      >
    </div>

    <!-- Category (Auto-suggest text input with Add New button) -->
    <div class="mb-5">
  <label for="expenseCategory" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
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
    console.log("Searching for:", request.term);
    $.ajax({
      url: '/finance/category/search', // Adjust URL if needed
      dataType: 'json',
      data: {
        term: request.term
      },
      success: function(data) {
        console.log("Data received:", data);
        response(data);
      },
      error: function(xhr, status, error) {
        console.error("AJAX error:", error);
      }
    });
  },
  minLength: 0, // Set to 0 for testing purposes
  select: function(event, ui) {
    console.log("Selected Category ID:", ui.item.id);
  }
});

});
</script>

    <!-- Vendor (Auto-suggest text input) -->
    <div class="mb-5">
      <label for="expenseVendor" class="block text-sm font-semibold text-gray-700 mb-2">Vendor</label>
      <input
        type="text"
        id="expenseVendor"
        name="expenseVendor"
        placeholder="Enter vendor name"
        required
        autocomplete="off"
        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
      >
      <!-- Implement auto-suggestions here as needed -->
    </div>

    <!-- Amount (Text input with numeric input mode) -->
    <div class="mb-5">
      <label for="expenseAmount" class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
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

    <!-- Payment Method (Text input) -->
    <div class="mb-5">
      <label for="expensePaymentMethod" class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
      <input
        type="text"
        id="expensePaymentMethod"
        name="expensePaymentMethod"
        placeholder="Enter payment method"
        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
      >
    </div>

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
