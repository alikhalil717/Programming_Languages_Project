@extends('layouts.admin')
@section('title','Settings')
@section('content')
<h1 class="text-3xl font-bold mb-6">System Settings</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- General Settings -->
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-bold mb-4">General Settings</h2>

            <form id="general-settings" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                    <input type="text"
                           id="site_name"
                           class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                           placeholder="Site name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                    <textarea id="site_description"
                              rows="3"
                              class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                              placeholder="Site description"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email"
                               id="site_email"
                               class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                               placeholder="Site email">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text"
                               id="site_phone"
                               class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                               placeholder="Site phone">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text"
                           id="site_address"
                           class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                           placeholder="Site address">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- System Settings -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mt-6">
            <h2 class="text-xl font-bold mb-4">System Settings</h2>

            <form id="system-settings" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                        <select id="default_language"
                                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="ar">Arabic</option>
                            <option value="en">English</option>
                            <option value="fr">French</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                        <select id="default_currency"
                                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="SAR">Saudi Riyal (SAR)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select id="timezone"
                                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="Asia/Riyadh">Riyadh (UTC+3)</option>
                            <option value="UTC">Universal Time (UTC)</option>
                            <option value="America/New_York">New York (UTC-5)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select id="date_format"
                                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="dd/mm/yyyy">Day/Month/Year</option>
                            <option value="mm/dd/yyyy">Month/Day/Year</option>
                            <option value="yyyy-mm-dd">Year-Month-Day</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maintenance Mode</label>
                        <p class="text-sm text-gray-500">Temporarily disable site for visitors</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="maintenance_mode" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Save System Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Settings & Info -->
    <div class="space-y-6">
        <!-- Admin Info -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-bold mb-4">Admin Information</h2>
            <div id="admin-info">
                <!-- Will be filled with data -->
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-bold mb-4">System Information</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">System Version</span>
                    <span class="font-medium">1.0.0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Last Update</span>
                    <span class="font-medium">2024-01-15</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Server Uptime</span>
                    <span class="font-medium" id="uptime">0 days</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <button onclick="clearCache()"
                        class="w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-broom mr-2"></i> Clear Cache
                </button>
                <button onclick="backupDatabase()"
                        class="w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-database mr-2"></i> Backup Database
                </button>
                <button onclick="viewLogs()"
                        class="w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-file-alt mr-2"></i> View System Logs
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSettings();
    loadAdminInfo();
    startUptimeCounter();

    document.getElementById('general-settings').addEventListener('submit', saveGeneralSettings);
    document.getElementById('system-settings').addEventListener('submit', saveSystemSettings);
});

async function loadSettings() {
    try {
        const response = await fetchData('settings');

        if (response && response.settings) {
            const settings = response.settings;

            document.getElementById('site_name').value = settings.site_name || '';
            document.getElementById('site_description').value = settings.site_description || '';
            document.getElementById('site_email').value = settings.site_email || '';
            document.getElementById('site_phone').value = settings.site_phone || '';
            document.getElementById('site_address').value = settings.site_address || '';
            document.getElementById('default_language').value = settings.default_language || 'ar';
            document.getElementById('default_currency').value = settings.default_currency || 'SAR';
            document.getElementById('timezone').value = settings.timezone || 'Asia/Riyadh';
            document.getElementById('date_format').value = settings.date_format || 'dd/mm/yyyy';
            document.getElementById('maintenance_mode').checked = settings.maintenance_mode || false;
        }
    } catch (error) {
        console.error('Error loading settings:', error);
    }
}

async function loadAdminInfo() {
    try {
        const userData = localStorage.getItem('admin_user');
        if (userData) {
            const user = JSON.parse(userData);

            const adminInfoHtml = `
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                        <i class="fas fa-user text-indigo-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg">${user.name}</p>
                        <p class="text-gray-500">${user.role === 'admin' ? 'System Admin' : 'User'}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                        <span>${user.email || 'Not specified'}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                        <span>${user.phone_number}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                        <span>Joined on ${formatDate(user.created_at)}</span>
                    </div>
                </div>
            `;

            document.getElementById('admin-info').innerHTML = adminInfoHtml;
        }
    } catch (error) {
        console.error('Error loading admin info:', error);
    }
}

async function saveGeneralSettings(e) {
    e.preventDefault();

    try {
        const settings = {
            site_name: document.getElementById('site_name').value,
            site_description: document.getElementById('site_description').value,
            site_email: document.getElementById('site_email').value,
            site_phone: document.getElementById('site_phone').value,
            site_address: document.getElementById('site_address').value
        };

        const response = await fetchData('settings/general', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(settings)
        });

        if (response && response.success) {
            showSuccess('General settings saved successfully');
        } else {
            throw new Error(response?.message || 'Failed to save settings');
        }
    } catch (error) {
        showError(error.message);
    }
}

async function saveSystemSettings(e) {
    e.preventDefault();

    try {
        const settings = {
            default_language: document.getElementById('default_language').value,
            default_currency: document.getElementById('default_currency').value,
            timezone: document.getElementById('timezone').value,
            date_format: document.getElementById('date_format').value,
            maintenance_mode: document.getElementById('maintenance_mode').checked
        };

        const response = await fetchData('settings/system', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(settings)
        });

        if (response && response.success) {
            showSuccess('System settings saved successfully');
        } else {
            throw new Error(response?.message || 'Failed to save settings');
        }
    } catch (error) {
        showError(error.message);
    }
}

function startUptimeCounter() {
    const startTime = Date.now();
    const uptimeElement = document.getElementById('uptime');

    function updateUptime() {
        const uptime = Date.now() - startTime;
        const days = Math.floor(uptime / (1000 * 60 * 60 * 24));
        const hours = Math.floor((uptime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((uptime % (1000 * 60 * 60)) / (1000 * 60));

        if (days > 0) {
            uptimeElement.textContent = `${days} days, ${hours} hours`;
        } else if (hours > 0) {
            uptimeElement.textContent = `${hours} hours, ${minutes} minutes`;
        } else {
            uptimeElement.textContent = `${minutes} minutes`;
        }
    }

    updateUptime();
    setInterval(updateUptime, 60000);  
}

async function clearCache() {
    if (!confirm('Do you want to clear cache?')) return;

    try {
        showLoading();

        const response = await fetchData('system/clear-cache', {
            method: 'POST'
        });

        if (response && response.success) {
            showSuccess('Cache cleared successfully');
        } else {
            throw new Error(response?.message || 'Failed to clear cache');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function backupDatabase() {
    if (!confirm('Do you want to create a database backup?')) return;

    try {
        showLoading();

        const response = await fetchData('system/backup', {
            method: 'POST'
        });

        if (response && response.success) {
            showSuccess('Backup created successfully');

            if (response.download_url) {
                window.open(response.download_url, '_blank');
            }
        } else {
            throw new Error(response?.message || 'Failed to create backup');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function viewLogs() {
    alert('This feature is under development');

}

function formatDate(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US');
}

function showLoading() {
    const button = event?.target;
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        button.disabled = true;

        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 3000);
    }
}

function hideLoading() {
    const buttons = document.querySelectorAll('button[disabled]');
    buttons.forEach(button => {
        button.disabled = false;
        button.innerHTML = button.innerHTML.replace('Processing...', '').replace('<i class="fas fa-spinner fa-spin mr-2"></i>', '');
    });
}
</script>
@endsection