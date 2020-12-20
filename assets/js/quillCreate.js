// Code syntax highlighter
import hljs from 'highlight.js'
import Quill from 'quill'
// Resize image module
import ImageResize from 'quill-image-resize-module'

// Import styles
import '../styles/quill/quill.scss'

export default function (container) {
    // Register module image resizer
    Quill.register('modules/imageResize', ImageResize);
    // Toolbar options
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],

        ['link', 'image'],
        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['clean']                                         // remove formatting button
    ];
    // Quill options
    let  options = {
        debug: false,
        modules: {
            syntax: {
                highlight: text => hljs.highlightAuto(text).value,
            },
            toolbar: toolbarOptions,
            imageResize: {
                modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
            },
        },
        placeholder: 'Ajoutez votre texte ici...',
        theme: 'snow'
    };
    // Import icons
    Quill.import("ui/icons");
    // Create editor and return it
    return new Quill(container, options);
}
