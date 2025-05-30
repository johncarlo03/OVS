document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('.toggle-btn');
  
    toggleButtons.forEach(button => {
      button.addEventListener('click', () => {
        // Remove 'active' class from all buttons
        toggleButtons.forEach(btn => btn.classList.remove('active'));
  
        // Add 'active' to the clicked button
        button.classList.add('active');
  
        // You can retrieve the selected mode like this:
        const selectedMode = button.dataset.mode;
        console.log("Selected Mode:", selectedMode);
  
        // Optional: Logic for switching behavior based on mode
        if (selectedMode === 'manual') {
          // Show manual login form elements
        } else if (selectedMode === 'rfid') {
          // Handle RFID-based login
        }
      });
    });
  });
  
  function login() {
    const schoolId = document.getElementById('schoolId').value;
    if (schoolId.trim() === '') {
      alert('Please enter your School ID');
      return;
    }
  
    alert(`Logging in with School ID: ${schoolId}`);
  }
  