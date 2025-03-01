    <div class="bg-white shadow-lg rounded-lg p-6">
      <div class="flex flex-col md:flex-row">
        <!-- Left Column: Enlarged Receipt Image -->
        <div class="md:w-1/3 mb-4 md:mb-0">
          <?php if (!empty($expense['receipt'])): ?>
            <img src="<?= htmlspecialchars($expense['receipt']); ?>" alt="Expense Receipt" class="w-full h-auto object-contain border border-gray-300 rounded">
          <?php else: ?>
            <div class="w-full h-48 flex items-center justify-center bg-gray-200 border border-gray-300 rounded">
              <span class="text-gray-500">No Receipt Available</span>
            </div>
          <?php endif; ?>
        </div>
<!-- Right Column: Expense Details & Review Information -->
<div class="md:w-2/3 md:pl-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Left Column: Core Expense Overview -->
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-2">Expense Overview</h2>
      <table class="w-full text-left">
        <tbody>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Expense Date:</th>
            <td class="py-2"><?= htmlspecialchars($expense['expense_date']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Submission Date:</th>
            <td class="py-2"><?= htmlspecialchars($expense['created_date']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Selected Category:</th>
            <td class="py-2"><?= htmlspecialchars($expense['category']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Selected Merchant:</th>
            <td class="py-2"><?= htmlspecialchars($expense['vendor']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Total Amount:</th>
            <td class="py-2">$<?= number_format($expense['amount'], 2); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Payment Method:</th>
            <td class="py-2"><?= htmlspecialchars($expense['payment_method']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Submission Notes:</th>
            <td class="py-2"><?= nl2br(htmlspecialchars($expense['notes'])); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Posting Date:</th>
            <td class="py-2"><?= htmlspecialchars($expense['payment_method']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Ledger Account:</th>
            <td class="py-2"><?= nl2br(htmlspecialchars($expense['notes'])); ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Right Column: Bookkeeper Review & Tagging -->
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-2">Review & Tag Expense</h2>
      
      <table class="w-full text-left">
        <tbody>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Approval Status</th>
            <td class="py-2"><?= htmlspecialchars($expense['expense_date']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Cost Center</th>
            <td class="py-2"><?= htmlspecialchars($expense['created_date']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Associated Work Order:</th>
            <td class="py-2"><?= htmlspecialchars($expense['category']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Invoice Number:</th>
            <td class="py-2"><?= htmlspecialchars($expense['vendor']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Tax Code:</th>
            <td class="py-2">$<?= number_format($expense['amount'], 2); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Reviewed By:</th>
            <td class="py-2"><?= htmlspecialchars($expense['payment_method']); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Journal Entry #:</th>
            <td class="py-2"><?= nl2br(htmlspecialchars($expense['notes'])); ?></td>
          </tr>
          <tr>
            <th class="py-2 pr-4 font-semibold text-gray-700">Additional Comments:</th>
            <td class="py-2"><?= nl2br(htmlspecialchars($expense['notes'])); ?></td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>

</div>

      </div>

      <!-- Line Items Section -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Line Items</h2>
        <?php if (isset($lineItems) && count($lineItems) > 0): ?>
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-gray-100">
                <th class="border px-4 py-2 text-left">Item Description</th>
                <th class="border px-4 py-2 text-right">Quantity</th>
                <th class="border px-4 py-2 text-right">Unit Price</th>
                <th class="border px-4 py-2 text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $grandTotal = 0;
                foreach ($lineItems as $item):
                  $lineTotal = $item['quantity'] * $item['unit_price'];
                  $grandTotal += $lineTotal;
              ?>
                <tr>
                  <td class="border px-4 py-2"><?= htmlspecialchars($item['description']); ?></td>
                  <td class="border px-4 py-2 text-right"><?= number_format($item['quantity'], 2); ?></td>
                  <td class="border px-4 py-2 text-right">$<?= number_format($item['unit_price'], 2); ?></td>
                  <td class="border px-4 py-2 text-right">$<?= number_format($lineTotal, 2); ?></td>
                </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="3" class="border px-4 py-2 font-bold text-right">Grand Total</td>
                <td class="border px-4 py-2 text-right font-bold">$<?= number_format($grandTotal, 2); ?></td>
              </tr>
            </tbody>
          </table>
        <?php else: ?>
          <p class="text-gray-600">No line items available for this expense. You can add details later.</p>
        <?php endif; ?>
      </div>

      <!-- Actions Section -->
      <div class="mt-8 flex flex-wrap gap-4">
        <a href="/finance/expense/edit?id=<?= htmlspecialchars($expense['id']); ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Edit Expense</a>
        <a href="/finance/expense/delete?id=<?= htmlspecialchars($expense['id']); ?>" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to delete this expense?');">Delete Expense</a>
        <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition" onclick="window.print();">Print Expense</button>
      </div>

      <!-- Expense Timeline / Audit Log -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Timeline</h2>
        <!-- Replace with dynamic timeline data as needed -->
        <ul class="border-l-2 border-blue-500 ml-4">
          <li class="mb-4">
            <div class="flex items-center">
              <div class="bg-blue-500 rounded-full h-4 w-4 -ml-2 mr-2"></div>
              <span class="font-semibold text-gray-700">Submitted</span>
              <span class="text-sm text-gray-500 ml-2">2025-02-26 17:45</span>
            </div>
            <p class="ml-8 text-gray-600">Expense submitted by Jane Doe.</p>
          </li>
          <li class="mb-4">
            <div class="flex items-center">
              <div class="bg-green-500 rounded-full h-4 w-4 -ml-2 mr-2"></div>
              <span class="font-semibold text-gray-700">Approved</span>
              <span class="text-sm text-gray-500 ml-2">2025-02-26 18:10</span>
            </div>
            <p class="ml-8 text-gray-600">Expense approved by Manager John Smith.</p>
          </li>
          <li class="mb-4">
            <div class="flex items-center">
              <div class="bg-red-500 rounded-full h-4 w-4 -ml-2 mr-2"></div>
              <span class="font-semibold text-gray-700">Updated</span>
              <span class="text-sm text-gray-500 ml-2">2025-02-26 18:30</span>
            </div>
            <p class="ml-8 text-gray-600">Additional line items were added to the expense.</p>
          </li>
        </ul>
      </div>
    </div>