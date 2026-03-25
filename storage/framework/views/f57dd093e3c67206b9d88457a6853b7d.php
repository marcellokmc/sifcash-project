<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'Erreur'); ?> - <?php echo e(config('app.name', 'SIFCash-Burkina')); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
        .border-left-danger {
            border-left: 4px solid #e74a3b !important;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        .border-left-secondary {
            border-left: 4px solid #858796 !important;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
    
    <!-- Styles personnalisés -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    
    <!-- Contenu principal -->
    <?php echo $__env->yieldContent('content'); ?>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
</body>
</html><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/layouts/error.blade.php ENDPATH**/ ?>