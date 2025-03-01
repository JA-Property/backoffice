<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome (Dev CDN) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js"
    crossorigin="anonymous"></script>
  <!-- Alpine.js (for the x-data logic) -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

  <!-- Include jQuery and jQuery UI CSS/JS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

  <!-- Include Toastr CSS/JS if not already included -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


</head>
<!-- Custom CSS for toast margin below global header -->
<style>
  .toast {
    margin-top: 50px !important;
  }
</style>

<body class="bg-gray-100 text-gray-800 min-h-screen flex">
  <?php
  require_once("GlobalSidebar.php");
  ?>
  <!-- OVERLAY (mobile) -->
  <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
  <!-- MAIN CONTENT WRAPPER -->
  <div class="flex-1 flex flex-col md:ml-64">
    <?php
    require_once("GlobalHeader.php");
    ?>
    <!-- View Content -->
    <main id="viewContent" class="flex-1">
      <!-- Outer Container -->
      <div class="flex flex-col p-4 space-y-6 pb-16">
        <?php echo $content; ?> <!-- Inject the dynamic content here -->
      </div>
    </main>
    <?php
    require_once("GlobalFooter.php");
    ?>
  </div>
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
  </script>
</body>

</html>