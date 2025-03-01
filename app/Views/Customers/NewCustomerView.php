<!-- Breadcrumb Container -->
<div class="bg-white shadow-sm rounded-md p-3 md:p-4">
  <nav class="text-sm" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
      <!-- Home -->
      <li>
        <a href="/" class="text-blue-600 hover:underline hover:text-blue-800 transition">
          Home
        </a>
      </li>
      <!-- Separator -->
      <li class="text-gray-400">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"></path>
        </svg>
      </li>
      <!-- Customers -->
      <li>
        <a href="/staff/customers" class="text-blue-600 hover:underline hover:text-blue-800 transition">
          Customers
        </a>
      </li>
      <!-- Separator -->
      <li class="text-gray-400">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a 1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"></path>
        </svg>
      </li>
      <!-- Current page -->
      <li class="text-gray-500" aria-current="page">
        New Customer
      </li>
    </ol>
  </nav>
</div>

<!-- Main Content -->
<div class="p-6 bg-white rounded-lg shadow-lg">
  <!-- Header Section -->
  <div class="flex items-start justify-between border-b pb-4 mb-6">
    <!-- Left: Title + Description -->
    <div class="space-y-1">
      <h2 class="text-xl font-semibold text-gray-800 flex items-center">
        <i class="fas fa-user-plus text-gray-800 mr-2"></i>
        Customer Details
      </h2>
      <p class="text-sm text-gray-500">
        Fill out the form below to add a new customer. Fields marked with an <span
          class="text-red-500">asterisk(*)</span> are required.
      </p>
    </div>
    <!-- Right: Help Button -->
    <div>
      <i class="fas fa-question-circle"></i>
    </div>
  </div>

  <!-- Fix #1: Ensure only visible fields are actually required by toggling `required` with Alpine. -->
  <form action="/customers/create" method="POST">
    <!-- Customer Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-6">
      <div 
        x-data="{ 
          // Customer Type
          customerType: 'Residential',
          // Referral Source
          referralSource: '',
          // Billing Format
          billingFormat: '',
          // Tax Exempt
          taxExempt: 'No',
          // Discount
          discountAmount: 0,
          discountType: 'Flat',

          // For handling names (Residential, Commercial, Other)
          first_name: '',
          last_name: '',
          primary_first_name: '',
          primary_last_name: '',
          sub_type: '',

          // For display_name if needed
          business_display_name: ''
        }" 
        class="w-full"
      >
        <!-- Left Section -->
        <div>
          <!-- Customer Type and Name -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Customer Type <span class="text-red-500">*</span>
            </label>

            <!-- Customer Type Selection Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <!-- Residential -->
              <label 
                :class="customerType === 'Residential' ? 'border-green-600' : 'border-gray-300'"
                class="cursor-pointer border-2 rounded-md p-3 flex flex-col items-center justify-center text-center space-y-2 transition-colors duration-150"
              >
                <input 
                  type="radio" 
                  name="customer_type" 
                  value="Residential" 
                  x-model="customerType" 
                  class="hidden"
                />
                <i class="fas fa-home text-gray-600 text-lg"></i>
                <span class="text-gray-700 break-words">Residential</span>
              </label>

              <!-- Commercial -->
              <label 
                :class="customerType === 'Commercial' ? 'border-green-600' : 'border-gray-300'"
                class="cursor-pointer border-2 rounded-md p-3 flex flex-col items-center justify-center text-center space-y-2 transition-colors duration-150"
              >
                <input 
                  type="radio" 
                  name="customer_type" 
                  value="Commercial" 
                  x-model="customerType" 
                  class="hidden"
                />
                <i class="fas fa-building text-gray-600 text-lg"></i>
                <span class="text-gray-700 break-words">Commercial</span>
              </label>

              <!-- Other -->
              <label 
                :class="customerType === 'Other' ? 'border-green-600' : 'border-gray-300'"
                class="cursor-pointer border-2 rounded-md p-3 flex flex-col items-center justify-center text-center space-y-2 transition-colors duration-150"
              >
                <input 
                  type="radio" 
                  name="customer_type" 
                  value="Other" 
                  x-model="customerType" 
                  class="hidden"
                />
                <i class="fas fa-question-circle text-gray-600 text-lg"></i>
                <span class="text-gray-700 break-words">Other</span>
              </label>
            </div>

            <!-- CONDITIONAL FIELDS BELOW -->

            <!-- For Residential -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
              <div class="mt-4" x-show="customerType === 'Residential'">
                <label for="res_first_name" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">First Name *</span>
                </label>
                <input 
                  type="text" 
                  name="res_first_name" 
                  id="res_first_name" 
                  placeholder="First Name"
                  :required="customerType === 'Residential'"
                  x-model="first_name" 
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm" 
                />
              </div>

              <div class="mt-4" x-show="customerType === 'Residential'">
                <label for="res_last_name" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Last Name *</span>
                </label>
                <input 
                  type="text" 
                  name="res_last_name" 
                  id="res_last_name" 
                  placeholder="Last Name" 
                  :required="customerType === 'Residential'"
                  x-model="last_name" 
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm" 
                />
              </div>
            </div>

            <!-- For Commercial -->
            <div class="mt-4" x-show="customerType === 'Commercial'">
              <!-- Company Name -->
              <div class="mt-4">
                <label for="company_name" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Company Name *</span>
                </label>
                <input 
                  type="text" 
                  name="company_name" 
                  id="company_name"
                  placeholder="Business or Display Name"
                  :required="customerType === 'Commercial'"
                  x-model="business_display_name"
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm" 
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
                <!-- Contact First Name -->
                <div class="mt-4">
                  <label for="primary_first_name_commercial" class="block text-sm font-medium text-gray-700">
                  <span class="text-red-500">Contact First Name *</span>
                  </label>
                  <input 
                    type="text" 
                    name="com_first_name" 
                    id="primary_first_name_commercial"
                    placeholder="First Name"
                    :required="customerType === 'Commercial'"
                    x-model="primary_first_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                  />
                </div>

                <!-- Contact Last Name -->
                <div class="mt-4">
                  <label for="primary_last_name_commercial" class="block text-sm font-medium text-gray-700">
                  <span class="text-red-500">Contact Last Name *</span>
                  </label>
                  <input 
                    type="text" 
                    name="com_last_name" 
                    id="primary_last_name_commercial"
                    placeholder="Last Name"
                    :required="customerType === 'Commercial'"
                    x-model="primary_last_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                  />
                </div>
              </div>
            </div>

            <!-- For Other -->
            <div x-show="customerType === 'Other'">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
                <div class="mt-4">
                  <label for="sub_type" class="block text-sm font-medium text-gray-700">
                    Sub Type
                  </label>
                  <select 
                    name="org_type" 
                    id="org_type"
                    :required="customerType === 'Other'"
                    x-model="sub_type"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                  >
                    <option value="" disabled selected>Select Sub Type</option>
                    <option value="Office">Office</option>
                    <option value="Retail">Retail</option>
                    <option value="Industrial">Industrial</option>
                    <option value="Other">Other</option>
                  </select>
                </div>

                <!-- Organization Name -->
                <div class="mt-4">
                  <label for="display_name_other" class="block text-sm font-medium text-gray-700">
                    Organization Name <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="organization_name" 
                    id="display_name_other"
                    placeholder="Business or Display Name"
                    :required="customerType === 'Other'"
                    x-model="business_display_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
                <!-- Contact First Name -->
                <div class="mt-4">
                  <label for="primary_first_name_other" class="block text-sm font-medium text-gray-700">
                    Contact First Name <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="oth_first_name" 
                    id="primary_first_name_other"
                    placeholder="First Name"
                    :required="customerType === 'Other'"
                    x-model="primary_first_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                  />
                </div>

                <!-- Contact Last Name -->
                <div class="mt-4">
                  <label for="primary_last_name_other" class="block text-sm font-medium text-gray-700">
                    Contact Last Name <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="oth_last_name" 
                    id="primary_last_name_other"
                    placeholder="Last Name"
                    :required="customerType === 'Other'"
                    x-model="primary_last_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Referral Source -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
            <!-- Left Column: Dropdown -->
            <div>
              <label for="referral_source" class="block text-sm font-medium text-gray-700">
                Referral Source
              </label>
              <select 
                id="referral_source" 
                name="referral_source" 
                x-model="referralSource" 
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
              >
                <option value="" disabled selected>Select</option>
                <option value="Internet">Google</option>
                <option value="Friend">Friend</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <!-- Right Column: "Other" input -->
            <div x-show="referralSource === 'Other'" x-cloak>
              <label for="referral_other" class="block text-sm font-medium text-gray-700">
              <span class="text-red-500">Please Specify *</span>
              </label>
              <input 
                type="text" 
                name="referral_other" 
                id="referral_other"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                placeholder="Enter referral details" 
              />
            </div>
          </div>

          <!-- Billing Format -->
          <div class="mt-4">
            <!-- Two-column grid: one for the select, one for the conditional element -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
              <!-- Column 1: Billing Format Dropdown -->
              <div>
                <label for="billing_bill_format" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Billing Format *</span>
                </label>
                <select 
                  name="billing_bill_format" 
                  id="billing_bill_format" 
                  x-model="billingFormat" 
                  required
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                >
                  <option value="" disabled selected>Select Billing Format</option>
                  <option value="Visit">Invoice</option>
                  <option value="Statement">Statement</option>
                </select>
              </div>

              <!-- Column 2: Conditional Button -->
              <div>
                <!-- Button appears only if billingFormat === 'Statement' -->
                <div x-show="billingFormat === 'Statement'" x-cloak>
                  <label for="credit_check" class="sr-only">Credit Check</label>
                  <button 
                    id="credit_check" 
                    type="button"
                    class="inline-flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md text-sm"
                  >
                    <i class="fas fa-credit-card mr-2"></i>
                    Credit Check
                  </button>
                </div>
              </div>
            </div>

            <!-- Credit check popup script (kept from original) -->
            <script>
              document.addEventListener('alpine:init', () => {
                const creditCheckBtn = document.getElementById('credit_check');
                if (creditCheckBtn) {
                  creditCheckBtn.addEventListener('click', function () {
                    // Example popup dimensions & centered positioning
                    const popupWidth = 500;
                    const popupHeight = 400;
                    const left = (screen.width - popupWidth) / 2;
                    const top = (screen.height - popupHeight) / 2;

                    window.open(
                      "/your-credit-check-url", // Replace with your actual route or URL
                      "CreditCheck",            // Window name (any string)
                      `width=${popupWidth},height=${popupHeight},top=${top},left=${left},
                  menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes`
                    );
                  });
                }
              });
            </script>

            <!-- Show Terms only if "Statement" is chosen -->
            <div 
              x-show="billingFormat === 'Statement'" 
              x-cloak
              class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6"
            >
              <!-- Terms 1 -->
              <div>
                <label for="terms" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Terms *</span>
                </label>
                <select 
                  id="terms" 
                  name="terms"
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                >
                  <option value="" disabled selected>Select Terms</option>
                  <option value="Net15">Net 15</option>
                  <option value="Net30">Net 30</option>
                  <option value="Net45">Net 45</option>
                </select>
              </div>

              <!-- Statement Period -->
              <div>
                <label for="terms2" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Statement Period *</span>
                </label>
                <select 
                  id="terms2" 
                  name="terms2"
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                >
                  <option value="" disabled selected>Select Period</option>
                  <option value="DueFirst">Due 1st day of Month</option>
                  <option value="Net30">Due 15th day of Month</option>
                  <option value="Net45">Due 31st day of Month</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Tax Exempt Section -->
          <div class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Tax Exempt Dropdown -->
              <div>
                <label for="tax_exempt" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Tax Exempt *</span>
                </label>
                <select 
                  id="tax_exempt" 
                  name="tax_exempt" 
                  x-model="taxExempt"
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
                >
                  <option value="No">No</option>
                  <option value="Yes">Yes</option>
                </select>
              </div>

              <!-- Tax Exempt ID (Appears Only if "Yes") -->
              <div x-show="taxExempt === 'Yes'" x-cloak>
                <label for="tax_exempt_id" class="block text-sm font-medium text-gray-700">
                <span class="text-red-500">Tax Exempt ID *</span>
                </label>
                <input 
                  type="text" 
                  id="tax_exempt_id" 
                  name="tax_exempt_id" 
                  placeholder="Enter Tax Exempt ID"
                  class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm" 
                />
              </div>
            </div>
          </div>

          <!-- Discount Section -->
          <div class="mt-4">
            <label for="discount_amount" class="block text-sm font-medium text-gray-700">
              Discount
            </label>
            <div class="flex space-x-2 mt-1">
              <input 
                type="number" 
                id="discount_amount" 
                name="discount_amount" 
                x-model="discountAmount"
                placeholder="0" 
                class="block w-1/2 border border-gray-300 rounded-md p-2 text-sm" 
              />
              <select 
                id="discount_type" 
                name="discount_type" 
                x-model="discountType"
                class="block w-1/2 border border-gray-300 rounded-md p-2 text-sm"
              >
                <option value="Flat">Flat</option>
                <option value="Percent">Percent</option>
              </select>
            </div>
            <p class="mt-1 text-xs text-gray-500">
              Applies to ALL invoices by default and cannot be removed at invoice level.
            </p>
          </div>
        </div>
      </div>

      <!-- Right Section / Contact-->
      <div class="">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Contact Information</h3>

        <!-- Personal Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Primary Email -->
          <div>
            <label for="primary_email" class="block text-sm font-medium text-gray-700">
            <span class="text-red-500">Email *</span>
            </label>
            <input 
              type="email" 
              name="primary_email" 
              id="primary_email" 
              placeholder="customer@example.com" 
              required
              class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
            >
          </div>
          <!-- Primary Phone -->
          <div>
            <label for="primary_phone" class="block text-sm font-medium text-gray-700">
            <span class="text-red-500">Phone *</span>
            </label>
            <input 
              type="tel" 
              name="primary_phone" 
              id="primary_phone" 
              placeholder="(123) 456-7890" 
              required
              class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
            >
          </div>
        </div>

        <!-- Billing Address -->
        <div class="grid grid-cols-1 gap-6 mt-4">
          <!-- Address Line 1 -->
          <div>
            <label for="billing_address" class="block text-sm font-medium text-gray-700">
            <span class="text-red-500">Billing Address *</span>
            </label>
            <input 
              type="text" 
              name="billing_address" 
              id="billing_address" 
              placeholder="123 Main St" 
              required
              class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
            />
          </div>

          <!-- Address Line 2 (optional) -->
          <div>
            <label for="billing_address2" class="block text-sm font-medium text-gray-700">
              Address Line 2
            </label>
            <input 
              type="text" 
              name="billing_address2" 
              id="billing_address2"
              placeholder="Apt, Suite, Unit, etc. (optional)"
              class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
            />
          </div>

          <!-- 3-column section for City, State, ZIP on desktop -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- City -->
            <div>
              <label for="billing_city" class="block text-sm font-medium text-gray-700">
              <span class="text-red-500">City *</span>
              </label>
              <input 
                type="text" 
                name="billing_city" 
                id="billing_city" 
                placeholder="City" 
                required
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
              />
            </div>

            <!-- State -->
            <div>
              <label for="billing_state" class="block text-sm font-medium text-gray-700">
              <span class="text-red-500">State <span class="text-red-500">*</span>
              </label>
              <input 
                type="text" 
                name="billing_state" 
                id="billing_state" 
                placeholder="State" 
                required
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
              />
            </div>

            <!-- ZIP -->
            <div>
              <label for="billing_zip" class="block text-sm font-medium text-gray-700">
                ZIP Code <span class="text-red-500">*</span>
              </label>
              <input 
                type="text" 
                name="billing_zip" 
                id="billing_zip" 
                placeholder="ZIP" 
                required
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
              />
            </div>
          </div>
        </div>

        <!-- Communication Preferences (3 inline checkboxes) -->
        <div class="mt-4">
          <span class="block text-sm font-medium text-gray-700">
            Communication Preferences <span class="text-red-500">*</span>
          </span>
          <div class="flex items-center space-x-6 mt-2">
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                name="comm_email" 
                id="comm_email" 
                class="form-checkbox text-blue-600"
              >
              <span class="ml-2 text-sm text-gray-700">Email</span>
            </label>
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                name="comm_sms" 
                id="comm_sms" 
                class="form-checkbox text-blue-600"
              >
              <span class="ml-2 text-sm text-gray-700">SMS</span>
            </label>
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                name="comm_phone" 
                id="comm_phone" 
                class="form-checkbox text-blue-600"
              >
              <span class="ml-2 text-sm text-gray-700">Phone</span>
            </label>
          </div>
        </div>

        <!-- Additional Notes -->
        <div>
          <label for="notes" class="block text-sm font-medium text-gray-700 mt-4">
            Additional Notes
          </label>
          <textarea 
            name="notes" 
            id="notes" 
            rows="4" 
            placeholder="Enter any additional information here..."
            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          ></textarea>
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-4">
      <button 
        type="submit"
        class="w-full bg-green-600 hover:bg-green-600 text-white py-2 rounded flex items-center justify-center"
      >
        <i class="fa fa-plus mr-2"></i> Create Customer
      </button>
    </div>
  </form>
</div>
