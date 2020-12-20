import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
// Quill Editor instantiate
import QuillEditor from './js/quillCreate'

document.querySelectorAll('.rich-text').forEach((editor) => {
    const target = editor.dataset.targetId;
    if (undefined !== target) {
        // Get target element (ie : hidden field)
        let targetElement = document.getElementById(target)
        if (null === targetElement) {
            return
        }
        // If target element content something, push it in editor
        if (targetElement.value.length) {
            editor.innerHTML = targetElement.value
        }
        // Start editor
        let quillInstance = QuillEditor(editor)
        // Search form
        const form = editor.closest('form')
        // On submit, fill target element with editor content
        if (null !== form) {
            form.addEventListener('submit', function (e) {
                targetElement.value = quillInstance.root.innerHTML
            })
        }
    }
})
