import Tooltip from "bootstrap/js/src/tooltip";

export default function () {
    document.addEventListener('DOMContentLoaded', (event) => {
        // Bootstrap toast
        let tooltipElList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipElList.map(function (tooltip) {
            return new Tooltip(tooltip)
        })
    })
}
