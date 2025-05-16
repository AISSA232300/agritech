/**
 * Role-based permissions for AgriTech Béchar
 * This file handles UI permissions based on user roles
 */

// Initialize role-based permissions
function initRolePermissions() {
  // Get current user role
  const userRole = getCurrentUserRole();
  console.log('Current user role:', userRole);

  // Apply permissions based on role
  if (userRole === 'gestionnaire' || userRole === 'admin') {
    // Gestionnaire has full access
    enableGestionnaireFeatures();
  } else {
    // Employe has restricted access
    restrictToEmployeFeatures();
  }
}

// Get current user role from Auth module
function getCurrentUserRole() {
  if (window.Auth && typeof window.Auth.getCurrentUserRole === 'function') {
    return window.Auth.getCurrentUserRole();
  }

  // Fallback to localStorage if Auth module is not available
  return localStorage.getItem('currentUserRole') || 'employe';
}

// Enable all features for gestionnaire role
function enableGestionnaireFeatures() {
  console.log('Enabling gestionnaire features');

  // Show all management buttons
  document.querySelectorAll('.management-only').forEach(el => {
    el.style.display = 'block';
  });

  // Enable all management actions
  document.querySelectorAll('.management-action').forEach(el => {
    el.disabled = false;
    el.classList.remove('disabled');
  });

  // Show user management section
  const userManagementSection = document.getElementById('userManagementSection');
  if (userManagementSection) {
    userManagementSection.style.display = 'block';
  }

  // Enable pivot management
  enablePivotManagement(true);

  // Enable task management
  enableTaskManagement(true);
}

// Restrict features for employe role
function restrictToEmployeFeatures() {
  console.log('Restricting to employe features');

  // Hide management buttons
  document.querySelectorAll('.management-only').forEach(el => {
    el.style.display = 'none';
  });

  // Disable management actions
  document.querySelectorAll('.management-action').forEach(el => {
    el.disabled = true;
    el.classList.add('disabled');
  });

  // Hide user management section
  const userManagementSection = document.getElementById('userManagementSection');
  if (userManagementSection) {
    userManagementSection.style.display = 'none';
  }

  // Disable pivot management
  enablePivotManagement(false);

  // Disable task management
  enableTaskManagement(false);
}

// Enable or disable pivot management features
function enablePivotManagement(enable) {
  // Add/Edit/Delete pivot buttons
  const pivotManagementButtons = document.querySelectorAll('.pivot-management');
  pivotManagementButtons.forEach(btn => {
    if (enable) {
      btn.style.display = 'inline-block';
      btn.disabled = false;
      btn.classList.remove('disabled');
    } else {
      btn.style.display = 'none';
      btn.disabled = true;
      btn.classList.add('disabled');
    }
  });

  // Pivot forms
  const pivotForms = document.querySelectorAll('.pivot-form');
  pivotForms.forEach(form => {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      input.disabled = !enable;
      if (!enable) {
        input.setAttribute('readonly', 'readonly');
      } else {
        input.removeAttribute('readonly');
      }
    });
  });
}

// Enable or disable task management features
function enableTaskManagement(enable) {
  // Add/Edit/Delete task buttons
  const taskManagementButtons = document.querySelectorAll('.task-management');
  taskManagementButtons.forEach(btn => {
    if (enable) {
      btn.style.display = 'inline-block';
      btn.disabled = false;
      btn.classList.remove('disabled');
    } else {
      btn.style.display = 'none';
      btn.disabled = true;
      btn.classList.add('disabled');
    }
  });

  // Task forms
  const taskForms = document.querySelectorAll('.task-form');
  taskForms.forEach(form => {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      input.disabled = !enable;
      if (!enable) {
        input.setAttribute('readonly', 'readonly');
      } else {
        input.removeAttribute('readonly');
      }
    });
  });

  // Always enable task completion buttons for all roles
  document.querySelectorAll('.task-complete-btn').forEach(btn => {
    btn.style.display = 'inline-block';
    btn.disabled = false;
    btn.classList.remove('disabled');
  });
}

// Complete a task
async function completeTask(taskId) {
  try {
    // Get the base URL for API calls
    const baseUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/'));
    const apiUrl = `${baseUrl}/api/tasks.php`;

    console.log(`Sending task completion request to: ${apiUrl}`);

    // Send task completion request to the server
    const response = await fetch(apiUrl, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Cache-Control': 'no-cache, no-store, must-revalidate'
      },
      body: JSON.stringify({
        id: taskId,
        action: 'complete'
      })
    });

    const data = await response.json();

    if (data.success) {
      console.log('Task completed successfully:', data.task);

      // Update UI to reflect completed task
      const taskElement = document.getElementById(`task-${taskId}`);
      if (taskElement) {
        taskElement.classList.add('completed');

        // Update status badge
        const statusBadge = taskElement.querySelector('.status-badge');
        if (statusBadge) {
          statusBadge.textContent = 'Terminée';
          statusBadge.className = 'status-badge badge bg-success';
        }

        // Hide complete button
        const completeBtn = taskElement.querySelector('.task-complete-btn');
        if (completeBtn) {
          completeBtn.style.display = 'none';
        }

        // Show completed by info
        const completedByElement = taskElement.querySelector('.completed-by');
        if (completedByElement) {
          const currentUsername = window.Auth.getCurrentUsername();
          completedByElement.textContent = `Terminée par: ${currentUsername}`;
          completedByElement.style.display = 'block';
        }
      }

      return true;
    } else {
      console.error('Failed to complete task:', data.message);
      return false;
    }
  } catch (error) {
    console.error('Error completing task:', error);
    return false;
  }
}

// Create a task card with role-based permissions
function createTaskCard(task) {
  const taskDate = new Date(task.date);
  const formattedDate = taskDate.toLocaleDateString('fr-FR');
  const isCompleted = task.status === 'completed';
  const userRole = getCurrentUserRole();

  return `
    <div class="task-card ${isCompleted ? 'completed' : ''}" id="task-${task.id}">
      <div class="task-header">
        <span class="task-date">${formattedDate}</span>
        <span class="task-priority ${task.priority}">${getPriorityLabel(task.priority)}</span>
        <span class="status-badge badge ${isCompleted ? 'bg-success' : 'bg-warning'}">${isCompleted ? 'Terminée' : 'En attente'}</span>
      </div>
      <div class="task-title">${task.title}</div>
      <div class="task-pivot">
        <i class="fas fa-tint"></i> ${task.pivot}
      </div>
      ${isCompleted ? `<div class="completed-by">Terminée par: ${task.completed_by || 'Utilisateur'}</div>` : ''}
      <div class="task-actions">
        <button class="btn-sm btn-outline-primary" onclick="viewTaskDetails('${task.id}')">
          <i class="fas fa-eye"></i>
        </button>
        ${!isCompleted ? `
        <button class="btn-sm btn-outline-success task-complete-btn" onclick="window.RolePermissions.completeTask('${task.id}')">
          <i class="fas fa-check"></i>
        </button>
        ` : ''}
        ${(userRole === 'gestionnaire' || userRole === 'admin') && !isCompleted ? `
        <button class="btn-sm btn-outline-primary management-action task-management" onclick="editTask('${task.id}')">
          <i class="fas fa-edit"></i>
        </button>
        <button class="btn-sm btn-outline-danger management-action task-management" onclick="deleteTask('${task.id}')">
          <i class="fas fa-trash"></i>
        </button>
        ` : ''}
      </div>
    </div>
  `;
}

// Helper function for task priority labels
function getPriorityLabel(priority) {
  const labels = {
    'low': 'Basse',
    'medium': 'Moyenne',
    'high': 'Haute'
  };
  return labels[priority] || priority;
}

// Export functions
window.RolePermissions = {
  initRolePermissions,
  getCurrentUserRole,
  completeTask,
  createTaskCard,
  getPriorityLabel
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize role-based permissions
  initRolePermissions();
});
