<?php
// app/Views/Services/NewService.php
?>
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
  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Toastr CSS/JS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <style>
    /* Hide non-active steps */
    .step { display: none; }
    .step.active { display: block; }
    /* Service Option Card styling */
    .service-option {
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      padding: 1rem;
      cursor: pointer;
      transition: border-color 0.2s, background-color 0.2s;
      text-align: center;
    }
    .service-option.selected {
      border-color: #10B981; /* Tailwind green-500 */
      background-color: #ECFDF5; /* Tailwind green-50 */
    }
    .service-option:hover {
      border-color: #10B981;
    }
    /* Progress Bar styling */
    #progressBar {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1.5rem;
    }
    .progress-step {
      text-align: center;
      cursor: pointer;
      flex: 1;
    }
    .progress-step .circle {
      width: 2rem;
      height: 2rem;
      margin: 0 auto;
      border-radius: 50%;
      border: 2px solid #cbd5e0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .progress-step.active .circle, 
    .progress-step.completed .circle {
      border-color: #10B981;
      font-weight: bold;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <!-- Progress Indicator (will be built dynamically) -->
    <div id="progressBar"></div>

    <form id="serviceWizardForm" action="/services/new" method="POST">
      <div id="wizardSteps">
        <!-- Step 0: Customer & Property Info with Service Selection -->
        <div class="step active" data-step="0" data-title="Customer Info">
          <h2 class="text-2xl font-bold mb-4">Customer &amp; Property Information</h2>
          <div class="mb-4 p-4 border rounded bg-gray-50">
            <p><strong>Customer Name:</strong> John Doe</p>
            <p><strong>Property ID:</strong> <?= htmlspecialchars($_GET['id'] ?? '12345', ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Address:</strong> 123 Main St, Anytown, USA</p>
          </div>

          <h3 class="text-xl font-semibold mt-6 mb-4">Select Service Type</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="service-option" data-value="service_property">
              <i class="fas fa-building fa-2x mb-2"></i>
              <h4 class="font-bold">Service Property</h4>
              <p class="text-sm text-gray-600">Manage your service property details.</p>
            </div>
            <div class="service-option" data-value="managed_property">
              <i class="fas fa-cogs fa-2x mb-2"></i>
              <h4 class="font-bold">Managed Property</h4>
              <p class="text-sm text-gray-600">Get comprehensive managed property support.</p>
            </div>
          </div>
          <div class="flex justify-end mt-6">
            <button type="button" id="nextFromStep0" class="next-step px-4 py-2 bg-blue-600 text-white rounded">Next</button>
          </div>
        </div>

        <!-- Step 1: Fixed common step (Before dynamic service steps) -->
        <div class="step" data-step="1" data-title="Property Verification">
          <h2 class="text-2xl font-bold mb-4">Verify Property Information</h2>
          <div class="mb-4">
            <label class="block text-gray-700">Property ID:</label>
            <input type="text" name="property_id" value="<?= htmlspecialchars($_GET['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full p-2 border rounded" readonly>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Address:</label>
            <input type="text" name="property_address" value="123 Main St, Anytown, USA" class="w-full p-2 border rounded">
          </div>
        </div>

        <!-- Final Step: Fixed common step after dynamic service steps -->
        <div class="step" data-step="final" data-title="Review &amp; Confirm">
          <h2 class="text-2xl font-bold mb-4">Review &amp; Confirm</h2>
          <p>Please review your selections and details before finishing.</p>
          <!-- You can include a summary of entered information here -->
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div id="navigationButtons" class="mt-6 flex justify-between">
        <button type="button" id="prevStep" class="prev-step px-4 py-2 bg-gray-600 text-white rounded" style="display:none;">Previous</button>
        <button type="button" id="nextStep" class="next-step px-4 py-2 bg-blue-600 text-white rounded">Next</button>
        <button type="submit" id="finishStep" class="px-4 py-2 bg-green-600 text-white rounded" style="display:none;">Finish</button>
      </div>
    </form>
  </div>

  <script>
    let currentStepIndex = 0;
    let steps = []; // Array of step elements
    let selectedServices = []; // Array of selected service values

    // Build progress bar dynamically
    function buildProgressBar() {
      const progressBar = document.getElementById('progressBar');
      progressBar.innerHTML = '';
      steps.forEach((step, index) => {
        const progStep = document.createElement('div');
        progStep.classList.add('progress-step');
        if(index === currentStepIndex) progStep.classList.add('active');
        else if(index < currentStepIndex) progStep.classList.add('completed');
        progStep.setAttribute('data-step', index);
        const stepTitle = step.getAttribute('data-title') || 'Step ' + (index + 1);
        progStep.innerHTML = `<div class="circle">${index + 1}</div>
                              <div class="text-sm">${stepTitle}</div>`;
        progStep.addEventListener('click', () => {
          goToStep(index);
        });
        progressBar.appendChild(progStep);
      });
    }

    // Show a given step by index
    function showStep(index) {
      steps.forEach((step, i) => {
        step.classList.toggle('active', i === index);
      });
      currentStepIndex = index;
      // Update navigation buttons
      document.getElementById('prevStep').style.display = (currentStepIndex > 0) ? 'inline-block' : 'none';
      if (currentStepIndex === steps.length - 1) {
        document.getElementById('nextStep').style.display = 'none';
        document.getElementById('finishStep').style.display = 'inline-block';
      } else {
        document.getElementById('nextStep').style.display = 'inline-block';
        document.getElementById('finishStep').style.display = 'none';
      }
      buildProgressBar();
    }

    // Navigate directly to a step
    function goToStep(index) {
      showStep(index);
    }

    // Generate dynamic wizard steps for each selected service and insert them between fixed steps
    function generateServiceSteps() {
      const wizardStepsContainer = document.getElementById('wizardSteps');
      // Remove any previously added dynamic steps (they have a data-dynamic attribute)
      wizardStepsContainer.querySelectorAll('.step[data-dynamic]').forEach(step => step.remove());

      // Find the index of the fixed steps:
      // Step 0 is the initial step, Step 1 is the fixed "Verify Property" step,
      // and the final step is the last child (with data-step="final")
      const fixedSteps = wizardStepsContainer.querySelectorAll('.step:not([data-dynamic])');
      let finalStep = fixedSteps[fixedSteps.length - 1];

      // Insert dynamic steps after Step 1 (index 1) and before the final step.
      selectedServices.forEach((service, idx) => {
        let title = '';
        let fieldHTML = '';
        if (service === 'service_property') {
          title = 'Service Property Details';
          fieldHTML = `<label class="block text-gray-700 mb-2">Service Description (Service Property):</label>
                       <textarea name="service_description[]" class="w-full p-2 border rounded" placeholder="Enter details..."></textarea>`;
        } else if (service === 'managed_property') {
          title = 'Managed Property Details';
          fieldHTML = `<label class="block text-gray-700 mb-2">Managed Service Notes:</label>
                       <textarea name="managed_service_notes[]" class="w-full p-2 border rounded" placeholder="Enter managed service details..."></textarea>`;
        } else {
          title = service;
          fieldHTML = `<label class="block text-gray-700 mb-2">Additional Details for ${title}:</label>
                       <textarea name="details_${service}[]" class="w-full p-2 border rounded" placeholder="Enter details..."></textarea>`;
        }
        const stepDiv = document.createElement('div');
        stepDiv.classList.add('step');
        stepDiv.setAttribute('data-title', title);
        stepDiv.setAttribute('data-dynamic', 'true');
        stepDiv.innerHTML = `
          <h2 class="text-2xl font-bold mb-4">${title}</h2>
          <div class="mb-4">${fieldHTML}</div>
        `;
        // Insert the dynamic step before the final fixed step.
        finalStep.parentNode.insertBefore(stepDiv, finalStep);
      });
    }

    // Handle Next/Previous/Finish navigation
    document.getElementById('nextFromStep0').addEventListener('click', () => {
      // In step 0, capture selected services
      selectedServices = [];
      document.querySelectorAll('.service-option.selected').forEach(option => {
        selectedServices.push(option.getAttribute('data-value'));
      });
      if (selectedServices.length === 0) {
        toastr.error("Please select at least one service.");
        return;
      }
      // Move to the next fixed step (Verify Property Information) first.
      showStep(1);
    });

    document.getElementById('nextStep').addEventListener('click', () => {
      // When moving from fixed step 1 to the next step, generate dynamic steps if not already inserted.
      if (currentStepIndex === 1) {
        generateServiceSteps();
        // Rebuild steps array: include all steps in wizardSteps container.
        steps = Array.from(document.querySelectorAll('#wizardSteps .step'));
      }
      if (currentStepIndex < steps.length - 1) {
        showStep(currentStepIndex + 1);
      }
    });

    document.getElementById('prevStep').addEventListener('click', () => {
      if (currentStepIndex > 0) {
        showStep(currentStepIndex - 1);
      }
    });

    // Initialize the wizard on page load
    document.addEventListener('DOMContentLoaded', () => {
      // The wizard initially contains the fixed steps.
      steps = Array.from(document.querySelectorAll('#wizardSteps .step'));
      buildProgressBar();

      // Enable selection toggling on service cards
      document.querySelectorAll('.service-option').forEach(option => {
        option.addEventListener('click', () => {
          option.classList.toggle('selected');
        });
      });
      showStep(0);
    });
  </script>
</body>
</html>
