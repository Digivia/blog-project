import Toast from "bootstrap/js/src/toast";

export default function () {
    document.addEventListener('DOMContentLoaded', (event) => {
        // Bootstrap toast
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        toastElList.map(function (toastEl) {
            return new Toast(toastEl, {
                animation: true,
                autohide: true,
                delay: 10000,
            }).show()
        })
    })
}
