import './styles/admin.scss'

// start the Stimulus application
import './bootstrap'
import 'bootstrap/js/src/collapse'
import 'bootstrap/js/src/dropdown'
import Toast from "bootstrap/js/src/toast"
import hljs from 'highlight.js'
import flashMessage from "./js/flashMessage"
import tooltip from "./js/tooltip"
import quillHandler from "./js/quill/quillHandler"
import ChoiceJs from './js/choicesJsInit'
import formReset from './js/formReset'

// Flash messages with sweetAlert2
flashMessage()
// Quill handler for rich text fields
quillHandler()
// Start highlight js
hljs.initHighlighting();
// Choice Js
ChoiceJs()
// Start tooltip
tooltip()
// Form reset (search form)
formReset()
