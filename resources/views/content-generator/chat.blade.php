@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('content')
<div class="content-generator-wrapper">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row g-0">
            {{-- Sidebar --}}
            <aside class="col-lg-3 col-xl-2 d-none d-lg-block">
                <div class="sidebar-inner">
                    {{-- Credits Badge --}}
                    <div class="credits-badge">
                        <div class="credits-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="credits-info">
                            <span class="credits-label">{{ __('translation.content_generator.available_credits') }}</span>
                            <span class="credits-value">
                                @if(($availableCredits ?? 0) == -1)
                                    {{ __('translation.content_generator.unlimited') }}
                                @else
                                    {{ number_format($availableCredits ?? 0) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Specialties --}}
                    <div class="sidebar-section">
                        <h6 class="section-title">
                            <i class="fas fa-stethoscope"></i>
                            {{ __('translation.content_generator.specialty') }}
                        </h6>
                        <div class="specialty-list">
                            @foreach($specialties ?? [] as $specialty)
                                <button type="button" 
                                        class="specialty-btn" 
                                        data-specialty-id="{{ $specialty->id }}"
                                        data-specialty-slug="{{ $specialty->slug ?? $specialty->key }}"
                                        data-topics='@json($specialty->activeTopics->map(fn($t) => ["id" => $t->id, "name" => $t->name, "icon" => $t->icon]))'>
                                    @if($specialty->icon)
                                        <i class="fas {{ $specialty->icon }} me-1" style="color: {{ $specialty->color ?? 'inherit' }}"></i>
                                    @endif
                                    {{ $specialty->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Topics (Dynamic based on specialty selection) --}}
                    <div class="sidebar-section" id="topicsSection" style="display: none;">
                        <h6 class="section-title">
                            <i class="fas fa-tags"></i>
                            {{ __('translation.content_generator.topics') }}
                        </h6>
                        <div class="topics-list" id="topicsList">
                            {{-- Topics will be loaded dynamically --}}
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ __('translation.content_generator.chat.topics_hint') }}
                        </small>
                    </div>

                    {{-- Content Types --}}
                    <div class="sidebar-section">
                        <h6 class="section-title">
                            <i class="fas fa-file-medical"></i>
                            {{ __('translation.content_generator.type') }}
                        </h6>
                        <div class="content-type-grid">
                            @foreach($contentTypes ?? [] as $type)
                                <div class="content-type-item" 
                                     data-type-id="{{ $type->id }}"
                                     data-type-slug="{{ $type->slug }}"
                                     data-prompt="{{ $type->prompt_template ?? __('translation.content_generator.chat.generate_prompt', ['type' => $type->name]) }}">
                                    <div class="type-icon">
                                        <i class="fas {{ $type->icon ?? 'fa-file-alt' }}"></i>
                                    </div>
                                    <span class="type-name">{{ $type->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Recent History --}}
                    <div class="sidebar-section">
                        <h6 class="section-title">
                            <i class="fas fa-history"></i>
                            {{ __('translation.content_generator.chat.recent') }}
                        </h6>
                        <div class="history-list" id="recentHistoryList" style="display: block !important; visibility: visible !important;">
                            @forelse($recentContents ?? [] as $content)
                                <a href="{{ route('content.show', $content->id) }}" 
                                   class="d-block text-decoration-none py-2 px-3 rounded chat-history-item">
                                    <div class="chat-history-text">
                                        {{ Str::limit($content->input_data['topic'] ?? $content->specialty->name ?? 'Content', 35) }}
                                    </div>
                                </a>
                            @empty
                                <div class="py-2 px-3">
                                    <p class="mb-0 text-secondary small">{{ __('translation.content_generator.chat.no_recent_content') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main Chat Area --}}
            <main class="col-12 col-lg-9 col-xl-10">
                <div class="chat-container">
                    {{-- Chat Header --}}
                    <div class="chat-header">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="header-info">
                                <h1 class="page-title">{{ __('translation.content_generator.chat.page_title') }}</h1>
                                <p class="page-subtitle">{{ __('translation.content_generator.chat.page_subtitle') }}</p>
                            </div>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('content.history') }}" class="btn-action">
                                <i class="fas fa-clock-rotate-left"></i>
                                <span class="d-none d-sm-inline">{{ __('translation.content_generator.chat.history') }}</span>
                            </a>
                            <button class="btn-action btn-new" id="newChatBtn">
                                <i class="fas fa-plus"></i>
                                <span class="d-none d-sm-inline">{{ __('translation.content_generator.chat.new') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Messages Area --}}
                    <div class="messages-area" id="messagesArea">
                        {{-- Welcome Message --}}
                        <div class="welcome-screen" id="welcomeScreen">
                            <div class="welcome-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h2>{{ __('translation.content_generator.chat.welcome_title') }} {{ $user->name ?? __('translation.common.user') }}</h2>
                            <p>{{ __('translation.content_generator.chat.welcome_message') }}</p>
                            
                            {{-- Quick Start: Content Types from Database --}}
                            <div class="quick-start-section">
                                <h6 class="quick-start-label">
                                    <i class="fas fa-lightbulb"></i>
                                    {{ __('translation.content_generator.chat.choose_content_type') }}
                                </h6>
                                <div class="quick-start">
                                    @foreach($contentTypes ?? [] as $type)
                                        <div class="quick-card" 
                                             data-type-id="{{ $type->id }}"
                                             data-prompt="{{ $type->prompt_template ?? __('translation.content_generator.chat.generate_prompt', ['type' => $type->name]) }}">
                                            <i class="fas {{ $type->icon ?? 'fa-file-alt' }}"></i>
                                            <span>{{ $type->name }}</span>
                                            @if($type->description)
                                                <small class="type-description">{{ Str::limit($type->description, 50) }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Popular Specialties Quick Access --}}
                            @if(isset($specialties) && $specialties->count() > 0)
                            <div class="quick-start-section mt-4">
                                <h6 class="quick-start-label">
                                    <i class="fas fa-stethoscope"></i>
                                    {{ __('translation.content_generator.chat.popular_specialties') }}
                                </h6>
                                <div class="specialty-quick-list">
                                    @foreach($specialties->take(6) as $specialty)
                                        <button type="button" 
                                                class="specialty-quick-btn"
                                                data-specialty-id="{{ $specialty->id }}"
                                                data-specialty-slug="{{ $specialty->slug ?? $specialty->key }}"
                                                data-topics='@json($specialty->activeTopics->map(fn($t) => ["id" => $t->id, "name" => $t->name, "icon" => $t->icon]))'>
                                            @if($specialty->icon)
                                                <i class="fas {{ $specialty->icon }}" style="color: {{ $specialty->color ?? 'var(--ins-primary)' }}"></i>
                                            @else
                                                <i class="fas fa-heartbeat" style="color: var(--ins-primary)"></i>
                                            @endif
                                            <span>{{ $specialty->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Messages Container --}}
                        <div class="messages-container" id="messagesContainer"></div>
                    </div>

                    {{-- Input Area --}}
                    <div class="input-area">
                        <form id="chatForm" class="chat-form">
                            @csrf
                            <input type="hidden" name="specialty_id" id="specialtyInput">
                            <input type="hidden" name="topic_id" id="topicInput">
                            <input type="hidden" name="content_type_id" id="contentTypeInput">
                            
                            <div class="selected-options" id="selectedOptions"></div>
                            
                            <div class="input-wrapper">
                                <div class="input-row">
                                    <select name="language" id="languageSelect" class="form-select-sm">
                                        @foreach($languages ?? [] as $code => $name)
                                            <option value="{{ $code }}" {{ $code == app()->getLocale() ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="tone" id="toneSelect" class="form-select-sm">
                                        @foreach($tones ?? [] as $key => $tone)
                                            <option value="{{ $key }}">{{ $tone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="textarea-wrapper">
                                    <textarea name="prompt" 
                                              id="promptInput" 
                                              rows="1"
                                              placeholder="{{ __('translation.content_generator.chat.input_placeholder') }}"
                                              required></textarea>
                                    <button type="submit" class="send-btn" id="sendBtn">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

{{-- Mobile Sidebar Offcanvas --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ __('translation.content_generator.chat.options') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        {{-- Credits --}}
        <div class="mobile-credits">
            <i class="fas fa-coins"></i>
            <span>{{ number_format($availableCredits ?? 0) }} {{ __('translation.content_generator.available_credits') }}</span>
        </div>

        {{-- Specialties --}}
        <div class="mobile-section">
            <h6>{{ __('translation.content_generator.specialty') }}</h6>
            <div class="specialty-list">
                @foreach($specialties ?? [] as $specialty)
                    <button type="button" 
                            class="specialty-btn mobile-specialty-btn" 
                            data-specialty-id="{{ $specialty->id }}"
                            data-specialty-slug="{{ $specialty->slug ?? $specialty->key }}"
                            data-topics='@json($specialty->activeTopics->map(fn($t) => ["id" => $t->id, "name" => $t->name, "icon" => $t->icon]))'>
                        @if($specialty->icon)
                            <i class="fas {{ $specialty->icon }} me-1" style="color: {{ $specialty->color ?? 'inherit' }}"></i>
                        @endif
                        {{ $specialty->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Mobile Topics Section --}}
        <div class="mobile-section" id="mobileTopicsSection" style="display: none;">
            <h6>{{ __('translation.content_generator.topics') }}</h6>
            <div class="topics-list" id="mobileTopicsList">
                {{-- Topics will be loaded dynamically --}}
            </div>
        </div>

        {{-- Content Types --}}
        <div class="mobile-section">
            <h6>{{ __('translation.content_generator.type') }}</h6>
            <div class="content-type-grid mobile">
                @foreach($contentTypes ?? [] as $type)
                    <div class="content-type-item" 
                         data-type-id="{{ $type->id }}"
                         data-prompt="{{ $type->prompt_template ?? __('translation.content_generator.chat.generate_prompt', ['type' => $type->name]) }}">
                        <i class="fas {{ $type->icon ?? 'fa-file-alt' }}"></i>
                        <span>{{ $type->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const promptInput = document.getElementById('promptInput');
    const sendBtn = document.getElementById('sendBtn');
    const messagesArea = document.getElementById('messagesArea');
    const messagesContainer = document.getElementById('messagesContainer');
    const welcomeScreen = document.getElementById('welcomeScreen');
    const selectedOptions = document.getElementById('selectedOptions');
    const specialtyInput = document.getElementById('specialtyInput');
    const topicInput = document.getElementById('topicInput');
    const contentTypeInput = document.getElementById('contentTypeInput');
    const newChatBtn = document.getElementById('newChatBtn');
    const topicsSection = document.getElementById('topicsSection');
    const topicsList = document.getElementById('topicsList');
    const mobileTopicsSection = document.getElementById('mobileTopicsSection');
    const mobileTopicsList = document.getElementById('mobileTopicsList');

    let selectedSpecialty = null;
    let selectedTopic = null;
    let selectedContentType = null;

    // Translation strings
    const translations = {
        selectTopic: "{{ __('translation.content_generator.chat.select_topic') }}",
        noTopics: "{{ __('translation.content_generator.chat.no_topics') }}",
        error: "{{ __('translation.content_generator.chat.error_occurred') }}",
        connectionError: "{{ __('translation.content_generator.chat.connection_error') }}"
    };

    // Auto-resize textarea
    promptInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Specialty selection - handles both desktop and mobile
    document.querySelectorAll('.specialty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all specialty buttons
            document.querySelectorAll('.specialty-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Also highlight the same specialty in the other view (mobile/desktop sync)
            const specId = this.dataset.specialtyId;
            document.querySelectorAll(`.specialty-btn[data-specialty-id="${specId}"]`).forEach(b => b.classList.add('active'));
            
            selectedSpecialty = {
                id: specId,
                name: this.textContent.trim(),
                slug: this.dataset.specialtySlug
            };
            specialtyInput.value = selectedSpecialty.id;
            
            // Clear selected topic when specialty changes
            selectedTopic = null;
            topicInput.value = '';
            
            // Load and display topics from data attribute
            const topicsData = JSON.parse(this.dataset.topics || '[]');
            displayTopics(topicsData);
            
            updateSelectedOptions();
            
            // Close mobile sidebar if open
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
            if (offcanvas) offcanvas.hide();
        });
    });

    // Display topics in both desktop and mobile views
    function displayTopics(topics) {
        // Clear existing topics
        topicsList.innerHTML = '';
        mobileTopicsList.innerHTML = '';
        
        if (topics.length === 0) {
            const noTopicsHtml = `<p class="empty-state text-muted">${translations.noTopics}</p>`;
            topicsList.innerHTML = noTopicsHtml;
            mobileTopicsList.innerHTML = noTopicsHtml;
            topicsSection.style.display = 'block';
            mobileTopicsSection.style.display = 'block';
            return;
        }
        
        topics.forEach(topic => {
            const topicHtml = `
                <button type="button" class="topic-btn" data-topic-id="${topic.id}" data-topic-name="${topic.name}">
                    ${topic.icon ? `<i class="fas ${topic.icon} me-1"></i>` : '<i class="fas fa-tag me-1"></i>'}
                    ${topic.name}
                </button>
            `;
            topicsList.insertAdjacentHTML('beforeend', topicHtml);
            mobileTopicsList.insertAdjacentHTML('beforeend', topicHtml);
        });
        
        // Show topics sections
        topicsSection.style.display = 'block';
        mobileTopicsSection.style.display = 'block';
        
        // Attach event listeners to new topic buttons
        attachTopicListeners();
    }

    // Attach click listeners to topic buttons
    function attachTopicListeners() {
        document.querySelectorAll('.topic-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active from all topic buttons
                document.querySelectorAll('.topic-btn').forEach(b => b.classList.remove('active'));
                
                // Mark this topic and its twin (mobile/desktop) as active
                const topicId = this.dataset.topicId;
                document.querySelectorAll(`.topic-btn[data-topic-id="${topicId}"]`).forEach(b => b.classList.add('active'));
                
                selectedTopic = {
                    id: topicId,
                    name: this.dataset.topicName
                };
                topicInput.value = selectedTopic.id;
                updateSelectedOptions();
                
                // Close mobile sidebar if open
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
                if (offcanvas) offcanvas.hide();
            });
        });
    }

    // Content type selection
    document.querySelectorAll('.content-type-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.content-type-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Sync mobile/desktop
            const typeId = this.dataset.typeId;
            const prompt = this.dataset.prompt;
            document.querySelectorAll(`.content-type-item[data-type-id="${typeId}"]`).forEach(i => i.classList.add('active'));
            
            selectedContentType = {
                id: typeId,
                name: this.querySelector('.type-name, span').textContent.trim()
            };
            contentTypeInput.value = selectedContentType.id;
            updateSelectedOptions();
            
            // Set prompt if available
            if (prompt) {
                promptInput.value = prompt;
                promptInput.dispatchEvent(new Event('input'));
            }
            
            promptInput.focus();
            
            // Close mobile sidebar if open
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
            if (offcanvas) offcanvas.hide();
        });
    });

    // Update selected options display
    function updateSelectedOptions() {
        selectedOptions.innerHTML = '';
        
        if (selectedSpecialty) {
            const tag = createOptionTag(selectedSpecialty.name, 'specialty');
            selectedOptions.appendChild(tag);
        }
        
        if (selectedTopic) {
            const tag = createOptionTag(selectedTopic.name, 'topic');
            selectedOptions.appendChild(tag);
        }
        
        if (selectedContentType) {
            const tag = createOptionTag(selectedContentType.name, 'contentType');
            selectedOptions.appendChild(tag);
        }
    }

    // Create option tag - Uses centralized ChatManager with fallback
    function createOptionTag(text, type) {
        const onRemove = function(tagType) {
            if (tagType === 'specialty') {
                selectedSpecialty = null;
                selectedTopic = null;
                specialtyInput.value = '';
                topicInput.value = '';
                document.querySelectorAll('.specialty-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.topic-btn').forEach(b => b.classList.remove('active'));
                topicsSection.style.display = 'none';
                mobileTopicsSection.style.display = 'none';
            } else if (tagType === 'topic') {
                selectedTopic = null;
                topicInput.value = '';
                document.querySelectorAll('.topic-btn').forEach(b => b.classList.remove('active'));
            } else {
                selectedContentType = null;
                contentTypeInput.value = '';
                document.querySelectorAll('.content-type-item').forEach(i => i.classList.remove('active'));
            }
            updateSelectedOptions();
        };
        
        if (typeof ChatManager !== 'undefined') {
            return ChatManager.createOptionTag(text, type, onRemove);
        }
        // Fallback
        const tag = document.createElement('span');
        tag.className = `selected-option selected-${type}`;
        tag.innerHTML = `${text} <i class="fas fa-times remove" data-type="${type}"></i>`;
        tag.querySelector('.remove').addEventListener('click', () => onRemove(type));
        return tag;
    }

    // Quick start cards - Select Content Type and optionally set prompt
    document.querySelectorAll('.quick-card').forEach(card => {
        card.addEventListener('click', function() {
            const typeId = this.dataset.typeId;
            const prompt = this.dataset.prompt;
            
            // Select the content type in sidebar
            if (typeId) {
                document.querySelectorAll('.content-type-item').forEach(i => i.classList.remove('active'));
                document.querySelectorAll(`.content-type-item[data-type-id="${typeId}"]`).forEach(i => i.classList.add('active'));
                
                selectedContentType = {
                    id: typeId,
                    name: this.querySelector('span').textContent.trim()
                };
                contentTypeInput.value = typeId;
                updateSelectedOptions();
            }
            
            // Set prompt if available
            if (prompt) {
                promptInput.value = prompt;
                promptInput.dispatchEvent(new Event('input'));
            }
            
            promptInput.focus();
        });
    });

    // Quick specialty buttons in welcome screen
    document.querySelectorAll('.specialty-quick-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const specId = this.dataset.specialtyId;
            
            // Trigger the same logic as sidebar specialty buttons
            document.querySelectorAll('.specialty-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll(`.specialty-btn[data-specialty-id="${specId}"]`).forEach(b => b.classList.add('active'));
            
            selectedSpecialty = {
                id: specId,
                name: this.querySelector('span').textContent.trim(),
                slug: this.dataset.specialtySlug
            };
            specialtyInput.value = specId;
            
            // Clear selected topic
            selectedTopic = null;
            topicInput.value = '';
            
            // Load topics
            const topicsData = JSON.parse(this.dataset.topics || '[]');
            displayTopics(topicsData);
            
            updateSelectedOptions();
            promptInput.focus();
        });
    });

    // Form submission - Uses centralized ApiClient
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const prompt = promptInput.value.trim();
        if (!prompt) return;
        
        welcomeScreen.style.display = 'none';
        addMessage(prompt, 'user');
        promptInput.value = '';
        promptInput.style.height = 'auto';
        
        const typingId = showTyping();
        sendBtn.disabled = true;
        
        const formData = new FormData(chatForm);
        formData.set('prompt', prompt);
        
        try {
            const data = await ApiClient.post('{{ route("content.generate") }}', formData);
            removeTyping(typingId);
            
            if (data.success && data.content_id) {
                // Add content with link to full page
                const contentUrl = `{{ url(app()->getLocale() . '/generate/result') }}/${data.content_id}`;
                const contentWithLink = data.content + 
                    `<div class="mt-3 pt-3 border-top"><a href="${contentUrl}" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt me-1"></i>{{ __('translation.content_generator.view_full_content') }}</a></div>`;
                addMessage(contentWithLink, 'assistant', false);
                
                // Refresh recent history in sidebar
                refreshRecentHistory();
            } else {
                addMessage(data.message || translations.error, 'assistant', true);
            }
        } catch (error) {
            removeTyping(typingId);
            addMessage(translations.connectionError, 'assistant', true);
        }
        
        sendBtn.disabled = false;
    });

    // Add message to chat - Uses centralized ChatManager with fallback
    function addMessage(content, type, isError = false) {
        if (typeof ChatManager !== 'undefined') {
            return ChatManager.addMessage(content, type, isError, {
                containerId: 'messagesContainer',
                areaId: 'messagesArea'
            });
        }
        // Fallback
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        
        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        avatar.innerHTML = type === 'assistant' ? '<i class="fas fa-robot"></i>' : '<i class="fas fa-user"></i>';
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        if (isError) contentDiv.style.borderColor = '#ef4444';
        contentDiv.innerHTML = content.replace(/\n/g, '<br>');
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(contentDiv);
        messagesContainer.appendChild(messageDiv);
        messagesArea.scrollTop = messagesArea.scrollHeight;
        return messageDiv;
    }

    // Typing indicator - Uses centralized ChatManager with fallback
    function showTyping() {
        if (typeof ChatManager !== 'undefined') {
            return ChatManager.showTyping({ containerId: 'messagesContainer', areaId: 'messagesArea' });
        }
        // Fallback
        const id = 'typing-' + Date.now();
        const typingDiv = document.createElement('div');
        typingDiv.id = id;
        typingDiv.className = 'message assistant';
        typingDiv.innerHTML = `<div class="message-avatar"><i class="fas fa-robot"></i></div><div class="message-content"><div class="typing-indicator"><span></span><span></span><span></span></div></div>`;
        messagesContainer.appendChild(typingDiv);
        messagesArea.scrollTop = messagesArea.scrollHeight;
        return id;
    }

    function removeTyping(id) {
        if (typeof ChatManager !== 'undefined') { ChatManager.removeTyping(id); return; }
        const typingDiv = document.getElementById(id);
        if (typingDiv) typingDiv.remove();
    }

    // New chat - reset everything
    newChatBtn.addEventListener('click', function() {
        messagesContainer.innerHTML = '';
        welcomeScreen.style.display = 'flex';
        selectedSpecialty = null;
        selectedTopic = null;
        selectedContentType = null;
        specialtyInput.value = '';
        topicInput.value = '';
        contentTypeInput.value = '';
        selectedOptions.innerHTML = '';
        topicsSection.style.display = 'none';
        mobileTopicsSection.style.display = 'none';
        document.querySelectorAll('.specialty-btn, .topic-btn, .content-type-item').forEach(el => el.classList.remove('active'));
    });

    // Enter to send (Shift+Enter for new line)
    promptInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Refresh recent history in sidebar via AJAX
    async function refreshRecentHistory() {
        try {
            const data = await ApiClient.get('{{ route("content.recent") }}');
            if (data.success && data.html) {
                const historyList = document.getElementById('recentHistoryList');
                if (historyList) {
                    historyList.innerHTML = data.html;
                }
            }
        } catch (error) {
            console.error('Failed to refresh recent history:', error);
        }
    }

    // Auto-select first specialty if user has specialties
    @if(isset($specialties) && $specialties->count() > 0)
    (function autoSelectFirstSpecialty() {
        const firstSpecialtyBtn = document.querySelector('.specialty-btn');
        if (firstSpecialtyBtn) {
            firstSpecialtyBtn.click();
        }
    })();
    @endif
});
</script>
@endsection
