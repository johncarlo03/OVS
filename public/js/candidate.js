window.onload = function () {
    
document.querySelectorAll('.show-confirm').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This candidate will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('formSubmitBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');

    // Store original form action and method
    const originalAction = form.action;
    const originalMethod = form.method;

    // Handle Edit
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            // Fill fields
            form.querySelector('[name="first_name"]').value = this.dataset.first_name;
            form.querySelector('[name="middle_name"]').value = this.dataset.middle_name;
            form.querySelector('[name="last_name"]').value = this.dataset.last_name;
            form.querySelector('[name="position"]').value = this.dataset.position;
            form.querySelector('[name="department"]').value = this.dataset.department;
            form.querySelector('[name="session"]').value = this.dataset.session;

            // Change form action/method
            form.action = `/candidates/update/${this.dataset.id}`;
            form.method = 'POST';
            submitBtn.textContent = 'Update';
            cancelBtn.style.display = 'inline-block';

            // Add _method for PUT
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
        });
    });

    // Handle Cancel
    cancelBtn.addEventListener('click', function () {
        // Clear form fields
        form.reset();

        // Reset form action/method
        form.action = originalAction;
        form.method = originalMethod;
        submitBtn.textContent = 'Register';
        cancelBtn.style.display = 'none';

        // Remove _method field if exists
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
    });

    form.addEventListener('submit', function (e) {
        // Check if form is in update mode
        if (submitBtn.textContent === 'Update') {
            e.preventDefault(); // Prevent immediate submission

            Swal.fire({
                title: 'Confirm Update',
                text: 'Are you sure you want to update this candidate?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#1e40af',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Proceed with the update
                }
            });
        }
    });

};
