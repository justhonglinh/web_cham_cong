// Location Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const locationModal = document.getElementById('locationModal');
    const existingLocationsModal = document.getElementById('existingLocationsModal');
    const openLocationModal = document.getElementById('openLocationModal');
    const closeLocationModal = document.getElementById('closeLocationModal');
    const closeExistingLocationsModal = document.getElementById('closeExistingLocationsModal');
    const cancelLocation = document.getElementById('cancelLocation');
    
    // Form elements
    const locationForm = document.getElementById('locationForm');
    const getCurrentLocation = document.getElementById('getCurrentLocation');
    
    // Loading and message elements
    const locationLoading = document.getElementById('locationLoading');
    const locationMessage = document.getElementById('locationMessage');
    
    // Existing locations elements
    const existingLocationsList = document.getElementById('existingLocationsList');
    const noLocationsMessage = document.getElementById('noLocationsMessage');

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for currentLocationData to be set, then update text
        setTimeout(() => {
            updateLocationModalText();
        }, 100);
    });

    // Function to update button text (can be called from dashboard)
    window.updateLocationButtonText = function() {
        updateLocationModalText();
    };

    // Show modal
    function showModal(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Update modal title and button text based on current location
        updateLocationModalText();
        
        // Show/hide action selection based on current location data
        updateActionSelection();
        
        // Fill form with current location data if available
        if (window.currentLocationData && window.currentLocationData.name) {
            fillFormWithCurrentLocation();
        }
        
        // Clear any previous messages
        locationMessage.classList.add('hidden');
    }

    // Update modal title and button text
    function updateLocationModalText() {
        const modalTitle = document.getElementById('locationModalTitle');
        const openLocationButton = document.getElementById('openLocationModal');
        
        // Check if there's current location data from server
        if (window.currentLocationData && window.currentLocationData.name) {
            modalTitle.textContent = 'Thay đổi vị trí chấm công';
            if (openLocationButton) {
                openLocationButton.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Thay đổi vị trí
                `;
            }
        } else {
            modalTitle.textContent = 'Thêm vị trí chấm công';
            if (openLocationButton) {
                openLocationButton.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Thêm vị trí
                `;
            }
        }
        
        // Update action selection visibility
        updateActionSelection();
    }

    // Update action selection visibility
    function updateActionSelection() {
        const actionSelection = document.getElementById('actionSelection');
        
        if (window.currentLocationData && window.currentLocationData.name) {
            // Show action selection and reload button when editing
            if (actionSelection) {
                actionSelection.classList.remove('hidden');
            }
        } else {
            // Hide action selection and reload button when adding new
            if (actionSelection) {
                actionSelection.classList.add('hidden');
            }
        }
    }

    // Fill form with current location data
    function fillFormWithCurrentLocation() {
        // Check if there's current location data from server
        if (window.currentLocationData) {
            // Fill the form fields with current data
            document.getElementById('location_name').value = window.currentLocationData.name || '';
            document.getElementById('location_address').value = window.currentLocationData.address || '';
            document.getElementById('location_latitude').value = window.currentLocationData.latitude ? Number(window.currentLocationData.latitude).toFixed(6) : '';
            document.getElementById('location_longitude').value = window.currentLocationData.longitude ? Number(window.currentLocationData.longitude).toFixed(6) : '';
            document.getElementById('location_radius').value = window.currentLocationData.radius || 100;
            document.getElementById('location_description').value = window.currentLocationData.description || '';
        }
    }

    // Hide modal
    function hideModal(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Show loading
    function showLoading() {
        locationLoading.classList.remove('hidden');
        locationForm.style.display = 'none';
    }

    // Hide loading
    function hideLoading() {
        locationLoading.classList.add('hidden');
        locationForm.style.display = 'block';
    }

    // Show message
    function showMessage(message, type = 'success') {
        locationMessage.className = `mt-4 p-3 rounded-lg ${type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
        locationMessage.textContent = message;
        locationMessage.classList.remove('hidden');
        
        setTimeout(() => {
            locationMessage.classList.add('hidden');
        }, 5000);
    }

    // Clear form
    function clearForm() {
        // Only clear form if there's no current location data
        if (!window.currentLocationData || !window.currentLocationData.name) {
            locationForm.reset();
        }
        locationMessage.classList.add('hidden');
    }

    // Event listeners for modal controls
    openLocationModal.addEventListener('click', () => showModal(locationModal));
    closeLocationModal.addEventListener('click', () => {
        hideModal(locationModal);
        clearForm();
    });
    closeExistingLocationsModal.addEventListener('click', () => hideModal(existingLocationsModal));
    cancelLocation.addEventListener('click', () => {
        hideModal(locationModal);
        clearForm();
    });

    // Close modal when clicking outside
    locationModal.addEventListener('click', (e) => {
        if (e.target === locationModal) {
            hideModal(locationModal);
            clearForm();
        }
    });

    existingLocationsModal.addEventListener('click', (e) => {
        if (e.target === existingLocationsModal) {
            hideModal(existingLocationsModal);
        }
    });

    // Get current location using HTML5 Geolocation API
    getCurrentLocation.addEventListener('click', function() {
        if (!navigator.geolocation) {
            showMessage('Trình duyệt của bạn không hỗ trợ định vị địa lý', 'error');
            return;
        }

        showLoading();
        
        // Clear form first to ensure fresh data
        locationForm.reset();
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                // Fill coordinates
                document.getElementById('location_latitude').value = latitude.toFixed(8);
                document.getElementById('location_longitude').value = longitude.toFixed(8);
                
                // Try to get address from coordinates using reverse geocoding
                getAddressFromCoordinates(latitude, longitude);
                
                hideLoading();
                showMessage('Đã lấy vị trí hiện tại thành công!', 'success');
            },
            function(error) {
                hideLoading();
                let errorMessage = 'Không thể lấy vị trí hiện tại';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Bạn đã từ chối quyền truy cập vị trí';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Thông tin vị trí không khả dụng';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Hết thời gian chờ lấy vị trí';
                        break;
                }
                
                showMessage(errorMessage, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            }
        );
    });

    // Get address from coordinates using Nominatim (OpenStreetMap)
    function getAddressFromCoordinates(latitude, longitude) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('location_address').value = data.display_name;
                    
                    // Try to extract a meaningful name
                    let name = '';
                    if (data.address) {
                        if (data.address.building) {
                            name = data.address.building;
                        } else if (data.address.amenity) {
                            name = data.address.amenity;
                        } else if (data.address.road) {
                            name = data.address.road;
                        } else if (data.address.suburb) {
                            name = data.address.suburb;
                        }
                    }
                    
                    if (name) {
                        document.getElementById('location_name').value = name;
                    }
                }
            })
            .catch(error => {
                console.log('Không thể lấy địa chỉ từ tọa độ:', error);
            });
    }

    // Select existing location
    window.selectLocation = function(locationId) {
        showLoading();
        
        fetch(`/locations/${locationId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const location = data.data;
                
                // Immediately save this location as the current location
                const locationData = {
                    name: location.name,
                    address: location.address,
                    latitude: location.latitude || '',
                    longitude: location.longitude || '',
                    radius: location.radius || 100,
                    description: location.description || ''
                };
                
                return fetch('/locations/update', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(locationData)
                });
            } else {
                throw new Error(data.message || 'Không thể tải thông tin vị trí');
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            hideLoading();
            
            if (result.success) {
                // Update currentLocationData with the saved location
                if (result.data) {
                    window.currentLocationData = {
                        name: result.data.name,
                        address: result.data.address,
                        latitude: result.data.latitude,
                        longitude: result.data.longitude,
                        radius: result.data.radius,
                        description: result.data.description || ''
                    };
                }
                
                // Update the UI to reflect the new current location
                updateLocationModalText();
                
                hideModal(existingLocationsModal);
                showMessage('Đã thay đổi vị trí chấm công hiện tại', 'success');
                
                // Reload page to refresh all data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showMessage(result.message || 'Có lỗi xảy ra khi thay đổi vị trí', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Lỗi khi xử lý vị trí:', error);
            
            // Chỉ hiển thị thông báo chung, không hiển thị chi tiết lỗi
            showMessage('Có lỗi xảy ra khi thay đổi vị trí', 'error');
        });
    };

    // Handle form submission
    locationForm.addEventListener('submit', function(e) {
        // Không prevent default nữa, để form submit bình thường
        // e.preventDefault();
        
        // Validate required fields
        const name = document.getElementById('location_name').value;
        const address = document.getElementById('location_address').value;
        
        if (!name || !address) {
            e.preventDefault(); // Prevent submit nếu validation fail
            showMessage('Vui lòng điền đầy đủ tên và địa chỉ vị trí', 'error');
            return false;
        }
        
        // Form sẽ submit bình thường và redirect về trang trước với flash message
    });
}); 