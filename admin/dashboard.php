<?php
require_once '../backend/config/dbc.php';
require_once '../backend/function/functions.php';

requireAdminAuth();

// Handle CRUD operations
$message = '';
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_position'])) {
        if (addPosition($_POST, $connection)) {
            $message = "Position added successfully!";
        } else {
            $message = "Error adding position.";
        }
    } elseif (isset($_POST['update_position'])) {
        if (updatePosition($_POST['id'], $_POST, $connection)) {
            $message = "Position updated successfully!";
        } else {
            $message = "Error updating position.";
        }
    }
}

if ($action === 'delete' && $id) {
    if (deletePosition($id, $connection)) {
        $message = "Position deleted successfully!";
    } else {
        $message = "Error deleting position.";
    }
}

$positions = getAllPositionsAdmin($connection);
$editPosition = null;

if ($action === 'edit' && $id) {
    $editPosition = getPositionById($connection, $id);
}

session_start();
$adminName = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CybertronLabs</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }

        .section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h2 {
            color: #333;
            font-size: 1.25rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background: #c53030;
        }

        .btn-success {
            background: #38a169;
            color: white;
        }

        .btn-success:hover {
            background: #2f855a;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            padding: 1.5rem;
        }

        .form-grid.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .message {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 0;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close {
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CybertronLabs Admin Panel</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($adminName); ?></span>
            <a href="/admin?logout=1" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($positions); ?></div>
                <div class="stat-label">Total Positions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($positions, fn($p) => $p['status'] === 'active')); ?></div>
                <div class="stat-label">Active Positions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($positions, fn($p) => $p['status'] === 'inactive')); ?></div>
                <div class="stat-label">Inactive Positions</div>
            </div>
        </div>

        <!-- Positions Table -->
        <div class="section">
            <div class="section-header">
                <h2>Job Positions</h2>
                <button class="btn btn-primary" onclick="openModal('addModal')">Add New Position</button>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Position Name</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($positions as $position): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($position['name']); ?></strong>
                                <br>
                                <small><?php echo htmlspecialchars(substr($position['description'], 0, 100)) . '...'; ?></small>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $position['status']; ?>">
                                    <?php echo ucfirst($position['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($position['created_at'])); ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $position['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="?action=delete&id=<?php echo $position['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Position Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $editPosition ? 'Edit Position' : 'Add New Position'; ?></h2>
                <span class="close" onclick="closeModal('addModal')">&times;</span>
            </div>
            
            <form method="POST">
                <?php if ($editPosition): ?>
                    <input type="hidden" name="id" value="<?php echo $editPosition['id']; ?>">
                <?php endif; ?>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Position Name</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo $editPosition ? htmlspecialchars($editPosition['name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image URL</label>
                        <input type="url" id="image" name="image" 
                               value="<?php echo $editPosition ? htmlspecialchars($editPosition['image']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-grid full">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required><?php echo $editPosition ? htmlspecialchars($editPosition['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="roles">Roles (one per line)</label>
                        <textarea id="roles" name="roles"><?php echo $editPosition ? implode("\n", $editPosition['roles']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="what_you_do">What You'll Do</label>
                        <textarea id="what_you_do" name="what_you_do"><?php echo $editPosition ? htmlspecialchars($editPosition['what_you_do']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="who_you_are">Who You Are</label>
                        <textarea id="who_you_are" name="who_you_are"><?php echo $editPosition ? htmlspecialchars($editPosition['who_you_are']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="what_we_offer">What We Offer</label>
                        <textarea id="what_we_offer" name="what_we_offer"><?php echo $editPosition ? htmlspecialchars($editPosition['what_we_offer']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="extras">Extras (optional)</label>
                        <textarea id="extras" name="extras"><?php echo $editPosition ? htmlspecialchars($editPosition['extras']) : ''; ?></textarea>
                    </div>
                    
                    <?php if ($editPosition): ?>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="active" <?php echo $editPosition['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $editPosition['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="padding: 1.5rem; border-top: 1px solid #e9ecef;">
                    <button type="submit" name="<?php echo $editPosition ? 'update_position' : 'add_position'; ?>" class="btn btn-success">
                        <?php echo $editPosition ? 'Update Position' : 'Add Position'; ?>
                    </button>
                    <button type="button" class="btn" onclick="closeModal('addModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Auto-open edit modal if editing
        <?php if ($editPosition): ?>
            openModal('addModal');
        <?php endif; ?>
    </script>
</body>
</html>