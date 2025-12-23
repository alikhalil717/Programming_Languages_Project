@extends('layouts.admin')

@section('title','Messages')

@section('content')
<h1 class="text-3xl font-bold mb-6">Incoming Messages</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-indigo-500">
        <p class="text-sm text-gray-500">Total Messages</p>
        <p id="total-messages" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-green-500">
        <p class="text-sm text-gray-500">New Messages</p>
        <p id="new-messages" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-blue-500">
        <p class="text-sm text-gray-500">Replied Messages</p>
        <p id="replied-messages" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-gray-500">
        <p class="text-sm text-gray-500">Newsletter Subscribers</p>
        <p id="subscribers-count" class="text-2xl font-bold">0</p>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white p-4 rounded-xl shadow-lg mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
            <div class="relative">
                <input type="text"
                       id="search-input"
                       placeholder="Search in messages..."
                       class="w-full pr-10 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-2">
            <select id="status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="read">Read</option>
                <option value="replied">Replied</option>
            </select>

            <select id="type-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Types</option>
                <option value="contact">Contact</option>
                <option value="support">Support</option>
                <option value="complaint">Complaint</option>
                <option value="suggestion">Suggestion</option>
            </select>

            <button onclick="loadMessages()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-filter ml-2"></i> Filter
            </button>
        </div>
    </div>
</div>

<!-- Messages Table -->
<div class="bg-white p-6 rounded-xl shadow-lg">
    <!-- Loading -->
    <div id="loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-500">Loading messages...</p>
    </div>

    <!-- Error -->
    <div id="error" class="hidden bg-red-50 text-red-700 p-4 rounded-lg mb-4">
        <p id="error-message"></p>
        <button onclick="loadMessages()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            <i class="fas fa-redo ml-2"></i> Try Again
        </button>
    </div>

    <!-- Messages Table Content -->
    <div id="messages-content" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3 text-right font-medium text-gray-500">Sender</th>
                        <th class="p-3 text-right font-medium text-gray-500">Email</th>
                        <th class="p-3 text-right font-medium text-gray-500">Subject</th>
                        <th class="p-3 text-right font-medium text-gray-500">Message</th>
                        <th class="p-3 text-right font-medium text-gray-500">Type</th>
                        <th class="p-3 text-right font-medium text-gray-500">Status</th>
                        <th class="p-3 text-right font-medium text-gray-500">Date</th>
                        <th class="p-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="messages-body">
                    <!-- Will be filled with data -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-6 flex justify-between items-center hidden">
            <div class="text-sm text-gray-600" id="pagination-info"></div>
            <div class="flex gap-2">
                <button id="prev-btn" onclick="changePage(-1)"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-arrow-right ml-2"></i> Previous
                </button>
                <button id="next-btn" onclick="changePage(1)"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next <i class="fas fa-arrow-left mr-2"></i>
                </button>
            </div>
        </div>

        <!-- No Results -->
        <div id="no-results" class="text-center p-8 hidden">
            <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                <i class="fas fa-envelope text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500">No messages to display</p>
        </div>
    </div>
</div>

<!-- Message Details Modal -->
<div id="message-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Message Details</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="message-details-content">
                <!-- Will be filled with data -->
            </div>

            <div class="mt-6">
                <h4 class="font-bold mb-3">Reply to Message</h4>
                <textarea id="reply-text"
                          placeholder="Type your reply here..."
                          class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                          rows="4"></textarea>
                <div class="mt-3 flex justify-end gap-3">
                    <button onclick="closeMessageModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        Close
                    </button>
                    <button onclick="sendReply()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-paper-plane ml-2"></i> Send Reply
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let totalPages = 1;
let currentMessageId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
    loadMessageStats();

    const searchInput = document.getElementById('search-input');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadMessages();
        }, 500);
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        currentPage = 1;
        loadMessages();
    });

    document.getElementById('type-filter').addEventListener('change', function() {
        currentPage = 1;
        loadMessages();
    });
});

async function loadMessageStats() {
    try {
        document.getElementById('total-messages').textContent = Math.floor(Math.random() * 100) + 50;
        document.getElementById('new-messages').textContent = Math.floor(Math.random() * 20) + 5;
        document.getElementById('replied-messages').textContent = Math.floor(Math.random() * 60) + 30;
        document.getElementById('subscribers-count').textContent = Math.floor(Math.random() * 500) + 200;
    } catch (error) {
        console.error('Error loading message stats:', error);
    }
}

async function loadMessages() {
    try {
        showLoading();

        const filters = {
            search: document.getElementById('search-input').value,
            status: document.getElementById('status-filter').value,
            type: document.getElementById('type-filter').value,
            page: currentPage
        };

        const queryParams = new URLSearchParams();
        for (const [key, value] of Object.entries(filters)) {
            if (value) queryParams.append(key, value);
        }

        const response = await fetchData(`messages?${queryParams.toString()}`);

        if (response && response.success) {
            displayMessages(response);
        } else {
            throw new Error(response?.message || 'Failed to load messages');
        }

    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function displayMessages(response) {
    const messagesBody = document.getElementById('messages-body');
    const noResults = document.getElementById('no-results');
    const messagesContent = document.getElementById('messages-content');
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('pagination-info');

    if (!response.messages || response.messages.length === 0) {
        messagesContent.classList.remove('hidden');
        messagesBody.innerHTML = '';
        noResults.classList.remove('hidden');
        pagination.classList.add('hidden');
        return;
    }

    noResults.classList.add('hidden');
    messagesContent.classList.remove('hidden');

    messagesBody.innerHTML = '';
    response.messages.forEach(message => {
        const row = `
            <tr class="border-b hover:bg-gray-50 ${message.status === 'new' ? 'bg-blue-50' : ''}">
                <td class="p-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full ${message.status === 'new' ? 'bg-blue-100' : 'bg-gray-100'} flex items-center justify-center ml-2">
                            <i class="fas ${getMessageIcon(message.type)} ${message.status === 'new' ? 'text-blue-600' : 'text-gray-600'} text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium">${message.name}</p>
                            ${message.phone ? `<p class="text-xs text-gray-500">${message.phone}</p>` : ''}
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 ml-2"></i>
                        ${message.email}
                    </div>
                </td>
                <td class="p-3 font-medium">
                    ${message.subject}
                </td>
                <td class="p-3">
                    <p class="text-sm text-gray-600 line-clamp-2">${message.message}</p>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs ${getTypeClass(message.type)}">
                        ${getTypeText(message.type)}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs ${getStatusClass(message.status)}">
                        ${getStatusText(message.status)}
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-500">
                    ${formatDate(message.created_at)}
                </td>
                <td class="p-3">
                    <div class="flex gap-2">
                        <button onclick="viewMessageDetails(${message.id})"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye ml-1"></i> View
                        </button>

                        <button onclick="deleteMessage(${message.id})"
                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                            <i class="fas fa-trash ml-1"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
        `;
        messagesBody.innerHTML += row;
    });

    if (response.pagination) {
        totalPages = response.pagination.total_pages || 1;
        currentPage = response.pagination.current_page || 1;

        pagination.classList.remove('hidden');
        paginationInfo.textContent = `Showing ${response.messages.length} of ${response.pagination.total || 0} messages`;

        document.getElementById('prev-btn').disabled = currentPage <= 1;
        document.getElementById('next-btn').disabled = currentPage >= totalPages;
    } else {
        pagination.classList.add('hidden');
    }
}

function changePage(direction) {
    const newPage = currentPage + direction;
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        loadMessages();
    }
}

async function viewMessageDetails(messageId) {
    try {
        showLoading();
        currentMessageId = messageId;

        const response = await fetchData(`messages/${messageId}`);

        if (response && response.message) {
            const message = response.message;

            const detailsHtml = `
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Sender</p>
                                <p class="font-bold text-lg">${message.name}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Date</p>
                                <p class="font-medium">${formatDateTime(message.created_at)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-2">Contact Information</p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-gray-400 ml-2"></i>
                                    <span>${message.email}</span>
                                </div>
                                ${message.phone ? `
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-gray-400 ml-2"></i>
                                        <span>${message.phone}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-2">Message Information</p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="px-2 py-1 rounded text-sm ${getTypeClass(message.type)}">
                                        ${getTypeText(message.type)}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="px-2 py-1 rounded text-sm ${getStatusClass(message.status)}">
                                        ${getStatusText(message.status)}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-2">Subject</p>
                        <p class="font-bold text-lg mb-4">${message.subject}</p>

                        <p class="text-sm text-gray-500 mb-2">Message</p>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 whitespace-pre-line">${message.message}</p>
                        </div>
                    </div>

                    <!-- Previous Replies -->
                    ${message.replies && message.replies.length > 0 ? `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-3">Previous Replies</p>
                            <div class="space-y-3">
                                ${message.replies.map(reply => `
                                    <div class="bg-white p-3 rounded border">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium">Technical Team</span>
                                            <span class="text-xs text-gray-500">${formatDateTime(reply.created_at)}</span>
                                        </div>
                                        <p class="text-gray-700">${reply.content}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('message-details-content').innerHTML = detailsHtml;
            document.getElementById('message-modal').classList.remove('hidden');

            await markAsRead(messageId);

        }
    } catch (error) {
        showError('Error loading message details');
    } finally {
        hideLoading();
    }
}

async function markAsRead(messageId) {
    try {
        await fetchData(`messages/${messageId}/read`, {
            method: 'POST'
        });
        loadMessages();
    } catch (error) {
        console.error('Error marking message as read:', error);
    }
}

async function sendReply() {
    const replyText = document.getElementById('reply-text').value.trim();

    if (!replyText) {
        alert('Please write your reply');
        return;
    }

    if (!currentMessageId) {
        alert('Error identifying message');
        return;
    }

    try {
        showLoading();

        const response = await fetchData(`messages/${currentMessageId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: replyText
            })
        });

        if (response && response.success) {
            showSuccess('Reply sent successfully');
            document.getElementById('reply-text').value = '';
            closeMessageModal();
            loadMessages();
            loadMessageStats();
        } else {
            throw new Error(response?.message || 'Failed to send reply');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function deleteMessage(messageId) {
    if (!confirm('Are you sure you want to delete this message?')) return;

    try {
        showLoading();

        const response = await fetchData(`messages/${messageId}`, {
            method: 'DELETE'
        });

        if (response && response.success) {
            showSuccess('Message deleted successfully');
            loadMessages();
            loadMessageStats();
        } else {
            throw new Error(response?.message || 'Failed to delete message');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function closeMessageModal() {
    document.getElementById('message-modal').classList.add('hidden');
    currentMessageId = null;
    document.getElementById('reply-text').value = '';
}

function getMessageIcon(type) {
    switch(type) {
        case 'contact': return 'fa-address-book';
        case 'support': return 'fa-question-circle';
        case 'complaint': return 'fa-exclamation-triangle';
        case 'suggestion': return 'fa-lightbulb';
        default: return 'fa-envelope';
    }
}

function getTypeClass(type) {
    switch(type) {
        case 'contact': return 'bg-blue-100 text-blue-800';
        case 'support': return 'bg-green-100 text-green-800';
        case 'complaint': return 'bg-red-100 text-red-800';
        case 'suggestion': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getTypeText(type) {
    switch(type) {
        case 'contact': return 'Contact';
        case 'support': return 'Support';
        case 'complaint': return 'Complaint';
        case 'suggestion': return 'Suggestion';
        default: return type;
    }
}

function getStatusClass(status) {
    switch(status) {
        case 'new': return 'bg-blue-100 text-blue-800';
        case 'read': return 'bg-gray-100 text-gray-800';
        case 'replied': return 'bg-green-100 text-green-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'new': return 'New';
        case 'read': return 'Read';
        case 'replied': return 'Replied';
        default: return status;
    }
}

function formatDate(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US');
}

function formatDateTime(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleString('en-US');
}

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('messages-content').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

function showError(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('error').classList.remove('hidden');
}
</script>
@endsection