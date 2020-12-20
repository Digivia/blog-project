import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
// Quill Editor instantiate
import QuillEditor from './js/quillCreate'

const editor = QuillEditor('.editor');

document.getElementById("ok").addEventListener('click', function (e) {
    let test = document.getElementById("test")
    test.innerHTML = editor.root.innerHTML;
})
