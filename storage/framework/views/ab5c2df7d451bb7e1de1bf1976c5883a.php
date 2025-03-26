<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POS System</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class', // Enables dark mode via classes
    }
  </script>

  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/pos.css">
  <link rel="stylesheet" type="text/css" href="assets/css/shift.css">
  <!-- Alpine.js for Interactivity -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
      x-init="document.documentElement.classList.toggle('dark', darkMode)"
      :class="darkMode ? 'dark' : ''"
      class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">

  <!-- Main Content -->
  <div>
    <?php echo $__env->yieldContent('content'); ?>
  </div>

  <!-- Dark Mode Toggle Script -->
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('themeSwitcher', () => ({
        toggleTheme() {
          this.darkMode = !this.darkMode;
          localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', this.darkMode);
        }
      }));
    });
  </script>

</body>
</html>
<?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/layouts/poss.blade.php ENDPATH**/ ?>