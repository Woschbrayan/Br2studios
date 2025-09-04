<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Br2Studios Admin'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dark-theme-fixes.css">
    <link rel="stylesheet" href="../assets/css/admin-imoveis.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .logo-img {
            width: 40px;
            height: 40px;
        }
        
        .logo h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .nav-section {
            margin: 20px 0;
        }
        
        .nav-section-title {
            padding: 0 25px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #999999;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .nav-item {
            display: block;
            padding: 12px 25px;
            color: #cccccc;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover,
        .nav-item.active {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            border-left-color: #666666;
        }
        
        .nav-item i {
            width: 20px;
            margin-right: 12px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #666666, #999999);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
            transform: translateY(-2px);
        }
        
        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .table-header h3 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-control.error {
            border-color: #e74c3c;
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
        
        /* Dashboard Specific Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: linear-gradient(135deg, #666666, #999999);
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-content h3 {
            font-size: 2rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-content p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-content small {
            font-size: 0.7rem;
            color: #999;
        }
        
        /* Activity Cards */
        .recent-activity {
            margin-top: 40px;
        }
        
        .activity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
        }
        
        .content-body {
            padding: 25px;
        }
        
        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .list-item:last-child {
            border-bottom: none;
        }
        
        .item-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .item-info p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }
        
        .item-actions {
            display: flex;
            gap: 8px;
        }
        
        .btn-edit {
            background: #666666;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            background: #999999;
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ccc;
        }
        
        /* Responsive for activity grid */
        @media (max-width: 1024px) {
            .activity-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .table-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background: #f8f9fa;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.9rem;
        }
        
        .table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .form-container h2 {
            margin: 0 0 10px 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-container p {
            margin: 0 0 20px 0;
            color: #666;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #666;
            box-shadow: 0 0 0 3px rgba(102, 102, 102, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Status Badge Styles */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-ativo {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inativo {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-disponivel {
            background: #d4edda;
            color: #155724;
        }
        
        .status-vendido {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-reservado {
            background: #fff3cd;
            color: #856404;
        }
        
        /* Button Sizes */
        .btn-sm {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
                margin-left: 0;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .page-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
