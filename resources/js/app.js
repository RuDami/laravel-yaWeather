/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('overlayscrollbars');
require('../../vendor/almasaeed2010/adminlte/dist/js/adminlte');
require('datatables.net-bs4');
require('datatables.net-searchpanes');
require('datatables.net-select-bs4');
//require('datatables.net-searchpanes-bs4');
$.extend(true, $.fn.DataTable.defaults, {
    language: {
        processing: "Ожидайте...",
        search: "Поиск&nbsp;:",
        lengthMenu: "Показывать _MENU_ элементов",
        info: "Элементы с _START_  по _END_ из _TOTAL_",
        infoEmpty: "Нет записей",
        //infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        infoPostFix: "",
        loadingRecords: "Загрузка записей...",
        zeroRecords: "Нет ни одной записи",
        emptyTable: "Таблица пуста",
        paginate: {
            first: "Первая",
            previous: "Предыдущая",
            next: "Следующая",
            last: "Последняя"
        },
        aria: {
            //    sortAscending:  ": activer pour trier la colonne par ordre croissant",
            //  sortDescending: ": activer pour trier la colonne par ordre décroissant"
        }
    },
    i18n: {
        create: {
            button: "Создать",
            title: "Создание новой записи",
            submit: "Применить"
        },
        edit: {
            button: "Редактировать",
            title: "Редактирование записи",
            submit: "Применить"
        },
        remove: {
            button: "Удалить",
            title: "Удаление записи",
            submit: "Применить",
            confirm: {
                _: "Вы действительно хотите удалить %d записей?",
                1: "Вы действительно хотите удалить запись?"
            }
        },
        error: {
            system: "Произошла ошибка обратитесь в службу поддержки"
        },
        multi: {
            title: "Несколько значений",
            info: "Выбранные элементы содержат различные значения для этой записи. Чтобы изменить и сделать все элементы, чтобы эта запись для того же значения, нажмите или нажмите здесь, в противном случае они будут сохранять свои значения отдельных.",
            restore: "Отменить изменения"
        },
        datetime: {
            previous: '<',
            next: '>',
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']
        }
    }
});
/*
window.Vue = require('vue');
*/
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
/*
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
*/
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
const app = new Vue({
    el: '#app',
});
*/
