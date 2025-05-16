/**
 * Authentication module for Agritech Bechar
 * Handles user login, logout, and session management
 * Connects to the database through API endpoints
 */

/**
 * Login user with username and password
 * @param {string} username - The user's username
 * @param {string} password - The user's password
 * @param {boolean} remember - Whether to remember the user
 * @returns {Promise<Object>} Promise resolving to result object with success status and user data or error message
 */
async function loginUser(username, password, remember = false) {
  console.log(`Attempting login with username: ${username}`);

  try {
    // Get the base URL for API calls
    const baseUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/'));
    const apiUrl = `${baseUrl}/api/auth.php`;

    console.log(`Sending login request to: ${apiUrl}`);

    // Send login request to the server
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Cache-Control': 'no-cache, no-store, must-revalidate'
      },
      body: JSON.stringify({
        action: 'login',
        username: username,
        password: password,
        remember: remember
      })
    });

    console.log(`Login response status: ${response.status}`);

    // Parse response
    const responseText = await response.text();
    console.log(`Login response text: ${responseText}`);

    let data;
    try {
      data = JSON.parse(responseText);
    } catch (e) {
      console.error('Error parsing JSON response:', e);
      return {
        success: false,
        message: 'Erreur de format de réponse du serveur. Veuillez réessayer.'
      };
    }

    // Check if login was successful
    if (data.success) {
      console.log('Login successful, storing user data...');

      // Store user info in localStorage (without password)
      const user = data.user;
      localStorage.setItem('currentUserId', user.id);
      localStorage.setItem('currentUsername', user.username);
      localStorage.setItem('currentUserEmail', user.email || '');
      localStorage.setItem('currentUserRole', user.role);
      localStorage.setItem('currentUserData', JSON.stringify(user));
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.setItem('lastLoginTime', new Date().toISOString());

      console.log(`User ${user.username} successfully logged in`);
      return { success: true, user: user };
    } else {
      // Login failed
      console.log(`Login failed: ${data.message}`);
      return {
        success: false,
        message: data.message || 'Erreur de connexion. Veuillez réessayer.'
      };
    }
  } catch (error) {
    console.error('Error during login:', error);
    return {
      success: false,
      message: 'Erreur de connexion au serveur. Veuillez vérifier votre connexion internet et réessayer.'
    };
  }
}

// Check if user is logged in
function isLoggedIn() {
  return localStorage.getItem('isLoggedIn') === 'true';
}

// Get current user ID
function getCurrentUserId() {
  return localStorage.getItem('currentUserId');
}

// Get current username
function getCurrentUsername() {
  return localStorage.getItem('currentUsername');
}

// Get current user role
function getCurrentUserRole() {
  return localStorage.getItem('currentUserRole');
}

/**
 * Get current user info
 * @returns {Object|null} User object or null if not logged in
 */
function getCurrentUser() {
  // First try to get from currentUserData which has complete info
  const userDataStr = localStorage.getItem('currentUserData');
  if (userDataStr) {
    try {
      return JSON.parse(userDataStr);
    } catch (e) {
      console.error('Error parsing user data:', e);
    }
  }

  // Fallback to finding user in the users array
  const userId = getCurrentUserId();
  if (!userId) return null;

  const users = JSON.parse(localStorage.getItem('agricultureUsers')) || [];
  return users.find(u => u.id == userId) || null;
}

/**
 * Logout current user
 * @returns {Promise<boolean>} Promise resolving to true if logout was successful
 */
async function logoutUser() {
  console.log('Logging out user...');

  try {
    // Get current user ID
    const currentUserId = localStorage.getItem('currentUserId');

    if (currentUserId) {
      // Get the base URL for API calls
      const baseUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/'));
      const apiUrl = `${baseUrl}/api/auth.php`;

      console.log(`Sending logout request to: ${apiUrl}`);

      // Send logout request to the server
      const response = await fetch(apiUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Cache-Control': 'no-cache, no-store, must-revalidate'
        },
        body: JSON.stringify({
          action: 'logout'
        })
      });

      console.log(`Logout response status: ${response.status}`);

      // Try to parse the response, but don't worry if it fails
      try {
        const data = await response.json();
        console.log('Logout response:', data);
      } catch (e) {
        console.warn('Could not parse logout response:', e);
      }
    }
  } catch (error) {
    console.error('Error during server logout:', error);
    // Continue with local logout even if server request fails
  } finally {
    // Always clear all user data from localStorage
    localStorage.removeItem('currentUserId');
    localStorage.removeItem('currentUsername');
    localStorage.removeItem('currentUserEmail');
    localStorage.removeItem('currentUserRole');
    localStorage.removeItem('currentUserData');
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('lastLoginTime');

    console.log('User logged out successfully (local session cleared)');
    return true;
  }
}

// Check authentication and redirect if not authenticated
function checkAuth(redirectUrl = 'login.html') {
  if (!isLoggedIn()) {
    window.location.href = redirectUrl;
    return false;
  }
  return true;
}

// Export functions
window.Auth = {
  loginUser,
  isLoggedIn,
  getCurrentUserId,
  getCurrentUsername,
  getCurrentUserRole,
  getCurrentUser,
  logoutUser,
  checkAuth
};
