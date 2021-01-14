import Swal from 'sweetalert2'

export default function () {
    const Toast = Swal.mixin({
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    })

    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('.flashbag').forEach( (alert) => {
            // Hide alert if displayed
            alert.style.display = 'none';
            // Get flash type and content to display in sweet alert
            let flashType = alert.dataset.flashType
            let content = alert.innerHTML
            // Launch sweet alert :)
            Toast.fire({
                type:  flashType,
                title: content,
                icon: flashType,
            })
        })
    })
}
