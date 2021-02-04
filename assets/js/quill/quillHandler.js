import QuillEditor from "./quillCreate";

export default function () {
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
        editor.addEventListener("keyup", (e) => autogrow(e.target))
        editor.addEventListener("click", (e) => {
            const target = e.target.closest('.ql-editor')
            if (null !== target) {
                autogrow(target)
            }
        })
        // editor.addEventListener('input', (e) => autogrow(e.target))
    })
    const autogrow = (element) => {
        element.style.height = 'inherit'
        // Get the computed styles for the element
        let computed = window.getComputedStyle(element)
        // Calculate the height
        let height = parseInt(computed.getPropertyValue('border-top-width'), 10)
            + parseInt(computed.getPropertyValue('padding-top'), 10)
            + element.scrollHeight
            + parseInt(computed.getPropertyValue('padding-bottom'), 10)
            + parseInt(computed.getPropertyValue('border-bottom-width'), 10)
        // Apply height
        element.style.height = height + 'px'
    }
}
