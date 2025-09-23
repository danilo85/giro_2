// Variáveis globais
let carouselTextCount = 0;
console.log('social-post-form.js carregado!');
let selectedHashtags = [];
let hashtagSuggestions = [];
let currentEmojiTarget = null;

document.addEventListener('DOMContentLoaded', function() {
    // Common initialization
    updatePreview();

    // Event listeners
    const titleElement = document.getElementById('titulo');
    const contentElement = document.getElementById('legenda');
    const textoFinalElement = document.getElementById('texto_final');
    const hashtagInput = document.getElementById('hashtagInput');
    const carouselTextsInput = document.getElementById('carouselTextsInput');

    if (titleElement) titleElement.addEventListener('input', updatePreview);
    if (contentElement) contentElement.addEventListener('input', updatePreview);
    if (textoFinalElement) textoFinalElement.addEventListener('input', updatePreview);
    if (hashtagInput) {
        hashtagInput.addEventListener('keydown', handleHashtagInput);
        hashtagInput.addEventListener('input', handleHashtagSearch);
    }
    if (carouselTextsInput) {
        carouselTextsInput.addEventListener('input', updateCarouselPreview);
    }

    // Load existing data for edit page
    if (document.body.classList.contains('page-edit-social-post')) {
        const hashtagsData = document.getElementById('hashtagsData');
        if (hashtagsData) {
            selectedHashtags = JSON.parse(hashtagsData.textContent);
            updateHashtagDisplay();
        }
    }
});

// Text formatting
function formatText(command, targetId) {
    const textarea = document.getElementById(targetId) || document.getElementById('content') || document.getElementById('legenda');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    if (selectedText) {
        let formattedText = '';
        switch (command) {
            case 'bold':
                formattedText = `**${selectedText}**`;
                break;
            case 'italic':
                formattedText = `*${selectedText}*`;
                break;
            case 'underline':
                formattedText = `__${selectedText}__`;
                break;
        }

        textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
        updatePreview();
    }
}

function formatCarouselText(command) {
    const textarea = document.getElementById('carouselTextsInput');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    if (selectedText) {
        let formattedText = '';
        switch (command) {
            case 'bold':
                formattedText = `**${selectedText}**`;
                break;
            case 'italic':
                formattedText = `*${selectedText}*`;
                break;
            case 'underline':
                formattedText = `__${selectedText}__`;
                break;
        }

        textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
        updateCarouselPreview();
    }
}

function formatTextFinal(command) {
    const textarea = document.getElementById('texto_final');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    if (selectedText) {
        let formattedText = '';
        switch (command) {
            case 'bold':
                formattedText = `**${selectedText}**`;
                break;
            case 'italic':
                formattedText = `*${selectedText}*`;
                break;
            case 'underline':
                formattedText = `__${selectedText}__`;
                break;
        }

        textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
        updatePreview();
    }
}

function insertEmoji(emoji) {
    const textarea = document.getElementById('content') || document.getElementById('legenda');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + emoji.length, start + emoji.length);
    updatePreview();
}

function insertEmojiToCarousel(emoji) {
    const textarea = document.getElementById('carouselTextsInput');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + emoji.length, start + emoji.length);
    updateCarouselPreview();
}

function insertEmojiToFinal(emoji) {
    const textarea = document.getElementById('texto_final');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + emoji.length, start + emoji.length);
    updatePreview();
}

// Expose functions globally for HTML onclick handlers
window.insertEmoji = insertEmoji;
window.insertEmojiToCarousel = insertEmojiToCarousel;
window.insertEmojiToFinal = insertEmojiToFinal;
window.formatText = formatText;
window.formatCarouselText = formatCarouselText;
window.formatTextFinal = formatTextFinal;
window.updatePreview = updatePreview;
window.updateCarouselPreview = updateCarouselPreview;
window.insertCarouselDivider = insertCarouselDivider;
window.handleHashtagInput = handleHashtagInput;
window.handleHashtagSearch = handleHashtagSearch;
window.handleHashtagKeyup = handleHashtagKeyup;
window.selectHashtag = selectHashtag;
window.removeHashtag = removeHashtag;
window.addHashtag = addHashtag;
window.updateHashtagDisplay = updateHashtagDisplay;
window.saveDraft = saveDraft;
window.toggleCallToActionType = toggleCallToActionType;
window.previewCallToActionImage = previewCallToActionImage;
window.removeCurrentImage = removeCurrentImage;

// Hashtag functions
function handleHashtagSearch(e) {
    const query = e.target.value.trim();
    if (query.length > 0) {
        fetch(`/social-posts/api/hashtags/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => showHashtagSuggestions(data))
            .catch(error => console.error('Error searching hashtags:', error));
    } else {
        hideHashtagSuggestions();
    }
}

// Alias for handleHashtagSearch to maintain compatibility
function handleHashtagKeyup(e) {
    handleHashtagSearch(e);
}

function showHashtagSuggestions(hashtags) {
    const container = document.getElementById('hashtagSuggestions');
    if (!container) return;

    if (hashtags.length === 0) {
        hideHashtagSuggestions();
        return;
    }

    container.innerHTML = `
        <div class="bg-white border border-gray-200 rounded-md shadow-lg">
            ${hashtags.map(hashtag => `
                <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-100 border-b border-gray-100 last:border-b-0"
                        onclick="selectHashtag('${hashtag.name}')">
                    #${hashtag.name}
                    <small class="text-muted ms-2">(${hashtag.usage_count} uses)</small>
                </button>
            `).join('')}
        </div>`;
    container.style.display = 'block';
}

function hideHashtagSuggestions() {
    const element = document.getElementById('hashtagSuggestions');
    if (element) element.style.display = 'none';
}

function selectHashtag(hashtag) {
    const hashtagInput = document.getElementById('hashtagInput');
    if(hashtagInput) hashtagInput.value = hashtag;
    addHashtag();
    hideHashtagSuggestions();
}

function handleHashtagInput(event) {
    const input = event.target;
    if (event.key === ' ' || event.key === 'Enter') {
        event.preventDefault();
        addHashtag();
    } else if (event.key === 'Backspace' && input.value === '' && selectedHashtags.length > 0) {
        removeHashtag(selectedHashtags[selectedHashtags.length - 1]);
    }
}

function addHashtag() {
    const input = document.getElementById('hashtagInput');
    if (!input) return;

    const hashtags = input.value.trim().split(/[ ,]+/).filter(Boolean);
    hashtags.forEach(hashtag => {
        const cleanHashtag = hashtag.replace(/[^a-zA-Z0-9_\u00C0-\u017F]/g, '');
        if (cleanHashtag && !selectedHashtags.includes(cleanHashtag) && selectedHashtags.length < 30) {
            selectedHashtags.push(cleanHashtag);
        }
    });

    updateHashtagDisplay();
    input.value = '';
    hideHashtagSuggestions();
}

function removeHashtag(hashtag) {
    selectedHashtags = selectedHashtags.filter(h => h !== hashtag);
    updateHashtagDisplay();
}

function updateHashtagDisplay() {
    console.log('updateHashtagDisplay chamada, selectedHashtags:', selectedHashtags);
    // Tenta encontrar o container correto (create e edit usam selectedHashtags)
    const container = document.getElementById('selectedHashtags');
    const counter = document.getElementById('hashtagCounter');
    const hiddenInput = document.getElementById('hashtags');

    console.log('Elementos encontrados:', { container, counter, hiddenInput });
    
    if (!container) {
        console.error('Container selectedHashtags não encontrado!');
        return;
    }

    if (container) {
        container.innerHTML = selectedHashtags.map(hashtag => `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 mr-2 mb-2">
                #${hashtag}
                <button type="button" class="ml-1 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100" onclick="removeHashtag('${hashtag}')">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </span>
        `).join('');
    }

    if (counter) {
        counter.textContent = `${selectedHashtags.length}/30`;
        counter.className = selectedHashtags.length >= 30 ? 'text-sm text-red-500' : 'text-sm text-gray-500';
    }

    if (hiddenInput) {
        hiddenInput.value = selectedHashtags.join(',');
    }
    updatePreview();
}

// Carousel functions
function updateCarouselPreview() {
    const textarea = document.getElementById('carouselTextsInput');
    const counter = document.getElementById('carouselCounter');
    if (!textarea || !counter) return;

    // Filtrar slides vazios e remover quebras de linha desnecessárias
    const slides = textarea.value.trim().split('---')
        .map(slide => slide.trim().replace(/^\n+|\n+$/g, '')) // Remove \n do início e fim
        .filter(slide => slide.length > 0 && slide !== '\n');
    counter.textContent = `${slides.length} slides`;
    updatePreview();
}

function insertCarouselDivider() {
    const textarea = document.getElementById('carouselTextsInput');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const divider = '\n---\n';
    textarea.value = textarea.value.substring(0, start) + divider + textarea.value.substring(start);
    textarea.focus();
    textarea.setSelectionRange(start + divider.length, start + divider.length);
    updateCarouselPreview();
}

// Preview function
function updatePreview() {
    const previewElement = document.getElementById('postPreview');
    if (!previewElement) return;

    const title = document.getElementById('titulo')?.value || '';
    const legend = document.getElementById('legenda')?.value || '';
    const final_text = document.getElementById('texto_final')?.value || '';
    const carouselSlides = (document.getElementById('carouselTextsInput')?.value || '')
        .split('---')
        .map(s => s.trim().replace(/^\n+|\n+$/g, '')) // Remove \n do início e fim
        .filter(s => s.length > 0 && s !== '\n');

    let previewHTML = '';

    if (title) previewHTML += `<div class="mb-3"><strong>📱 Título:</strong><br>${title}</div>`;
    if (legend) previewHTML += `<div class="mb-3"><strong>📝 Conteúdo:</strong><br>${legend.replace(/\n/g, '<br>')}</div>`;

    carouselSlides.forEach((slide, index) => {
        previewHTML += `<div class="mb-3"><strong>📱 Carrossel ${index + 1}:</strong><br>${slide.replace(/\n/g, '<br>')}</div>`;
    });

    if (selectedHashtags.length > 0) {
        previewHTML += `<div class="mb-3"><strong>🏷️ Hashtags:</strong><br>${selectedHashtags.map(h => `#${h}`).join(' ')}</div>`;
    }

    if (final_text) previewHTML += `<div class="mb-3"><strong>📢 Call-to-Action:</strong><br>${final_text.replace(/\n/g, '<br>')}</div>`;

    if (!previewHTML) {
        previewHTML = `
            <div class="text-gray-500 dark:text-gray-400 text-center py-3">
                <svg class="w-8 h-8 mx-auto mb-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H2ZM2 5h16v10H2V5Z"/>
                </svg>
                <p class="mb-0">Preview do post aparecerá aqui</p>
            </div>`;
    }

    previewElement.innerHTML = `<div class="social-post-preview">${previewHTML}</div>`;
}

// Form submission
function saveDraft() {
    const statusInput = document.getElementById('status');
    if (statusInput) statusInput.value = 'rascunho';
    document.getElementById('socialPostForm').submit();
}

// Call-to-Action functions
function toggleCallToActionType() {
    const textRadio = document.querySelector('input[name="call_to_action_type"][value="text"]');
    const imageRadio = document.querySelector('input[name="call_to_action_type"][value="image"]');
    const textSection = document.getElementById('text_call_to_action');
    const imageSection = document.getElementById('image_call_to_action');
    
    if (textRadio && textRadio.checked) {
        if (textSection) textSection.classList.remove('hidden');
        if (imageSection) imageSection.classList.add('hidden');
        // Limpar o input de imagem
        const imageInput = document.getElementById('call_to_action_image');
        if (imageInput) imageInput.value = '';
        resetImagePreview();
    } else if (imageRadio && imageRadio.checked) {
        if (textSection) textSection.classList.add('hidden');
        if (imageSection) imageSection.classList.remove('hidden');
        // Limpar o textarea de texto
        const textInput = document.getElementById('texto_final');
        if (textInput) textInput.value = '';
    }
    
    updatePreview();
}

function previewCallToActionImage(event) {
    const file = event.target.files[0];
    processCallToActionImage(file);
}

function processCallToActionImage(file) {
    const uploadPlaceholder = document.getElementById('upload_placeholder');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    const fileName = document.getElementById('file_name');
    const imageInput = document.getElementById('call_to_action_image');
    
    if (file) {
        // Validar tipo de arquivo
        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];
        if (!allowedTypes.includes(file.type)) {
            alert('Por favor, selecione apenas arquivos PNG, JPG ou JPEG.');
            if (imageInput) imageInput.value = '';
            return;
        }
        
        // Validar tamanho do arquivo (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('O arquivo deve ter no máximo 2MB.');
            if (imageInput) imageInput.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImg) previewImg.src = e.target.result;
            if (fileName) fileName.textContent = file.name;
            if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
            if (imagePreview) imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
        
        // Atualizar o input file com o arquivo
        if (imageInput) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;
        }
    } else {
        resetImagePreview();
    }
}

// Drag and Drop functionality
function initializeDragAndDrop() {
    const dropZone = document.querySelector('label[for="call_to_action_image"]');
    
    if (!dropZone) return;
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        dropZone.classList.remove('border-gray-300', 'bg-gray-50', 'dark:bg-gray-700');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        dropZone.classList.add('border-gray-300', 'bg-gray-50', 'dark:bg-gray-700');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            processCallToActionImage(files[0]);
        }
    }
}

// Initialize drag and drop when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeDragAndDrop();
});

function resetImagePreview() {
    const uploadPlaceholder = document.getElementById('upload_placeholder');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    const fileName = document.getElementById('file_name');
    
    if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
    if (imagePreview) imagePreview.classList.add('hidden');
    if (previewImg) previewImg.src = '';
    if (fileName) fileName.textContent = '';
}

function removeCurrentImage() {
    const currentImageDiv = document.getElementById('current_image');
    const imageInput = document.getElementById('call_to_action_image');
    
    if (currentImageDiv) currentImageDiv.style.display = 'none';
    
    // Adicionar um campo hidden para indicar que a imagem deve ser removida
    let removeInput = document.getElementById('remove_call_to_action_image');
    if (!removeInput) {
        removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_call_to_action_image';
        removeInput.id = 'remove_call_to_action_image';
        removeInput.value = '1';
        if (imageInput && imageInput.parentNode) {
            imageInput.parentNode.appendChild(removeInput);
        }
    } else {
        removeInput.value = '1';
    }
}