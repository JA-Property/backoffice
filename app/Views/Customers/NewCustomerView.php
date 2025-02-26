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
          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
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
          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
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
<!-- Main Content -->
<div class="p-6 bg-white rounded-lg shadow-lg">
  <!-- Header Section -->
  <div class="flex items-start justify-between border-b pb-4 mb-6">
    <!-- Left: Title + Description -->
    <div class="space-y-1">
      <h2 class="text-xl font-semibold text-gray-800 flex items-center">
        <i class="fas fa-user-plus text-gray-800 mr-2"></i>
        New Customer
      </h2>
      <p class="text-sm text-gray-500">
        Fill out the form below to add a new customer. Fields marked with an <span class="text-red-500">asterisk (*)</span> are required.
      </p>
    </div>
    <!-- Right: Help Button -->
    <div>
      <a href="#">
        <i class="fas fa-question-circle"></i>
      </a>
    </div>
  </div>
  <form action="/create-customer" method="POST" class="space-y-6">

    <!-- Customer Basic Info -->
    <div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    
    
    <!-- Left Section / Type, Name -->
       <div>
  <!-- Customer Type and Conditional Fields -->
  <div class="mb-4" x-data="{ customerType: 'Residential' }">
  <!-- Customer Type Selection -->
  <label class="block text-sm font-medium text-gray-700 mb-1">Customer Type</label>
  <div class="flex space-x-4">
    <!-- Residential -->
    <label 
      :class="customerType === 'Residential' ? 'border-green-500' : 'border-gray-300'" 
      class="cursor-pointer border-2 rounded-md p-3 flex items-center space-x-2 transition-colors duration-150"
    >
      <input 
        type="radio" 
        name="customer_type" 
        value="Residential" 
        x-model="customerType" 
        class="hidden" 
        required
      >
      <i class="fas fa-home text-gray-600"></i>
      <span class="text-gray-700">Residential</span>
    </label>
    <!-- Commercial -->
    <label 
      :class="customerType === 'Commercial' ? 'border-green-500' : 'border-gray-300'" 
      class="cursor-pointer border-2 rounded-md p-3 flex items-center space-x-2 transition-colors duration-150"
    >
      <input 
        type="radio" 
        name="customer_type" 
        value="Commercial" 
        x-model="customerType" 
        class="hidden" 
        required
      >
      <i class="fas fa-building text-gray-600"></i>
      <span class="text-gray-700">Commercial</span>
    </label>
    <!-- Other -->
    <label 
      :class="customerType === 'Other' ? 'border-green-500' : 'border-gray-300'" 
      class="cursor-pointer border-2 rounded-md p-3 flex items-center space-x-2 transition-colors duration-150"
    >
      <input 
        type="radio" 
        name="customer_type" 
        value="Other" 
        x-model="customerType" 
        class="hidden" 
        required
      >
      <i class="fas fa-question-circle text-gray-600"></i>
      <span class="text-gray-700">Other</span>
    </label>
  </div>


  <!-- For Residential: Show a first name-->
  <div class="mt-4" x-show="customerType === 'Residential'">
    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
    <input 
      type="text" 
      name="first_name" 
      id="first_name" 
      placeholder="First Name" 
      required 
      class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
    >
  </div>

    <!-- For Residential: Show a last name-->
    <div class="mt-4" x-show="customerType === 'Residential'">
    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
    <input 
      type="text" 
      name="last_name" 
      id="last_name" 
      placeholder="Last Name" 
      required 
      class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
    >
  </div>

  <!-- For Commercial or Other: Show Company Name and Primary Contact fields -->
  <div class="mt-4" x-show="customerType !== 'Residential'">
  <div>
        <label for="sub_type" class="block text-sm font-medium text-gray-700">Sub Type</label>
        <select 
          name="sub_type" 
          id="sub_type" 
          required 
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
        >
          <option value="" disabled selected>Select Sub Type</option>
          <option value="Office">Office</option>
          <option value="Retail">Retail</option>
          <option value="Industrial">Industrial</option>
          <option value="Other">Other</option>
        </select>
      </div>

    <!-- Company Name -->
    <div>
      <label for="display_name" class="block text-sm font-medium text-gray-700 mt-4">Company Name</label>
      <input 
        type="text" 
        name="display_name" 
        id="display_name" 
        placeholder="Business or Display Name" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
    <!-- Primary Contact First Name -->
    <div class="mt-4">
      <label for="primary_first_name" class="block text-sm font-medium text-gray-700">Primary Contact First Name</label>
      <input 
        type="text" 
        name="primary_first_name" 
        id="primary_first_name" 
        placeholder="First Name" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
    <!-- Primary Contact Last Name -->
    <div class="mt-4">
      <label for="primary_last_name" class="block text-sm font-medium text-gray-700">Primary Contact Last Name</label>
      <input 
        type="text" 
        name="primary_last_name" 
        id="primary_last_name" 
        placeholder="Last Name" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
  </div>
  
<!-- Hidden Display Name (auto-combined) -->
<input type="hidden" name="display_name" :value="primaryFirstName + ' ' + primaryLastName">
</div>


<!-- Referral Source (Alpine.js for show/hide) -->
<div 
  x-data="{ referralSource: '' }" 
  class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start"
>
  <!-- Left Column: Dropdown -->
  <div>
    <label for="referral_source" class="block text-sm font-medium text-gray-700">
      Referral Source
    </label>
    <select
      id="referral_source"
      name="referral_source"
      x-model="referralSource"
      required
      class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
    >
      <option value="" disabled selected>Select a referral source</option>
      <option value="Internet">Internet</option>
      <option value="Friend">Friend</option>
      <option value="Other">Other</option>
    </select>
  </div>

  <!-- Right Column: "Other" input (hidden unless "Other" is selected) -->
  <div 
    x-show="referralSource === 'Other'"
    x-cloak
  >
    <label for="referral_other" class="block text-sm font-medium text-gray-700">
      Please Specify
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

<div x-data="{ billingFormat: '' }">
  <!-- First Row: Billing format + Conditional Field -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
    
    <!-- Billing format -->
    <div>
      <!-- Removed mt-4 from here -->
      <label for="billing_bill_format" class="block text-sm font-medium text-gray-700">
        Billing Format
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

    <!-- Conditional Field on the Right -->
    <div class="flex items-end"> 
      <!-- Only show the button if 'Statement' is selected -->
      <div x-show="billingFormat === 'Statement'" x-cloak class="w-full">
        <!-- If you still want a label for accessibility, you can make it sr-only -->
        <label for="credit_check" class="sr-only">Perform Credit Check</label>
        <button 
          id="credit_check"
          type="button" 
          class="inline-flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 
                 text-white font-medium py-2 rounded-md"
        >
          <!-- Example heroicon (Outline: Check Circle) -->
          <svg 
            class="h-5 w-5 mr-2" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="2" 
            viewBox="0 0 24 24" 
            xmlns="http://www.w3.org/2000/svg"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              d="M9 12l2 2l4 -4m-7 7a9 9 0 1118 0a9 9 0 01-18 0z"
            />
          </svg>
          Perform Credit Check
        </button>
      </div>
      <!-- If Other, show the Other Billing format dropdown -->
      <div x-show="billingFormat === 'Other'" x-cloak class="w-full">
        <label for="billing_other" class="block text-sm font-medium text-gray-700">
          Other Billing Format
        </label>
        <select
          id="billing_other"
          name="billing_other"
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
        >
          <option value="" disabled selected>Select an option</option>
          <option value="CustomPlanA">Custom Plan A</option>
          <option value="CustomPlanB">Custom Plan B</option>
          <option value="CustomPlanC">Custom Plan C</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Second Row: Postpay Additional Fields -->
  <div 
    x-show="billingFormat === 'Post'"
    x-cloak
    class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6"
  >
    <!-- Terms -->
    <div>
      <label for="terms" class="block text-sm font-medium text-gray-700">Terms</label>
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
      <label for="statement_period" class="block text-sm font-medium text-gray-700">
        Statement Period
      </label>
      <select 
        id="statement_period" 
        name="statement_period" 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
        <option value="" disabled selected>Select Period</option>
        <option value="Weekly">Weekly</option>
        <option value="Bi-Weekly">Bi-Weekly</option>
        <option value="Monthly">Monthly</option>
      </select>
    </div>
    <!-- Delivery Method -->
    <div>
      <label for="delivery_method" class="block text-sm font-medium text-gray-700">
        Delivery Method
      </label>
      <select 
        id="delivery_method" 
        name="delivery_method" 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
        <option value="" disabled selected>Select Delivery</option>
        <option value="Email">Email</option>
        <option value="Mail">Mail</option>
        <option value="Both">Both</option>
      </select>
    </div>
  </div>
</div>


<div x-data="{ taxExempt: 'No', discountType: 'Flat', discountAmount: '' }">
  <div class="mt-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
      <!-- Tax Exempt Section -->
      <div>
        <label for="tax_exempt" class="block text-sm font-medium text-gray-700">Tax Exempt</label>
        <select 
          id="tax_exempt" 
          name="tax_exempt" 
          x-model="taxExempt" 
          class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
        >
          <option value="No">No</option>
          <option value="Yes">Yes</option>
        </select>
        <div x-show="taxExempt === 'Yes'" x-cloak class="mt-2">
          <label for="tax_exempt_id" class="block text-sm font-medium text-gray-700 mt-4">Tax Exempt ID</label>
          <input 
            type="text" 
            id="tax_exempt_id" 
            name="tax_exempt_id" 
            placeholder="Enter Tax Exempt ID" 
            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
          >
        </div>
      </div>

      <!-- Discount Section -->
      <div>
        <label for="discount_amount" class="block text-sm font-medium text-gray-700">Discount</label>
        <div class="flex space-x-2 mt-1">
          <input 
            type="number" 
            id="discount_amount" 
            name="discount_amount" 
            x-model="discountAmount" 
            placeholder="0" 
            class="block w-1/2 border border-gray-300 rounded-md p-2 text-sm"
          >
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
</div>


       </div>

    

  


<!-- Right Section / Contact-->
<div class="">
  <h3 class="text-xl font-bold text-gray-800 mb-4">Contact Information</h3>
  
  <!-- Personal Information -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Primary Email -->
  <div>
    <label for="primary_email" class="block text-sm font-medium text-gray-700">Email</label>
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
    <label for="primary_phone" class="block text-sm font-medium text-gray-700">Phone</label>
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
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    <!-- Street Address (Full width) -->
    <div class="md:col-span-2">
      <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
      <input 
        type="text" 
        name="billing_address" 
        id="billing_address" 
        placeholder="123 Main St" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
    <!-- City -->
    <div>
      <label for="billing_city" class="block text-sm font-medium text-gray-700">City</label>
      <input 
        type="text" 
        name="billing_city" 
        id="billing_city" 
        placeholder="City" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
    <!-- State / Province -->
    <div>
      <label for="billing_state" class="block text-sm font-medium text-gray-700">State / Province</label>
      <input 
        type="text" 
        name="billing_state" 
        id="billing_state" 
        placeholder="State" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
    <!-- ZIP / Postal Code -->
    <div>
      <label for="billing_zip" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
      <input 
        type="text" 
        name="billing_zip" 
        id="billing_zip" 
        placeholder="ZIP" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
    </div>
    <!-- Country -->
    <div>
      <label for="billing_country" class="block text-sm font-medium text-gray-700">Country</label>
      <select 
        name="billing_country" 
        id="billing_country" 
        required 
        class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"
      >
        <option value="" disabled selected>Select Country</option>
        <option value="USA">United States</option>
        <option value="CAN">Canada</option>
        <option value="UK">United Kingdom</option>
        <option value="AUS">Australia</option>
        <!-- Add more countries as needed -->
      </select>
    </div>
    
  </div>
  <!-- Communication Preferences (3 inline checkboxes) -->
<div class="mt-4">
  <span class="block text-sm font-medium text-gray-700">Communication Preferences</span>
  <div class="flex items-center space-x-6 mt-2">
    <label class="inline-flex items-center">
      <input type="checkbox" name="comm_email" id="comm_email" class="form-checkbox text-blue-600">
      <span class="ml-2 text-sm text-gray-700">Email</span>
    </label>
    <label class="inline-flex items-center">
      <input type="checkbox" name="comm_sms" id="comm_sms" class="form-checkbox text-blue-600">
      <span class="ml-2 text-sm text-gray-700">SMS</span>
    </label>
    <label class="inline-flex items-center">
      <input type="checkbox" name="comm_phone" id="comm_phone" class="form-checkbox text-blue-600">
      <span class="ml-2 text-sm text-gray-700">Phone</span>
    </label>
  </div>
</div>

    <!-- Additional Notes -->
    <div>
      <label for="notes" class="block text-sm font-medium text-gray-700 mt-4">Additional Notes</label>
      <textarea name="notes" id="notes" rows="4" placeholder="Enter any additional information here..." class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm"></textarea>
    </div>
</div>


</div>

    <!-- Submit Button -->
    <div class="mt-4">
      <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded flex items-center justify-center">
        <i class="fa fa-plus mr-2"></i> Create Customer
      </button>
    </div>
  </form>
</div>
