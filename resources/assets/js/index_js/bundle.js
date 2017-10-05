/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

$(document).ready(function() {
    $("body").show();
    var exists = [];

    $('.classification_container').css("height", $('.content').height() - 1);


    // $('.themename').click(function() {

    //     var ThemeName = this.attributes["name"].value;
    //     var id = this.id;

    //     if (exists.indexOf(id) >= 0) {
    //         alert('已經存在該項目');
    //     } else {
    //         var namePag = document.createElement("div");
    //         namePag.setAttribute("class", "ThemePageTitle");
    //         namePag.innerHTML = ThemeName;
    //         $('.classifications_title').append(namePag);
    //         exists.push(id);
    //     }

    // })

});

var Vue = __webpack_require__(!(function webpackMissingModule() { var e = new Error("Cannot find module \"vue\""); e.code = 'MODULE_NOT_FOUND'; throw e; }()));
Vue.use(__webpack_require__(!(function webpackMissingModule() { var e = new Error("Cannot find module \"vue-resource\""); e.code = 'MODULE_NOT_FOUND'; throw e; }())));

Vue.component('child', {
    // 声明 props
    // props: ['messageq'],
    // 就像 data 一样，prop 可以用在模板内
    // 同样也可以在 vm 实例中像“this.message”这样使用
    template: '<div class="classifications_content_page" v-on:click="addpag">456</div>',
    data: function() {
        return {
            message: 123
        }
    },
    methods: {
        app: function() {
            alert('asd');
        },
        aee: function() {
            alert('asd');
            var namePag2 = document.createElement("child");
            $('.classifications_title').append(namePag2);
        }
    }
})

var classificationPagging = new Vue({
    el: '.classification_container',
    data: {
        show: true,
        exists: [],
        clickItemId: " ",
        message: "hello123",
        pos: [],
        range: 0,
    },
    created: function() {
        this.loadPersonalObjectives();
    },
    methods: {
        toggle: function() {
            this.show = !this.show;
        },
        appearId: function() {
            console.log('46');
        },
        appear: function(item) {
            if (this.exists.indexOf(item.id) >= 0) {
                alert('已經存在該項目');
                this.clickItemId = item.id;
            } else {
                var namePag = document.createElement("div");
                namePag.setAttribute("class", "ThemePageTitle");
                namePag.innerHTML = item.themename;
                $('.classifications_title').append(namePag);
                this.exists.push(item.id);
                this.clickItemId = item.id;
                this.Item = item;
                // <div class="ThemePageTitle">item.themename</div>
                var namePag2 = document.createElement("child");
                $('.classifications_title').append(namePag2);
            }
        },
        addpag: function() {
            this.range += 1;
        },
        loadPersonalObjectives: function() {
            Vue.http.get('/data').then((response) => { this.pos = response.data; });
            // this.$http.get('/classification_manage').then((response) => {
            //     this.pos = response.data.pos;

            // }, (response) => {
            //     console.log(response);
            // });
        }
    }
});

/***/ })
/******/ ]);