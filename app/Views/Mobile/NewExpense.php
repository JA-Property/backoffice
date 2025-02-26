<div class="max-w-sm mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-lg font-bold mb-4">Expense Entry</h2>
  
    <form>
      <!-- Date -->
      <div class="mb-4">
        <label for="expenseDate" class="block text-sm font-medium text-gray-700">
          Date
        </label>
        <input
          type="date"
          id="expenseDate"
          name="expenseDate"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          required
        />
      </div>
  
      <!-- Category -->
      <div class="mb-4">
        <label for="expenseCategory" class="block text-sm font-medium text-gray-700">
          Category
        </label>
        <select
          id="expenseCategory"
          name="expenseCategory"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          required
        >
          <option value="" disabled selected>Select Category</option>
          <option value="Travel">Travel</option>
          <option value="Meals">Meals</option>
          <option value="Supplies">Supplies</option>
          <!-- Add more categories as needed -->
        </select>
      </div>
  
      <!-- Vendor -->
      <div class="mb-4">
        <label for="expenseVendor" class="block text-sm font-medium text-gray-700">
          Vendor
        </label>
        <input
          type="text"
          id="expenseVendor"
          name="expenseVendor"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          placeholder="Enter vendor name"
          required
        />
      </div>
  
      <!-- Amount -->
      <div class="mb-4">
        <label for="expenseAmount" class="block text-sm font-medium text-gray-700">
          Amount
        </label>
        <input
          type="number"
          step="0.01"
          id="expenseAmount"
          name="expenseAmount"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          placeholder="0.00"
          required
        />
      </div>
  
      <!-- Payment Method -->
      <div class="mb-4">
        <label for="expensePaymentMethod" class="block text-sm font-medium text-gray-700">
          Payment Method
        </label>
        <select
          id="expensePaymentMethod"
          name="expensePaymentMethod"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
        >
          <option value="" disabled selected>Select Payment Method</option>
          <option value="Cash">Cash</option>
          <option value="Credit Card">Credit Card</option>
          <option value="Debit Card">Debit Card</option>
          <option value="Check">Check</option>
          <!-- Add more methods as needed -->
        </select>
      </div>
  
      <!-- Notes -->
      <div class="mb-4">
        <label for="expenseNotes" class="block text-sm font-medium text-gray-700">
          Notes
        </label>
        <textarea
          id="expenseNotes"
          name="expenseNotes"
          rows="3"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          placeholder="Additional details"
        ></textarea>
      </div>
  
      <!-- Camera / Upload -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Receipt</label>
        <div class="mt-1 flex">
          <!-- Hidden file input with camera capture -->
          <input
            id="receiptUpload"
            name="receiptUpload"
            type="file"
            accept="image/*"
            capture="environment"
            class="hidden"
          />
          <button
            type="button"
            class="flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700"
            onclick="document.getElementById('receiptUpload').click()"
          >
            <!-- Optional icon -->
            <svg
              class="w-4 h-4 mr-1"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
            Camera / Upload
          </button>
        </div>
      </div>
  
      <!-- Submit -->
      <div>
        <button
          type="submit"
          class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-md"
        >
          Save Expense
        </button>
      </div>
    </form>
  </div>
