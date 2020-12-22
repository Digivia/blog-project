import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import hljs from 'highlight.js'
import flashMessage from "./js/flashMessage";
import quillHandler from "./js/quill/quillHandler";

// Flash messages with sweetAlert2
flashMessage()
// Quill handler for rich text fields
quillHandler()
// Start highlight js
hljs.initHighlighting();
