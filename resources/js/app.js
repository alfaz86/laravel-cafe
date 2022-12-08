require("./bootstrap");
import $ from "jquery";
window.$ = window.jQuery = $;

import {
    formatNumber,
    datatableLanguage,
} from "./helper";

window.formatNumber = formatNumber;
window.datatableLanguage = datatableLanguage;