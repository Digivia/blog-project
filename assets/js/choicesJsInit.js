import Choice from 'choices.js'
// Apply choice.js plugin on all .js-choice select
export default function () {
    document.querySelectorAll('.js-choice').forEach((select) => {
        new Choice(select, {
            loadingText: 'Chargement...',
            noResultsText: 'Aucun résultat',
            noChoicesText: 'Aucun résultat',
            itemSelectText: '',
            shouldSort: false,
        });
    });
    document.querySelectorAll('.js-choice-multiple').forEach((select) => {
        new Choice(select, {
            loadingText: 'Chargement...',
            noResultsText: 'Aucun résultat',
            noChoicesText: 'Aucun résultat',
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: true,
        });
    });
}