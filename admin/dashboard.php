<?php
session_start();
require_once '../backend/config/dbc.php';
require_once '../backend/function/functions.php';

requireAdminAuth();

// Handle job position actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_position'])) {
        $result = addJobPosition($_POST, $_FILES, $connection);
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
    
    if (isset($_POST['update_position'])) {
        $result = updateJobPosition($_POST['position_id'], $_POST, $_FILES, $connection);
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
    
    if (isset($_POST['delete_position'])) {
        $result = deleteJobPosition($_POST['position_id'], $connection);
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

// Get all job positions
$positions = getAllJobPositions($connection);
$totalPositions = count($positions);
$activePositions = count(array_filter($positions, function($p) { return $p['status'] === 'active'; }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CybertronLabs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #16161A;
            color: white;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #5658BE 0%, #16161A 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(86, 88, 190, 0.3);
            border-bottom: 2px solid #5658BE;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: #5658BE;
            color: white;
            border: 2px solid #5658BE;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
            border: 2px solid #28a745;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #000;
            border: 2px solid #ffc107;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
            border: 2px solid #dc3545;
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: #1e1e23;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            border: 1px solid #2a2a2f;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(86, 88, 190, 0.2);
        }
        
        .card h2 {
            margin-bottom: 1.5rem;
            color: #5658BE;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #2a2a2f;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #16161A;
            color: white;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5658BE;
            box-shadow: 0 0 0 3px rgba(86, 88, 190, 0.2);
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border: 2px dashed #5658BE;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(86, 88, 190, 0.1);
            color: #5658BE;
            font-weight: 600;
        }
        
        .file-upload-label:hover {
            background: rgba(86, 88, 190, 0.2);
            border-color: #4a4cb8;
        }
        
        .image-preview {
            margin-top: 1rem;
            max-width: 200px;
            border-radius: 8px;
            border: 2px solid #2a2a2f;
        }
        
        .positions-list {
            grid-column: 1 / -1;
        }
        
        .position-item {
            background: #1e1e23;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            border: 1px solid #2a2a2f;
            transition: all 0.3s ease;
        }
        
        .position-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(86, 88, 190, 0.15);
            border-color: #5658BE;
        }
        
        .position-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .position-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #5658BE;
            margin-bottom: 0.5rem;
        }
        
        .position-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .alert {
            padding: 1.2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .alert-error {
            background: rgba(244, 67, 54, 0.1);
            color: #f44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #5658BE, #4a4cb8);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            color: white;
            box-shadow: 0 8px 32px rgba(86, 88, 190, 0.3);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .status-inactive {
            background: rgba(158, 158, 158, 0.2);
            color: #9e9e9e;
            border: 1px solid rgba(158, 158, 158, 0.3);
        }
        
        .edit-form {
            display: none;
            background: #16161A;
            padding: 2rem;
            border-radius: 10px;
            margin-top: 1rem;
            border: 2px solid #5658BE;
        }
        
        .edit-form.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .position-header {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-shield-alt"></i> CybertronLabs Admin</h1>
            <div class="header-actions">
                <span>Welcome, Admin</span>
                <a href="../admin.php?logout=1" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $totalPositions ?></div>
                <div class="stat-label">Total Positions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $activePositions ?></div>
                <div class="stat-label">Active Positions</div>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="card">
                <h2><i class="fas fa-plus-circle"></i> Add New Position</h2>
                <form method="POST" enctype="multipart/form-data" action="../backend/action/action.php">
                    <input type="hidden" name="type" value="addJobPosition">
                    
                    <div class="form-group">
                        <label for="name">Job Title</label>
                        <input type="text" id="name" name="name" required placeholder="e.g. Senior Developer">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Short Description</label>
                        <textarea id="description" name="description" required placeholder="Brief job description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Job Image</label>
                        <div class="file-upload">
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            <label for="image" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose Image File</span>
                            </label>
                        </div>
                        <img id="imagePreview" class="image-preview" style="display: none;">
                    </div>
                    
                    <div class="form-group">
                        <label for="what_you_do">What You Do</label>
                        <textarea id="what_you_do" name="what_you_do" placeholder="Detailed job description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="roles">Roles & Responsibilities (one per line)</label>
                        <textarea id="roles" name="roles" placeholder="Edit video content\nManage social media\nCollaborate with team"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="who_you_are">Who You Are (one per line)</label>
                        <textarea id="who_you_are" name="who_you_are" placeholder="Experienced with tools\nPassionate about work\nTeam player"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="what_we_offer">What We Offer (one per line)</label>
                        <textarea id="what_we_offer" name="what_we_offer" placeholder="Competitive salary\nHealth benefits\nFlexible hours"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="extras">Additional Information</label>
                        <textarea id="extras" name="extras" placeholder="Contact information, special requirements, etc."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="add_position" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Position
                    </button>
                </form>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-info-circle"></i> Quick Guide</h2>
                <div style="color: #cccccc; line-height: 1.8;">
                    <p><strong style="color: #5658BE;">Adding Positions:</strong></p>
                    <p>• Fill all required fields</p>
                    <p>• Upload images in JPG, PNG, or GIF format</p>
                    <p>• Use one item per line for lists</p>
                    <p>• Set status to control visibility</p>
                    <br>
                    <p><strong style="color: #5658BE;">Image Upload:</strong></p>
                    <p>• Maximum file size: 5MB</p>
                    <p>• Supported formats: JPG, PNG, GIF</p>
                    <p>• Images are automatically optimized</p>
                    <br>
                    <p><strong style="color: #5658BE;">Status:</strong></p>
                    <p>• Active: Visible on website</p>
                    <p>• Inactive: Hidden from public</p>
                </div>
            </div>
        </div>
        
        <div class="card positions-list">
            <h2><i class="fas fa-briefcase"></i> Job Positions Management</h2>
            
            <?php if (empty($positions)): ?>
                <div style="text-align: center; padding: 4rem; color: #666;">
                    <i class="fas fa-briefcase" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; color: #5658BE;"></i>
                    <p style="font-size: 1.2rem;">No job positions found</p>
                    <p>Add your first position using the form above!</p>
                </div>
            <?php else: ?>
                <?php foreach ($positions as $position): ?>
                    <div class="position-item">
                        <div class="position-header">
                            <div>
                                <div class="position-title"><?= htmlspecialchars($position['name']) ?></div>
                                <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem;">
                                    <span class="status-badge <?= $position['status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
                                        <?= ucfirst($position['status']) ?>
                                    </span>
                                    <small style="color: #999;">Created: <?= date('M j, Y', strtotime($position['created_at'])) ?></small>
                                    <?php if ($position['image']): ?>
                                        <small style="color: #5658BE;"><i class="fas fa-image"></i> Has Image</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="position-actions">
                                <button onclick="toggleEdit(<?= $position['id'] ?>)" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this position?')" action="../backend/action/action.php">
                                    <input type="hidden" name="type" value="deleteJobPosition">
                                    <input type="hidden" name="position_id" value="<?= $position['id'] ?>">
                                    <button type="submit" name="delete_position" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="position-description">
                            <?= nl2br(htmlspecialchars($position['description'])) ?>
                        </div>
                        
                        <?php if ($position['image']): ?>
                            <div style="margin: 1rem 0;">
                                <img src="../<?= htmlspecialchars($position['image']) ?>" alt="Job Image" style="max-width: 200px; border-radius: 8px; border: 2px solid #2a2a2f;">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Edit Form -->
                        <div id="edit-form-<?= $position['id'] ?>" class="edit-form">
                            <h3 style="color: #5658BE; margin-bottom: 1rem;"><i class="fas fa-edit"></i> Edit Position</h3>
                            <form method="POST" enctype="multipart/form-data" action="../backend/action/action.php">
                                <input type="hidden" name="type" value="updateJobPosition">
                                <input type="hidden" name="position_id" value="<?= $position['id'] ?>">
                                
                                <div class="form-group">
                                    <label>Job Title</label>
                                    <input type="text" name="name" value="<?= htmlspecialchars($position['name']) ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea name="description" required><?= htmlspecialchars($position['description']) ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Job Image</label>
                                    <?php if ($position['image']): ?>
                                        <div style="margin-bottom: 1rem;">
                                            <p style="color: #5658BE; font-weight: 600; margin-bottom: 0.5rem;">Current Image:</p>
                                            <img src="../<?= htmlspecialchars($position['image']) ?>" alt="Current Job Image" style="max-width: 200px; border-radius: 8px; border: 2px solid #2a2a2f; margin-bottom: 1rem;">
                                            <p style="color: #999; font-size: 0.9rem;">Upload a new image to replace the current one</p>
                                        </div>
                                    <?php else: ?>
                                        <p style="color: #999; margin-bottom: 1rem;">No image currently uploaded</p>
                                    <?php endif; ?>
                                    
                                    <div class="file-upload">
                                        <input type="file" id="edit-image-<?= $position['id'] ?>" name="image" accept="image/*" onchange="previewEditImage(this, <?= $position['id'] ?>)">
                                        <label for="edit-image-<?= $position['id'] ?>" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Choose New Image (Optional)</span>
                                        </label>
                                    </div>
                                    <img id="editImagePreview-<?= $position['id'] ?>" class="image-preview" style="display: none;">
                                </div>
                                
                                <div class="form-group">
                                    <label>What You Do (one per line)</label>
                                    <textarea name="what_you_do" placeholder="Autem et quia ipsum\ndasdasdasdasd\ndasdasdasdasd"><?php 
                                        $whatYouDo = json_decode($position['what_you_do'], true);
                                        if (is_array($whatYouDo) && !empty($whatYouDo)) {
                                            echo htmlspecialchars(implode("\n", $whatYouDo));
                                        } else {
                                            // Handle legacy data that might not be JSON
                                            $cleanText = str_replace(['\\r\\n', '\\n', '\r\n'], "\n", $position['what_you_do']);
                                            echo htmlspecialchars($cleanText);
                                        }
                                    ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Roles & Responsibilities (one per line)</label>
                                    <textarea name="roles" placeholder="Edit video content\nManage social media\nCollaborate with team"><?php 
                                        $roles = json_decode($position['roles'], true);
                                        if (is_array($roles) && !empty($roles)) {
                                            echo htmlspecialchars(implode("\n", $roles));
                                        } else {
                                            $cleanText = str_replace(['\\r\\n', '\\n', '\r\n'], "\n", $position['roles']);
                                            echo htmlspecialchars($cleanText);
                                        }
                                    ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Who You Are (one per line)</label>
                                    <textarea name="who_you_are" placeholder="Experienced with tools\nPassionate about work\nTeam player"><?php 
                                        $whoYouAre = json_decode($position['who_you_are'], true);
                                        if (is_array($whoYouAre) && !empty($whoYouAre)) {
                                            echo htmlspecialchars(implode("\n", $whoYouAre));
                                        } else {
                                            $cleanText = str_replace(['\\r\\n', '\\n', '\r\n'], "\n", $position['who_you_are']);
                                            echo htmlspecialchars($cleanText);
                                        }
                                    ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>What We Offer (one per line)</label>
                                    <textarea name="what_we_offer" placeholder="Competitive salary\nHealth benefits\nFlexible hours"><?php 
                                        $whatWeOffer = json_decode($position['what_we_offer'], true);
                                        if (is_array($whatWeOffer) && !empty($whatWeOffer)) {
                                            echo htmlspecialchars(implode("\n", $whatWeOffer));
                                        } else {
                                            $cleanText = str_replace(['\\r\\n', '\\n', '\r\n'], "\n", $position['what_we_offer']);
                                            echo htmlspecialchars($cleanText);
                                        }
                                    ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Additional Information</label>
                                    <textarea name="extras" placeholder="Contact information, special requirements, etc."><?= htmlspecialchars($position['extras']) ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="active" <?= $position['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $position['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                
                                <div style="display: flex; gap: 1rem;">
                                    <button type="submit" name="update_position" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update Position
                                    </button>
                                    <button type="button" onclick="toggleEdit(<?= $position['id'] ?>)" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const label = input.nextElementSibling.querySelector('span');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    label.textContent = input.files[0].name;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function previewEditImage(input, id) {
            const preview = document.getElementById('editImagePreview-' + id);
            const label = input.nextElementSibling.querySelector('span');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    label.textContent = input.files[0].name;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function toggleEdit(id) {
            const editForm = document.getElementById('edit-form-' + id);
            editForm.classList.toggle('active');
        }
    </script>
</body>
</html>