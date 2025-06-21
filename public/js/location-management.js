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
    const loadExistingLocations = document.getElementById('loadExistingLocations');
    
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
            document.getElementById('location_latitude').value = window.currentLocationData.latitude || '';
            document.getElementById('location_longitude').value = window.currentLocationData.longitude || '';
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
                timeout: 10000,
                maximumAge: 60000
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

    // Load existing locations
    loadExistingLocations.addEventListener('click', function() {
        showModal(existingLocationsModal);
        loadExistingLocationsData();
    });

    function loadExistingLocationsData() {
        fetch('/locations', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                displayExistingLocations(data.data);
                noLocationsMessage.classList.add('hidden');
                existingLocationsList.classList.remove('hidden');
            } else {
                noLocationsMessage.classList.remove('hidden');
                existingLocationsList.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải danh sách vị trí:', error);
            noLocationsMessage.classList.remove('hidden');
            existingLocationsList.classList.add('hidden');
        });
    }

    function displayExistingLocations(locations) {
        existingLocationsList.innerHTML = '';
        
        // Find the current location index to ensure only one is marked
        let currentLocationIndex = -1;
        if (window.currentLocationData && window.currentLocationData.name) {
            currentLocationIndex = locations.findIndex(location => 
                location.name === window.currentLocationData.name &&
                location.address === window.currentLocationData.address &&
                parseFloat(location.latitude) === parseFloat(window.currentLocationData.latitude) &&
                parseFloat(location.longitude) === parseFloat(window.currentLocationData.longitude)
            );
        }
        
        locations.forEach((location, index) => {
            // Only mark as current location if it's the first match found
            const isCurrentLocation = index === currentLocationIndex;
            
            const locationItem = document.createElement('div');
            locationItem.className = `p-3 border rounded-lg hover:bg-gray-50 cursor-pointer ${
                isCurrentLocation ? 'border-blue-500 bg-blue-50' : 'border-gray-200'
            }`;
            locationItem.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-medium text-gray-900">${location.name}</h4>
                            ${isCurrentLocation ? '<span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Đang sử dụng</span>' : ''}
                        </div>
                        <p class="text-sm text-gray-600">${location.address}</p>
                        <div class="flex items-center mt-1 text-xs text-gray-500">
                            <span>Bán kính: ${location.radius}m</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full ${location.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${location.is_active ? 'Hoạt động' : 'Không hoạt động'}
                        </span>
                        <button onclick="selectLocation(${location.id})" class="text-blue-600 hover:text-blue-800 text-sm">
                            ${isCurrentLocation ? 'Chọn lại' : 'Chọn'}
                        </button>
                    </div>
                </div>
            `;
            existingLocationsList.appendChild(locationItem);
        });
    }

    // Select existing location
    window.selectLocation = function(locationId) {
        fetch(`/locations/${locationId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
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
                    description: location.description || '',
                    action: 'update' // Always update current location
                };
                
                showLoading();
                
                fetch('/locations/update', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(locationData)
                })
                .then(response => response.json())
                .then(result => {
                    hideLoading();
                    
                    if (result.success) {
                        // Update currentLocationData with the saved location
                        window.currentLocationData = {
                            name: location.name,
                            address: location.address,
                            latitude: location.latitude,
                            longitude: location.longitude,
                            radius: location.radius,
                            description: location.description || ''
                        };
                        
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
                    console.error('Lỗi khi lưu vị trí:', error);
                    showMessage('Có lỗi xảy ra khi thay đổi vị trí', 'error');
                });
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải thông tin vị trí:', error);
            showMessage('Không thể tải thông tin vị trí', 'error');
        });
    };

    // Handle form submission
    locationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(locationForm);
        const data = Object.fromEntries(formData);
        
        // Get action from radio button if available, otherwise use automatic detection
        let action = 'update'; // Default to update
        
        const actionType = formData.get('action_type');
        if (actionType) {
            action = actionType;
        } else {
            // Fallback to automatic detection
            const hasUsedCurrentLocation = document.getElementById('location_latitude').value && 
                                         document.getElementById('location_latitude').value !== (window.currentLocationData?.latitude || '');
            
            if (hasUsedCurrentLocation || !window.currentLocationData || !window.currentLocationData.name) {
                action = 'create';
            }
        }
        
        // Add action to data
        data.action = action;
        
        showLoading();
        
        // Always use PUT method for updateCurrent endpoint
        const method = 'PUT';
        const url = '/locations/update';
        
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Update currentLocationData with the saved location
                if (data.data) {
                    window.currentLocationData = {
                        name: data.data.name,
                        address: data.data.address,
                        latitude: data.data.latitude,
                        longitude: data.data.longitude,
                        radius: data.data.radius,
                        description: data.data.description || ''
                    };
                    
                    // Update button text immediately
                    updateLocationModalText();
                }
                
                setTimeout(() => {
                    hideModal(locationModal);
                    // Reload page to refresh data after showing success message
                    window.location.reload();
                }, 2000);
            } else {
                showMessage(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Lỗi khi lưu vị trí:', error);
            showMessage('Có lỗi xảy ra khi lưu vị trí', 'error');
        });
    });

    // Show/hide action buttons based on mode
    if (isEditing) {
        document.getElementById('loadExistingLocations').style.display = 'none';
    } else {
        document.getElementById('loadExistingLocations').style.display = 'flex';
    }
}); 