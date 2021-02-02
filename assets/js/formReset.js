export default function () {
    document.addEventListener('DOMContentLoaded', (event) => {
        let formReset = [].slice.call(document.querySelectorAll('.form-reset-icon'))
        formReset.map(function (resetButton) {
            resetButton.addEventListener('click', (e) => {
                const form = resetButton.closest('form')
                if (null !== form) {
                    let inputFields = [].slice.call(form.querySelectorAll('input'))
                    inputFields.map((input) => {
                        input.setAttribute('value', '')
                    })
                }
            })
        })
    })
}
