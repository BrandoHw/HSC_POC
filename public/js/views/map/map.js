import "../sass/app.scss";

// jQuery
import $ from 'jquery';
window.jQuery = $;
window.$ = $;

// Global
import "./modules/bootstrap";
import "./modules/theme";
import "./modules/moment";
import "./modules/sidebar";
import "./modules/user-agent";

// Datetimepicker
import "./modules/datetimepicker";

// Daterangepicker
import "./modules/daterangepicker";

// AMCharts
import "./modules/amcharts";

// Charts
import "./modules/chartjs";

// Full Calendar
import "./modules/fullcalendar";

// Maps
import "./modules/vector-maps";

// Notyf
import "./modules/notyf";

// Select2
import "./modules/select2";

// Stepper
import "./modules/stepper";

// Leaflet
require('leaflet');
require('leaflet-draw');

// Jquery
import 'jquery-ui/ui/widgets/dialog.js';

//List
import List from "list.js";
window.List = List;
