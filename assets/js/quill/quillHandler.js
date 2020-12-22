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
    })
}
